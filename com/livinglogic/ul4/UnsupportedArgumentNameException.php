<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

/**
 * Exception when an argument has been by keyword that is not supported
 */
class UnsupportedArgumentNameException extends ArgumentException
{
	public function __construct($arg0, $argumentName)
	{
		$name = "";

		if (is_string($arg0))
			$name = $arg0;
		else if ($arg0 instanceof Signature)
			$name = $arg0.getName();
		else
			throw new \Exception("UnsupportedArgumentNameException.__construct called with type of first parameter " . gettype($arg0) . ". Must be string!");

		parent::__construct($name . "() doesn't support an argument named " . Utils::repr($argumentName));
	}
}

?>
