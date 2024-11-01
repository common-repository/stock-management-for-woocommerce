<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Adds the client needed resources for the dashboard page
function mp_stoman_ClientResources_InjectDashboardResources()
{

	$methodName = 'mp_stoman_ClientResources_InjectDashboardResources';

	mp_stoman_Logger_AddEntry( 'FUNCTION', $methodName, 'Function has started' );

	require_once(ABSPATH .'wp-includes/pluggable.php');

	//Vendors CSS - Reset
	$reset_css_ver  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'css/reset.css' ));
	wp_enqueue_style( 'mp-stoman-css-reset', mp_stoman_consts_pluginUrl . '/css/reset.css', array(), $reset_css_ver);

	//Vendors CSS - Bootstrap
	$bootstrap_css_ver  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'css/vendors/bootstrap.css' ));
	wp_enqueue_style( 'mp-stoman-css-bootstrap', mp_stoman_consts_pluginUrl . '/css/vendors/bootstrap.css', array(), $bootstrap_css_ver);

	//Vendors CSS - FancyTree win7
	$animate_css_ver  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'css/vendors/fancytree-skinwin7.css' ));
    wp_enqueue_style( 'mp-stoman-css-fancytreewin7', mp_stoman_consts_pluginUrl . '/css/vendors/fancytree-skinwin7.css', array(), $animate_css_ver);

	//Vendors CSS - FontAwesome
	$fontawesome_css_ver  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'css/vendors/font-awesome.min.css' ));
	wp_enqueue_style( 'mp-stoman-css-fontawesome', mp_stoman_consts_pluginUrl . '/css/vendors/font-awesome.min.css', array(), $fontawesome_css_ver);

	//Vendors CSS - Switchery
	$switchery_css_ver  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'css/vendors/switchery.css' ));
	wp_enqueue_style( 'mp-stoman-css-switchery', mp_stoman_consts_pluginUrl . '/css/vendors/switchery.css', array(), $switchery_css_ver);

	//Vendors CSS - ModalEffects
	$animate_css_ver  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'css/vendors/animate.css' ));
	wp_enqueue_style( 'mp-stoman-css-animate', mp_stoman_consts_pluginUrl . '/css/vendors/animate.css', array(), $animate_css_ver);

	//CSS - Dashboard
	$dashboard_css_ver  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'css/dashboard.css' ));
	wp_enqueue_style( 'mp-stoman-css-dashboard', mp_stoman_consts_pluginUrl . '/css/dashboard.css', array(), $dashboard_css_ver);

	//Vendors JS - JQuery
	wp_enqueue_script( 'jquery' );

	//Vendors JS - JQuery UI
	wp_enqueue_script( 'jquery-ui-core' );

	//Vendors JS - Easying
	wp_enqueue_script( 'jqueryeasing-js', mp_stoman_consts_pluginUrl . '/scripts/vendors/jqueryeasing.js');

	//Vendors JS - Bootstrap
	wp_enqueue_script( 'bootstrap-js', mp_stoman_consts_pluginUrl . '/scripts/vendors/bootstrap.js');

	//Vendors JS - fancytree
	wp_enqueue_script( 'fancytree-js', mp_stoman_consts_pluginUrl . '/scripts/vendors/fancytree.min.js' );

	//Vendors JS - DataTable
	wp_enqueue_script( 'datatabe-js', mp_stoman_consts_pluginUrl . '/scripts/vendors/datatable.min.js');

	//Vendors JS - Switchery
	wp_enqueue_script( 'switchery-js', mp_stoman_consts_pluginUrl . '/scripts/vendors/switchery.min.js');


	//Vendors JS - Notify js
	wp_enqueue_script( 'notify-js', mp_stoman_consts_pluginUrl . '/scripts/vendors/bootstrap-notify.min.js');


	//Plugin JS - Shared
	$shared  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'scripts/shared.js' ));
	wp_register_script( 'mp-stoman-shared-js', mp_stoman_consts_pluginUrl . '/scripts/shared.js', array(), $shared, false);
	wp_localize_script( 'mp-stoman-shared-js', 'mp_stoman_vars', mp_stoman_ClientResources_BuildArrayConstantsForScripts());
	wp_enqueue_script( 'mp-stoman-shared-js', mp_stoman_consts_pluginUrl . '/scripts/shared.js');

	//Plugin JS - Dashboard
	$dashboard  = date("ymd-Gis", filemtime( mp_stoman_consts_pluginPath . 'scripts/dashboard.js' ));
	wp_register_script( 'mp-stoman-dashboard-js', mp_stoman_consts_pluginUrl . '/scripts/dashboard.js', array(), $dashboard, false);
	wp_localize_script( 'mp-stoman-dashboard-js', 'mp_stoman_vars', mp_stoman_ClientResources_BuildArrayConstantsForScripts());
	wp_enqueue_script( 'mp-stoman-dashboard-js', mp_stoman_consts_pluginUrl . '/scripts/dashboard.js');

}




//returns the array that will be passed to javascripts files (constants)
function mp_stoman_ClientResources_BuildArrayConstantsForScripts()
{

	$methodName = 'mp_stoman_ClientResources_BuildArrayConstantsForScripts';
	$result = array();

	mp_stoman_Logger_AddEntry( 'FUNCTION', $methodName, 'Function has started' );

	$ajax_nonce = wp_create_nonce( "mp_stoman" );

	$result = array(
	    'ajaxNonce' => $ajax_nonce,
	    'ajaxHandlerUrl' => admin_url( 'admin-ajax.php' ),
		'wooCommerceActive' => class_exists( 'WooCommerce' )
	);

	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Response content: " . mp_stoman_Logger_exportVarContent($result, true));
	mp_stoman_Logger_AddEntry("FUNCTION", $methodName,"End");

	return $result;

}

?>