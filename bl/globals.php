<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}

//Ensure global is declared
global $wpdb;

//define some options keys used in the Wordpress options table
define( 'mpstoman_consts_optionKeyEventLogLogErrors', 'mpstomanEventLogLogErrors' );
define( 'mpstoman_consts_optionKeyEventLogLogEmails', 'mpstomanEventLogLogEmails' );
define( 'mpstoman_consts_optionKeyEventLogLogDebug', 'mpstomanEventLogLogDebug' );

//define some constants
define( 'mp_stoman_consts_pluginVersion', '1.1.2' );
define( 'mp_stoman_consts_logFilePath', mp_stoman_consts_pluginPath . '/utils/log/log.txt' );

?>