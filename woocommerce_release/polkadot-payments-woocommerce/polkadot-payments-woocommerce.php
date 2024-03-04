<?php

/* Plugin name: Polkadot payments for WooCommerce
 * Plugin URI: https://woocommerce.zymologia.fi/Alzymologist/about.html
 * Description: Use Polkadot blokchain for direct payments
 * Author: Alzymologist OY
 * Author URI: https://zymologia.fi
 * Version: 1.0.1
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Этот фильтр-хук позволяет зарегистрировать наш PHP-класс в качестве платёжного шлюза WooCommerce
 */
add_filter( 'woocommerce_payment_gateways', 'polkadot_register_gateway_class' );
function polkadot_register_gateway_class( $gateways ) {
    $gateways[] = 'WC_Polkadot_Gateway'; // название вашего класса, добавляем его в общий массив
    return $gateways;
}

/*
 * сам класс внутри хука plugins_loaded
 */

add_action( 'plugins_loaded', 'polkadot_gateway_class' );
function polkadot_gateway_class() {

    class WC_Polkadot_Gateway extends WC_Payment_Gateway {
	/**
	 * Это конструктор класса, о нём мы с вами ещё поговорим в 3-м шаге урока
	 */
	public function __construct() {

	    $this->id = 'polkadot'; // ID платёжного шлюза
	    $this->icon = 'https://assets.polkadot.network/brand/Polkadot_Logo/Horizontal/SVG/Transparent/Polkadot_Logo_Horizontal_Pink-White.svg'; // 'https://lleo.me/fon1.jpg'; // URL иконки, которая будет отображаться на странице оформления заказа рядом с этим методом оплаты
	    // $this->has_fields = true; // если нужна собственная форма ввода полей карты
	    $this->method_title = 'Polkadot payments';
	    $this->method_description = 'Polkadot direct payments'; // будет отображаться в админке
	    // платёжные плагины могут поддерживать подписки, сохранённые карты, возвраты, но начнём с простых платежей
	    $this->supports = array( 'products' );

	    // все поля настроек админки
	    $this->form_fields = array(
		'enabled' => array(
		    'title'       => 'On/Off',
		    'label'       => 'Turn on Polkadot payments',
		    'type'        => 'checkbox',
		    'description' => '',
		    'default'     => 'no'
		),
		'store_name' => array(
		    'title'       => 'Store name',
		    'description' => 'Enter your store name or leave blank',
		    'default'     => '',
		    'type'        => 'text'
		),
		'daemon_url' => array(
		    'title'       => 'Daemon url',
		    'description' => 'Enter your daemon url like http://localhost:16726',
		    'default'     => 'http://localhost:16726',
		    'type'        => 'text'
		),
	    );

	    // инициализируем настройки
	    $this->init_settings();
	    $this->enabled = $this->get_option( 'enabled' );
	    $this->store_name = $this->get_option( 'store_name' );
	    $this->daemon_url = $this->get_option( 'daemon_url' );
	    // Хук для сохранения всех настроек
	    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}
    }
}


// https://woocommerce.zymologia.fi/wp-json/kalatori/v1/health
add_action('rest_api_init', function () {
    register_rest_route('kalatori/v1', '/health/', array(
        'methods' => 'POST',
        'callback' => 'kalatori_health_ajax',
    ));
});
function kalatori_health_ajax(WP_REST_Request $request) {
    $opt = get_option( 'woocommerce_polkadot_settings', [] );
    $daemon_url = $opt['daemon_url']; if(empty($daemon_url)) $daemon_url='http://localhost:16726';
    $r=ajax($daemon_url."/order/0/price/0");
    jdie($r);
}


add_action( 'woocommerce_store_api_checkout_order_processed', 'polkadot_callback' ); // ,10)
// Function for `woocommerce_store_api_checkout_order_processed` action-hook.
function polkadot_callback( $order ) {
    $opt = get_option( 'woocommerce_polkadot_settings', [] );
    if('no' === $opt['enabled']) ejdie('Plugin disabled. Use admin page for enable');
    // if( ! is_cart() ) ||&& ! is_checkout()
    $daemon_url = $opt['daemon_url']; if(empty($daemon_url)) $daemon_url='http://localhost:16726';
    $store_name = $opt['store_name']; if(empty($store_name)) $store_name='';

    // payment request
    $order_id = $order->get_id();
    $hash = WC()->cart->get_cart_hash();
    $price = $order->get_total();
    $currency = $order->get_currency();
    $status = $order->get_status();
    $user_id = WC()->session->get_customer_id();
    $json=array(
	'order_id' => "wc.".$store_name.".".$hash.".".$order_id.".".$user_id,
	'price' => $price // * $_GET['mul']
    );
    $r=ajax($daemon_url."/order/".$json['order_id']."/price/".$json['price']);

    if(!isset($r['result'])) ejdie($r);

    if(isset($_GET['ajax'])) {
	$order->set_status('pending', 'Order is saved and pending payment.', true);
	$order->save();
	jdie($r);
    }

    // не Ajax, реально кнопку нажали и снова проверили
    if(strtolower($r['result'])=='paid') {
	$order->add_order_note( 'Paid by DOT: success' );
	// $order->set_status( 'on-hold' );
	$order->payment_complete();
	$order->save();
	return true;
    }

    http_response_code(500);
    die(print_r($r,1));
    // die('{"error":"kalatori payment"}');
    // return true;
    return false;
}

// Registering plugin
add_action('plugins_loaded', function() {
  if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
    require_once plugin_dir_path( __FILE__ ) . '/Polkadot.class.php';
    add_action( 'woocommerce_blocks_payment_method_type_registration',
	function ($registry) {
	    $registry->register( new Automattic\WooCommerce\Blocks\Payments\Integrations\Polkadot() );
	}
    );
 }
});

// //////////////////////////////////////////////

  function ejdie($s) { jdie(array('error'=>1,'error_message'=>$s)); }
  function jdie($j) { die(json_encode($j)); }

  function ajax($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FAILONERROR => true,
        CURLOPT_CONNECTTIMEOUT => 2, // only spend 3 seconds trying to connect
        CURLOPT_TIMEOUT => 2 // 30 sec waiting for answer
    ));
    $r = curl_exec($ch);
    if(curl_errno($ch) || empty($r)) return array('error'=>'connect','error_message'=>curl_error($ch),'url'=>$url);

    $r = (array)json_decode($r);
    if(empty($r)) return array('error'=>'json','error_message'=>'Wrong json format','url'=>$url);
    curl_close($ch);

    // Йобаные патчи для kalatori
    if(isset($r['order'])) $r['order_id']=$r['order'];
    if(isset($r['price'])) $r['price']=1*$r['price'];
    $r['order_id']=preg_replace("/^.*\_/s",'',$r['order_id']);
    if( isset($r['mul']) && $r['mul'] < 20 ) $r['mul']=pow(10, $r['mul']);
    return $r;
  }
