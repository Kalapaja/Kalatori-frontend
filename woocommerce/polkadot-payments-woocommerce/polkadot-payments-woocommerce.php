<?php

/* Plugin name: Polkadot payments for WooCommerce
 * Plugin URI: https://woocommerce.zymologia.fi/Alzymologist/about.html
 * Description: Use Polkadot blokchain for direct payments
 * Author: Alzymologist OY
 * Author URI: https://zymologia.fi
 * Version: 1.0.1
*/

// Не открываем напрямую
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
 * А дальше идёт сам класс, тоже обратите внимание, что он внутри хука plugins_loaded
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

	    // Хук для сохранения всех настроек, как видите, можно еще создать собственный метод process_admin_options() и закастомить всё
	    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

	    // Если будет генерировать токен из данных карты, то по-любому нужно будет подключать какой-то JS
	    // add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );

	    // ну и хук тоже можете тут зарегистрировать
	    // add_action( 'woocommerce_api_{webhook name}', array( $this, 'polkadot_webhook' ) );

	}


//	public function payment_fields() {
//	    return 'wwwwwwwwwwwwwwwwwww';
//	}


	/**
	 * А этот метод пригодится, если захотите добавить форму ввода данных карты прямо на сайт
	 * Подробнее об этом мы поговорим в 4-м шаге
	 */
/*
	public function payment_fields() {

		die('payment_fields()');

	    // окей, давайте выведем описание, если оно заполнено
	    if ( $this->description ) {
		// отдельные инструкции для тестового режима
		if ( $this->testmode ) {
		    $this->description .= ' ТЕСТОВЫЙ РЕЖИМ АКТИВИРОВАН. В тестовом режиме вы можете использовать тестовые данные карт, указанные в <a href="#" target="_blank">документации</a>.';
		    $this->description  = trim( $this->description );
		}
		// описание закидываем в теги <p>
		echo wpautop( wp_kses_post( $this->description ) );
	    }

	    // я использую функцию echo(), но по сути можете закрыть тег PHP и выводить прямо как HTML
	    echo '<fieldset id="wc-' . $this->id . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';

die('oooh');

	    // чтобы разработчики плагинов могли сюда что-то добавить, но не обязательно
	    do_action( 'woocommerce_credit_card_form_start', $this->id );

	    // I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
	    echo '<div class="form-row form-row-wide"><label>Номер карты <span class="required">*</span></label>
		<input id="truemisha_ccNo" type="text" autocomplete="off">
		</div>
		<div class="form-row form-row-first">
		    <label>Срок действия <span class="required">*</span></label>
		    <input id="truemisha_expdate" type="text" autocomplete="off" placeholder="MM / ГГ">
		</div>
		<div class="form-row form-row-last">
		    <label>Код (CVC) <span class="required">*</span></label>
		    <input id="truemisha_cvv" type="password" autocomplete="off" placeholder="CVC">
		</div>
		<div class="clear"></div>';

	    // чтобы разработчики плагинов могли сюда что-то добавить, но не обязательно
	    do_action( 'woocommerce_credit_card_form_end', $this->id );

	    echo '<div class="clear"></div></fieldset>';
	}
*/

	/*
	 * Для подключения дополнительных CSS и JS, нужны также для формы ввода карт и создания токена для них
	 */
        public function payment_scripts() {

	    // if our payment gateway is disabled, we do not have to enqueue JS too
	    if ( 'no' === $this->enabled ) return;

	    // выходим, если находимся не на странице оформления заказа
	    if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
		return;
	    }


//	    die('fff');

/*
wc_print_notice(
    sprintf(
	'Минимальная сумма заказа %s, а у вы хотите заказать всего лишь на %s.' ,
	wc_price( $minimum_amount ),
	wc_price( WC()->cart->subtotal )
    ),
    'notice' // или error
);
*/





// echo "################################################################";

// die('eeeeeeeeeeee');

	//    wp_register_style( 'polkadot-payment', plugins_url( 'css/polkadot.css' , __FILE__ ) );
