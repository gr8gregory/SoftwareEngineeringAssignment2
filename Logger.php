<?php
class Logger
{
	public static function writeToConsole(string $valToLog) : string
	{
		return "<script>console.log('" . $valToLog . "');</script>";
	}
}
?>