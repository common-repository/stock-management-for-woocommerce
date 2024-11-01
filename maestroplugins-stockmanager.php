<?php


/**
 * @package MaestroPluginsStockManager
 */
/*
Plugin Name: Stock Management for WooCommerce
Description: Manage your WooCommerce Stock Easily
Version: 1.0.0
Author: Maestro Plugins
License: GPLv2 or later
Text Domain: mp_stoman
 */


//Checks if the ABSPATH is defined, and exits in case it is not
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Defining some constants
define( 'mp_stoman_consts_pluginPath', plugin_dir_path( dirname(__FILE__) . '/maestroplugins-stockmanager.php') );
define( 'mp_stoman_consts_pluginUrl', plugin_dir_url( dirname(__FILE__) . '/maestroplugins-stockmanager.php') );


//including the file that will manage all inclusions
include_once( mp_stoman_consts_pluginPath . '/bl/includes.php' );
mp_stoman_Includes_IncludeFiles();


//Plugin init function
function mp_stoman_Main_Init()
{

	//Adding menu pages in WP dashbaord
	add_action( 'admin_menu', 'mp_stoman_Pages_AddAdminPage' );

}
add_action( 'init', 'mp_stoman_Main_Init' );


//Adding Languages
load_plugin_textdomain( 'mp-stoman', false, dirname(plugin_basename(__FILE__) ) . '/languages/');

?>
