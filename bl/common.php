<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}



//when there is an error in some function, will return an error object based on WP_Error class
//do not enclose this function in try catches
function mp_stoman_Common_ReturnFunctionError( $methodName, $errorMessage)
{

    $result = new WP_Error( $methodName, $errorMessage);

    mp_stoman_Logger_AddEntry( 'ERROR', $methodName, $errorMessage );

    mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Returning Error object as content: " . mp_stoman_Logger_exportVarContent($result, true));
    mp_stoman_Logger_AddEntry("FUNCTION", $methodName,"End");

    return $result;

}


//returns an ajax error, that will be used in wp_send_json as response
//errorCode can be: "generic", "missingparam"
function mp_stoman_Common_ReturnAjaxError( $methodName, $errorCode, $errorMessage )
{

	$result = new mp_stoman_Entities_AjaxResponse();

	mp_stoman_Logger_AddEntry("ERROR", $methodName, $errorMessage);

	$result->success = 0;
	$result->errorCode = $errorCode;
	$result->errorMessage = $errorMessage;

	wp_send_json($result);

}



//returns an ajax response, that will inform the user about some input validation
function mp_stoman_Common_ReturnAjaxValidationMessage( $methodName, $errorMessage, $inputIdValidationError )
{

	$result = new mp_stoman_Entities_AjaxResponse();

	mp_stoman_Logger_AddEntry("DEBUG", $methodName, "Validation Failed: " + $errorMessage);

	$result->success = 0;
	$result->errorCode = "inputValidation";
	$result->errorMessage = $errorMessage;
	$result->validationInputId = $inputIdValidationError;

	wp_send_json($result);

}

?>