<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

/**
 * Exception when an argument has been specified both as a positional argument and as a keyword argument.
 */
class DuplicateArgumentException extends ArgumentException
{
	public function __construct($arg0, $arg1)
	{
		$name = "";
		$argumentName = "";
		$argumentPosition = -1;

		if (func_num_args() == 2)
		{
			if (is_string($arg0))
			{
				$name = $arg0;
				$argumentNamem = $arg1;
				$argumentPosition = -1;
			}
			else if ($arg0 instanceof Signature && $arg1 instanceof ArgumentDescription)
			{
				$name = $arg0.getName();
				$argumentName = $arg1.getName();
				$argumentPosition = $arg1.getPosition();
			}
			else if (is_object($arg0) && is_string($arg1))
			{
				$name = $arg0 instanceof UL4Name ? $arg0.getUL4() : Utils::objectType($arg0);
				$argumentNamem = $arg1;
				$argumentPosition = -1;
			}
			else
			{
				throw new \Exception("DuplicateArgumentException.__construct called with two arguments of type " . gettype($arg0) . " and " . gettype($arg1));
			}
		}
		else if (func_num_args() == 3)
		{
			$argumentPosition = func_get_arg(2);
		}
		else
		{
			throw new \Exception("DuplicateArgumentException.__construct called with " . func_num_args() . " arguments. Must be called with 2 or 3 arguments!");
		}

		parent::__construct($name . "() argument " . Utils::repr($argumentName) . ($argumentPosition >= 0 ? " (position " . $argumentPosition . ")": "") . " specified multiple times");
	}
}

?>