//@@@@@@@@@@@@@@@@	    wp_register_script( 'polkadot-payment', plugins_url( 'js/polkadot.js?'.rand(0,9999999) , __FILE__ ), array(), rand(0,100).'.'.rand(0,100).'.'.rand(0,100) );
//@@@@@@@@@@@@@@@@	    wp_enqueue_script( 'polkadot-payment' );

/*
add_action('wp_enqueue_scripts', 'override_woo_frontend_scripts');
function override_woo_frontend_scripts() {
    wp_deregister_script('wc-checkout');
    wp_enqueue_script('wc-checkout', get_template_directory_uri() . '/checkout.js', array('woocommerce', 'wc-country-select', 'wc-address-i18n'), null, true);
}
*/






	/*
		if ( is_checkout() && $this->upi_address !== 'hide' ) {
			wp_enqueue_style( 'upiwc-selectize', plugins_url( 'css/selectize.min.css' , __FILE__ ), array(), '0.15.2' );
			wp_enqueue_style( 'upiwc-checkout', plugins_url( 'css/checkout.min.css' , __FILE__ ), array( 'upiwc-selectize' ), UPIWC_VERSION );
			wp_enqueue_script( 'upiwc-selectize', plugins_url( 'js/selectize.min.js' , __FILE__ ), array( 'jquery' ), '0.15.2', false );
		}

		wp_register_style( 'upiwc-jquery-confirm', plugins_url( 'css/jquery-confirm.min.css' , __FILE__ ), array(), '3.3.4' );
		wp_register_style( 'upiwc-payment', plugins_url( 'css/payment.min.css' , __FILE__ ), array( 'upiwc-jquery-confirm' ), UPIWC_VERSION );
		wp_register_script( 'upiwc-qr-code', plugins_url( 'js/easy.qrcode.min.js' , __FILE__ ), array( 'jquery' ), '3.8.3', true );
		wp_register_script( 'upiwc-jquery-confirm', plugins_url( 'js/jquery-confirm.min.js' , __FILE__ ), array( 'jquery' ), '3.3.4', true );
		wp_register_script( 'upiwc-payment', plugins_url( 'js/payment.min.js', __FILE__ ), array( 'jquery', 'upiwc-qr-code', 'upiwc-jquery-confirm' ), UPIWC_VERSION,
	*/


	    // наш платёжный плагин отключен? ничего не делаем
	//    if ( 'no' === $this->enabled ) {
	//	return;
	//    }

	    // также нет смысла подключать JS, если плагин не настроен, не указаны API ключи
	//    if ( empty( $this->private_key ) || empty( $this->publishable_key ) ) {
	//	return;
	//    }

	//    // проверяем также ssl, если плагин работает не в тестовом режиме
	//    if ( ! $this->testmode && ! is_ssl() ) {
	//	return;
	//    }

	    // предположим, что это какой-то JS банка для обработки данных карт
	//    wp_enqueue_script( 'bank_js', 'https://урл-какого-то-банка/api/token.js' );

	    // а это наш произвольный JavaScript, дополняющий token.js
	//    wp_register_script( 'woocommerce_polkadot', plugins_url( 'polkadot.js', __FILE__ ), array( 'jquery', 'bank_js' ) );

	    // допустим, что нам в JavaScript коде понадобится публичный API ключ, передаём его вот так
	//    wp_localize_script( 'woocommerce_polkadot', 'polkadot_params', array(
	//	'publishableKey' => $this->publishable_key
	//    ) );

	//     wp_enqueue_script( 'woocommerce_polkadot' );

// return "############################################################";

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
    $daemon_url = 'http://localhost:16726';
    $r=ajax($daemon_url."/order/0/price/0");
    $json=array(); foreach($r as $n=>$l) $json['daemon_'.$n]=$l;
    jdie($json);
}








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


