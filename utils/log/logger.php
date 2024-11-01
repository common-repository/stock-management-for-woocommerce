<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Adds a new log entry
function mp_stoman_Logger_AddEntry( $type, $event, $message )
{

	//reading enabled log setting
	$optionLogErrors = get_option( mpstoman_consts_optionKeyEventLogLogErrors, 0 );
	$optionLogEmails = get_option( mpstoman_consts_optionKeyEventLogLogEmails, 0 );
	$optionLogDebug = get_option( mpstoman_consts_optionKeyEventLogLogDebug, 0 );

	$writeThisLog = 0;

	if ( $type == "QUERY" || $type == "FUNCTION" || $type == "DEBUG" )
	{
		if ( $optionLogDebug == 1 )
		{
			$writeThisLog = 1;
		}
	}
	if ( $type == "ERROR" )
	{
		if ( $optionLogErrors == 1 )
		{
			$writeThisLog = 1;
		}
	}
	if ( $type == "EMAIL" )
	{
		if ( $optionLogEmails == 1 )
		{
			$writeThisLog = 1;
		}
	}

	if ( $writeThisLog == 1 )
	{

		//checks the permissions on the file
		if ( mp_stoman_Logger_CheckFileWritable() )
		{

			// Logging class initialization
			$log = new mp_stoman_Logger_helper();

			// write message to the log file
			$log->lwrite( $type, $event, $message);

			// close log file
			$log->lclose();

		}

	}

}


//Returns true if the file is readable and writable
function mp_stoman_Logger_CheckFileWritable()
{

	$result = false;

	if ( is_readable( mp_stoman_consts_logFilePath ) )
	{

		if ( is_writeable( mp_stoman_consts_logFilePath ) )
		{

			$result = true;

		}

	}

	return $result;

}


//Clears the log file
function mp_stoman_Logger_ClearLogFile()
{

	//Checks that the file exists and clears it
	if( file_exists( mp_stoman_consts_logFilePath ) )
	{

		if ( mp_stoman_Logger_CheckFileWritable() )
		{

			file_put_contents( mp_stoman_consts_logFilePath , "");

		}

	}

}


//Reads the log file row by row and returns an array of objects
function mp_stoman_Logger_ReadLogFile()
{

	$result = array();

	if( file_exists( mp_stoman_consts_logFilePath ) )
	{

		if ( mp_stoman_Logger_CheckFileWritable() )
		{

			$arrayLines = ( file( mp_stoman_consts_logFilePath , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

			//reversing the array and building an object array instead
			foreach ( array_reverse( $arrayLines ) as $line )
			{

				$lineDataArray =  explode("*/*/*", $line);

				$newLogInfo = new stdClass();

				//checks for dirty lines
				if ( count($lineDataArray) == 4)
				{

					$newLogInfo->Date = $lineDataArray[0];
					$newLogInfo->Type = $lineDataArray[1];
					$newLogInfo->Method = $lineDataArray[2];
					$newLogInfo->EventText = $lineDataArray[3];

					if ( $newLogInfo->Type=="QUERY" )
					{
						$newLogInfo->EventText = str_replace("\r", " ", $newLogInfo->EventText);
					}
					if ( $newLogInfo->Type=="FUNCTION" )
					{
						$newLogInfo->TextColor="black";
					}
					if ( $newLogInfo->Type=="DEBUG" || $newLogInfo->Type=="QUERY" )
					{
						$newLogInfo->TextColor="black";
					}
					if ( $newLogInfo->Type=="INFO" )
					{
						$newLogInfo->TextColor="black";
					}
					if ( $newLogInfo->Type=="WARNING")
					{
						$newLogInfo->TextColor="orange";
					}
					if ( $newLogInfo->Type=="ERROR")
					{
						$newLogInfo->TextColor="red";
					}

					array_push( $result, $newLogInfo );

				}

			}

		}

	}

	return $result;

}


//returns the size of the file in bites
function mp_stoman_Logger_ReturnSize()
{

	if ( mp_stoman_Logger_CheckFileWritable() == true )
	{
		return filesize(mp_stoman_consts_logFilePath);
	}
	else
	{
		return -1;
	}

}


//returns true if the system should suggest to user to clear log file (eg too big)
function mp_stoman_Logger_TimeToClearIt()
{

	// 10000000 = 10megabytes roughly

	if ( mp_stoman_Logger_ReturnSize() > 10000000 )
	{
		return true;
	}
	else
	{
		return false;
	}

}


//This function returns a log readable representation of a variable
function mp_stoman_Logger_exportVarContent( $variable )
{

	return str_replace("\n", '', var_export($variable, true));

}


//Class used to manage logging
class mp_stoman_Logger_Helper {

	// declare file pointer as private properties
	private $fp;

	// write message to the log file
	public function lwrite($type, $event, $message) {

		try
		{

			// if file pointer doesn't exist, then open log file
			if (!is_resource($this->fp)) {
				$this->lopen();
			}

			// define current time and suppress E_WARNING if using the system TZ settings
			$time = @date('[d/M/Y:H:i:s]');

			// write current time, script name and message to the log file
			fwrite($this->fp, "$time*/*/*$type*/*/*$event*/*/*$message" . PHP_EOL);

		}
		catch(Exception $e)
		{

		}

	}

	// close log file (it's always a good idea to close a file when you're done with it)
	public function lclose() {

		try
		{

			fclose($this->fp);

		}
		catch(Exception $e)
		{

		}
	}

	// open log file (private method)
	private function lopen() {

		try
		{

			// open log file for writing only and place file pointer at the end of the file
			// (if the file does not exist, try to create it)
			$this->fp = fopen( mp_stoman_consts_logFilePath, 'a') or exit();

		}
		catch(Exception $e)
		{

		}

	}

}

?>