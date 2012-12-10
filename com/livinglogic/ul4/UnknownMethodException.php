<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

/**
 * Thrown when an unknown method is encountered in an UL4 template.
 */
class UnknownMethodException extends \Exception
{
	public function __construct($methodName)
	{
		parent::__construct("Method '" . $methodName . "' unknown!");
	}
}

?>