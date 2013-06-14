<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

/**
 * Exception when a * argument isn't a list
 */
class RemainingArgumentsException extends ArgumentException
{
	public function __construct($arg0)
	{
		$name = "";

		if (is_string($arg0))
			$name = $arg0;
		else if ($arg0 instanceof Signature)
			$name = $arg0->getName();
		else
			$name = $arg0 instanceof UL4Name ? $arg0->nameUL4() : Utils::objectType($arg0);

		parent::__construct($name);
	}
}

?>