add_action( 'woocommerce_store_api_checkout_order_processed', 'polkadot_callback' ); // ,10)
// Function for `woocommerce_store_api_checkout_order_processed` action-hook.
function polkadot_callback( $order ) {

    $daemon_url = 'http://localhost:16726';

    // payment request
    $order_id = $order->get_id();
    $hash = WC()->cart->get_cart_hash();
    $price = $order->get_total();
    $currency = $order->get_currency();
    $status = $order->get_status();
    $user_id = WC()->session->get_customer_id();
    $json=array(
	'order_id' => "wc.".$hash.".".$order_id.".".$user_id,
	'price' => $price // * $_GET['mul']
    );
    $r=ajax($daemon_url."/order/".$json['order_id']."/price/".$json['price']);

    if(isset($_GET['ajax'])) {
	foreach($r as $n=>$l) $json['daemon_'.$n]=$l;
	$order->set_status('pending', 'Order is saved and pending payment.', true);
	$order->save();
	jdie($json);
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

// //////////////////////////////////////////////
// //////////////////////////////////////////////
// //////////////////////////////////////////////
// //////////////////////////////////////////////
// //////////////////////////////////////////////
  function p($a) {
    if($a===false) return 'FALSE'."\n";
    if($a===true) return 'TRUE'."\n";
    if($a===null) return 'NULL'."\n";
    if(empty($a)) return 'EMPTY'."\n";
    if(gettype($a)=='string') return "(string) [".$a."]\n";
    if(gettype($a)=='integer') return "(integer) [".$a."]\n";
    return print_r($a,1)."\n";
  }

  function ejdie($s) { $this->jdie(array('error'=>1,'error_message'=>$s)); }

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
    if(isset($r['result'])) {
        if($r['result']=='waiting') $r['result']='Waiting';
        if($r['result']=='paid') $r['result']='Paid';
    }
    $r['order_id']=preg_replace("/^.*\_/s",'',$r['order_id']);
    if( isset($r['mul']) && $r['mul'] < 20 ) $r['mul']=pow(10, $r['mul']);
    return $r;
  }

  function logs($s='') { }

// https://woocommerce.zymologia.fi/wp-json/polkadot/v1/checkout-data

/*
    die(""
	."\n get_status                :".$order->get_status()
	."\n get_total                 :".$order->get_total()
	."\n get_currency              :".$order->get_currency()
	."\n get_checkout_payment_url  :".$order->get_checkout_payment_url()
	."\n has_status() == ".intval( $order->has_status( 'on-hold' ))
    );
$order->update_status( 'cancelled',
$order->update_status( 'failed' );
$order->update_status( apply_filters( 'wc_ppcp_capture');
$order->update_status( 'on-hold', sprintf(
$order->update_status( 'wc-refunded' );^M
$order->update_status( 'wc-cancelled', sprintf( 'The zipMoney charge (id:%s) has been cancelled.', $charge->getId() 
$order->update_status( WC_Zipmoney_Payment_Gateway_Config::ZIP_ORDER_STATUS_AUTHORIZED_KEY );
$order->update_status( 'cancelled', 'order_note' );
$order->set_status(apply_filters( 'authorize_status', 'on-hold' ),
$order->set_status( 'on-hold' );
$this->set_processing( 'capture' );

$order->payment_complete( $result->get_capture_id() );
$order->payment_complete( $charge->getId() );^M
$order->payment_complete();


$order->set_transaction_id( $capture->getId() );
$order->add_order_note( sprintf( __( 'Error processing payment. Reason: %s', 'pymntpl-paypal-woocommerce' ),

$this->add_payment_complete_message( $order, $result );
$this->save_order_meta_data( $order, $result->paypal_order );
do_action( 'wc_ppcp_order_payment_complete', $order, $result, $this );
do_action( 'woocommerce_checkout_order_processed', $order_id, (array) $WC_Session, $order );

// Add the authorised status for payment complete^M
add_filter( 'woocommerce_valid_order_statuses_for_payment_complete',
 array( $this, 'filter_add_authorize_order_status_for_payment_complete' ) );

public function filter_add_authorize_order_status_for_payment_complete( $statuses ) {
><------>$statuses[] = str_replace( 'wc-', '', WC_Zipmoney_Payment_Gateway_Config::ZIP_ORDER_STATUS_AUTHORIZED_KEY
*/
