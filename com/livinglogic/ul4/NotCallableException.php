<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

/**
 * Thrown by {@link CallFunc} if the object is not callable.
 */
class NotCallableException extends \Exception
{
	public function __construct($obj)
	{
		parent::__construct(Utils::objectType($obj) . " is not callable!");
	}
}

?>
