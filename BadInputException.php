<?php
class BadInputException extends Exception 
{
	public function __construct($message, $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function ToString(): string
	{
		return (__CLASS__ . ': ' . $this->code . ': ' . $this->message . '\n');
	}
}

?>