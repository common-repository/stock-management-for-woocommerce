<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Called from the main file, it will include all needed php files for the plugin
function mp_stoman_Includes_IncludeFiles()
{

	//priority files
	include( mp_stoman_consts_pluginPath . '/bl/globals.php' );

	//including loggin utility
	include( mp_stoman_consts_pluginPath . '/utils/log/logger.php' );

	//including bl files
	include( mp_stoman_consts_pluginPath . '/bl/client_resources.php' );
	include( mp_stoman_consts_pluginPath . '/bl/common.php' );
	include( mp_stoman_consts_pluginPath . '/bl/pages.php' );

	//including entities
	include( mp_stoman_consts_pluginPath . '/entities/entity_ajaxresponse.php' );
	include( mp_stoman_consts_pluginPath . '/entities/entity_category.php' );

	//including classes
	include( mp_stoman_consts_pluginPath . '/classes/class_category.php' );

	//including ajax files
	include( mp_stoman_consts_pluginPath . '/ajax/ajax_products.php' );
	include( mp_stoman_consts_pluginPath . '/ajax/ajax_categories.php' );

}


?>