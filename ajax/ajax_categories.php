<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_mp_stoman_categories_listfortreeview', 'mp_stoman_categories_listfortreeview' );


//Returns the list for the treeview category object
function mp_stoman_categories_listfortreeview()
{

	$methodName = 'mp_stoman_categories_listfortreeview';

	$response = new mp_stoman_Entities_AjaxResponse();

	mp_stoman_Logger_AddEntry("FUNCTION", $methodName, "Start");
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . mp_stoman_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'mp_stoman', 'security' );
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	$classCategory = new mp_stoman_Classes_Category();

	//getting categories for treeview rendering
	$listCategoriesForTreeview = $classCategory->ListAsArrayForTreeView( null );

    //building response
	$response->success = 1;
	$response->data = $listCategoriesForTreeview;

	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Response content: " . mp_stoman_Logger_exportVarContent($response, true));
	mp_stoman_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>