<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Called from main plugin file, it will create the page in Wordpress admin dashboard
function mp_stoman_Pages_AddAdminPage()
{

    //The capability that the user must have in order to access the page
	$capabilityName = 'manage_options';

	//points to the normal dashboard page
	$generatedPage = add_menu_page( 'Stock Manager', 'Stock Manager', $capabilityName, 'mp_stoman', 'mp_stoman_Pages_AddAdminPage_callback', mp_stoman_consts_pluginUrl . '/images/wp-pluginlogo.png', 76 );

	//Adds the page and calls the function to include client resources needed
	add_action( 'load-' . $generatedPage, 'mp_stoman_ClientResources_InjectDashboardResources' );

}
function mp_stoman_Pages_AddAdminPage_callback() {

	//Including the page
	include( mp_stoman_consts_pluginPath . "/pages/dashboard.php" );

}


?>