<?php

namespace com\livinglogic\ul4;

/**
 * Exception when a required argument to a function is missing
 */
class MissingArgumentException extends ArgumentException
{
	public function __construct($arg0, $arg1)
	{
		$name = "";
		$argumentName = "";
		$argumentPosition = -1;

		if (func_num_args() == 2)
		{
			$name = $arg0->getName();
			$argumentName = $arg1->getName();
			$argumentPosition = $arg1->getPosition();
		}
		else if (func_num_args() == 3)
		{
			$name = $arg0;
			$argumentName = $arg1;
			$argumentPosition = func_get_arg(2);
		}
		else
			throw new \Exception("MissingArgumentException.__construct called with " . func_num_args() . " arguments. Must be called with 2 or 3!");

		parent::__construct("required " . $name . "() argument " . Utils::repr($argumentName) . " (position " . $argumentPosition . ") missing");
	}
}

?>
