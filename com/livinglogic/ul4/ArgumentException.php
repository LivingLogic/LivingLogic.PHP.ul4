<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

/**
 * Base class of all exceptions for some kind of problems with argument.
 */
class ArgumentException extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}

?>