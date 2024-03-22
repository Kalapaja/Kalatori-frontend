<section>
    <div id='polkadot_work'><img src='{$module_host nofilter}/img/ajaxm.gif'> Loading plugin</div>
</section>

<script>
    var s=document.createElement('script');
    s.type='text/javascript';
    s.src="{$module_host nofilter}/js/DOT.js"
	    + '?random='+Math.random() // DEBUG ONLY!
    ;
    s.onerror=function(e){ alert('DOT plugin: script not found: '+e.src) };
    s.onload=function(e) {
	DOT.presta_init({
	    wpath:	 "{$module_host nofilter}",
	    ajax_host:	 "{$ajax_host nofilter}",
	    total:	 "{$total nofilter}",
	    module_name: "{$module_name nofilter}",
	    order_id:	 "{$order_id nofilter}",
	    shop_id:	 "{$shop_id nofilter}",
	    products:	 "{$products nofilter}",
	});
    };
    document.getElementsByTagName('head').item(0).appendChild(s);
</script>