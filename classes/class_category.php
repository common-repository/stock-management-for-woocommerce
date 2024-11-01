<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}

//Class that handles categories
class mp_stoman_Classes_Category
{

	//Lists all the categories
	function ListAsArrayForTreeView( $params )
	{

		$methodName = 'mp_stoman_Classes_Category.ListFromDB';
		mp_stoman_Logger_AddEntry("FUNCTION", $methodName, "Start");
		mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Params values: " . mp_stoman_Logger_exportVarContent($params, true));

		$result = array();

		//root category (all)
		$categoryRootEntity = array();
		$categoryRootEntity["key"] = "-1";
		$categoryRootEntity["title"] = "All Categories";
		$categoryRootEntity["children"] = array();
		array_push( $result, $categoryRootEntity );

		//loads all the categories recursively
		$this->ListAsArrayForTreeView_Recursive( $result[0]["children"], 0 );

		mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . mp_stoman_Logger_exportVarContent($result, true));
		mp_stoman_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;
	}


	//Recursive function called from the main function
	function ListAsArrayForTreeView_Recursive( &$collection, $parent = 0)
	{

		$terms = get_terms("product_cat", array('parent' => $parent, 'hide_empty' => false));

		//If there are terms, start displaying
		if( count($terms) > 0 )
		{

			foreach ($terms as $term)
			{

			    $categoryEntity = array();

			    $categoryEntity["key"] = $term->term_id;
			    $categoryEntity["title"] = $term->name;
				$categoryEntity["children"] = array();

				//recursive
				$this->ListAsArrayForTreeView_Recursive( $categoryEntity["children"], $term->term_id);

			    array_push( $collection, $categoryEntity );

			}

		}

	}

}

?>