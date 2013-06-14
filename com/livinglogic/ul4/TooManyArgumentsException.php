<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

/**
 * Exception when to many positional arguments are passed
 */
class TooManyArgumentsException extends ArgumentException
{
	public function __construct($arg0, $arg1)
	{
		$name = "";
		$expected = 0;
		$given = 0;

		if (func_num_args() == 2)
		{
			$name = $arg0->getName();
			$expected = $arg0->size();
			$given = $arg1;
		}
		else if (func_num_args() == 3)
		{
			$name = $arg0;
			$expected = $arg1;
			$given = func_get_arg(2);
		}
		else
			throw new \Exception("TooManyArgumentsException.__construct called with " . func_num_args() . " arguments. Must be called with 2 or 3 arguments");

		parent::__construct($name . "() expects at most " . $expected . " positional argument" . ($expected != 1 ? "s" : "") . ", " , $given . " given");
	}
}

?>
