<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_mp_stoman_products_stockdetails_save', 'mp_stoman_products_stockdetails_save' );
add_action( 'wp_ajax_mp_stoman_products_list', 'mp_stoman_products_list' );


//Lists the products given some filters
//sPOST[CategoryId] is a mandatory param, pass =-1 to include all prodcuts without category filter
//sPost[SearchKey] is a mandatory param, leave emtpy for no filter
function mp_stoman_products_list()
{

	$methodName = 'mp_stoman_products_listbycategory';

	$response = new mp_stoman_Entities_AjaxResponse();

	mp_stoman_Logger_AddEntry("FUNCTION", $methodName, "Start");
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . mp_stoman_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'mp_stoman', 'security' );
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["CategoryId"]) == false)
	{
	    mp_stoman_Common_ReturnAjaxError($methodName, "generic", "POST['CategoryId'] parameter is missing. Aborting.");
	}
	$postCategoryId = sanitize_text_field($_POST["CategoryId"]);
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param CategoryId: " . mp_stoman_Logger_exportVarContent($postCategoryId, true));
	if ( isset($_POST["SearchKey"]) == false)
	{
	    mp_stoman_Common_ReturnAjaxError($methodName, "generic", "POST['SearchKey'] parameter is missing. Aborting.");
	}
	$postSearchKey = sanitize_text_field($_POST["SearchKey"]);
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param SearchKey: " . mp_stoman_Logger_exportVarContent($postSearchKey, true));

	//getting products by given category
	if ($postCategoryId != -1)
	{

		//filtering by the specified catory
		$args = array
		(
			'posts_per_page' => -1,
			'tax_query' => array(
									array(
											'taxonomy' => 'product_cat',
											'field' => 'term_id',
											'terms' => $postCategoryId,
											'operator' => 'IN'
										 )
								),
			'post_type' => 'product',
			'orderby' => 'title,'
		);

	}
	else
	{

		//category passed is -1 (root) so returning all products
		$args = array
		(
			'posts_per_page' => -1,
			'post_type' => 'product',
			'orderby' => 'title,'
		);

	}

	$productsList = new WP_Query( $args );

	//filters by search key
	$productsListFiltered = array();
	foreach( $productsList->posts as $productPost )
	{

		// Get an instance of the product object
	    $productWC = wc_get_product( $productPost->ID );

	    //Get the additional details for the product
		$product_sku = $productWC->get_sku();

		$addProduct = true;

		if( $postSearchKey != "")
		{

			if (stripos($productPost->post_title, $postSearchKey) === false )
			{
			    $addProduct = false;
			}

		}

		if ($addProduct == true)
		{
			array_push($productsListFiltered, $productPost);
		}

	}

	//normalizing data
	$resultListProducts = array();
	foreach( $productsListFiltered as $productPost )
	{

	    // Get an instance of the product object
	    $productWC = wc_get_product( $productPost->ID );

	    //Get the additional details for the product
	    $product_stockquantity = $productWC->get_stock_quantity();
		$product_sku = $productWC->get_sku();
		$product_stockenabled = $productWC->managing_stock();

		$newProductObject = array();
		$newProductObject["productId"] = $productPost->ID;
		$newProductObject["productTitle"] = $productPost->post_title;
		$newProductObject["productStockEnabled"] = $product_stockenabled;
		$newProductObject["productStockQuantity"] = $product_stockquantity;
		$newProductObject["productSKU"] = $product_sku;

		//adjusts
		if( $newProductObject["productStockQuantity"] == null )
		{
			$newProductObject["productStockQuantity"] = 0;
		}

		array_push($resultListProducts, $newProductObject);

	}

    //building response
	$response->success = 1;
	$response->data = $resultListProducts;

	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Response content: " . mp_stoman_Logger_exportVarContent($response, true));
	mp_stoman_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Updates the stock details of a product
//sPOST[ProductId] is a mandatory param
//sPOST[NewStockQuantity] is an optional mandatory param
function mp_stoman_products_stockdetails_save()
{

	$methodName = 'mp_stoman_products_stockdetails_save';

	$response = new mp_stoman_Entities_AjaxResponse();

	mp_stoman_Logger_AddEntry("FUNCTION", $methodName, "Start");
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . mp_stoman_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'mp_stoman', 'security' );
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["ProductId"]) == false)
	{
	    mp_stoman_Common_ReturnAjaxError($methodName, "generic", "POST['ProductId'] parameter is missing. Aborting.");
	}
	$postProductId = sanitize_text_field($_POST["ProductId"]);
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param ProductId: " . mp_stoman_Logger_exportVarContent($postProductId, true));

	//checking for other params
	$postNewStockQuantity = null;
	if ( isset($_POST["NewStockQuantity"]))
	{
		$postNewStockQuantity = sanitize_text_field($_POST["NewStockQuantity"]);
	}
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param NewStockQuantity: " . mp_stoman_Logger_exportVarContent($postNewStockQuantity, true));
	$postproductStockEnabled = null;
	if ( isset($_POST["StockEnabled"]))
	{
		$postproductStockEnabled = sanitize_text_field($_POST["StockEnabled"]);
	}
	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param StockEnabled: " . mp_stoman_Logger_exportVarContent($postproductStockEnabled, true));


	// Get an instance of the product object
	$product = wc_get_product( $postProductId );

	//checks if the product was found
	if ( $product != null )
	{

		//updates stock managed property (must be yes or no)
		if ( $postproductStockEnabled != null )
		{
			$manage_stock_value = "";
			if ($postproductStockEnabled == 'true')
			{
				$manage_stock_value = "yes";
				mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Setting Stock Manage to to true");
			}
			else
			{
				$manage_stock_value = "no";
				mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Setting Stock Manage to to false");
			}
			update_post_meta( $postProductId, '_manage_stock', $manage_stock_value );
		}

		//updates the stock quantity, if the parameter was passed
		if ( $postNewStockQuantity != null )
		{
			wc_update_product_stock( $product, $postNewStockQuantity );
		}

	}
	else
	{
		mp_stoman_Common_ReturnAjaxError($methodName, "generic", "No product found with such id.");
	}

    //building response
	$response->success = 1;
	$response->data = $product->get_name();

	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Response content: " . mp_stoman_Logger_exportVarContent($response, true));
	mp_stoman_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>