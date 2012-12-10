<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

/**
 * Thrown when a key can't be found in a map. This exception is also used when
 * a variable can't be found in the top-level variables map.
 */
class KeyException extends \Exception
{
	public function __construct($key)
	{
		parent::__construct("Key '" . $key . "' not found!");
	}
}

?>