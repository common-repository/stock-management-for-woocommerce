<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}

//System class that represents an ajax standard response
class mp_stoman_Entities_AjaxResponse
{

	//Properties
	public $success;												//1 means OK, 0 means failure
    public $data;													//reponse data content
	public $errorCode;												//the error code
	public $errorMessage;											//the error message for the user
	public $validationInputId;										//the id of the input control to display as validation error state

}

?>