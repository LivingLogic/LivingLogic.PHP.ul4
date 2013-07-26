<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';


/**
 * Exception when a function is called with positional arguments, but the function doesn't support any
 */
class PositionalArgumentsNotSupportedException extends ArgumentException
{
	public function __construct($obj)
	{
		if (is_string($obj))
			parent::__construct($obj . "() doesn't support positional arguments");
		else if ($obj instanceof Signature)
			parent::__construct($obj->getName() . "() doesn't support positional arguments");
	}
}

?>