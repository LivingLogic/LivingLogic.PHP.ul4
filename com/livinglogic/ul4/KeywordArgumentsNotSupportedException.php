<?php

namespace com\livinglogic\ul4;

/**
 * Exception when a function is called with keyword arguments, but the function doesn't support any
 */
class KeywordArgumentsNotSupportedException extends ArgumentException
{
	public function __construct($arg0)
	{
		if (is_string($arg0))
			$name = $arg0;
		else if ($arg0 instanceof Signature)
			$name = $arg0->getName();
		else
			throw new \Exception("KeywordArgumentsNotSupportedException can not be constructed with type " . gettype($arg0));

		parent::__construct($name . "() doesn't support keyword arguments");
	}
}

?>
