<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class SyntaxException extends \Exception
{
	public function __construct($message, $cause)
	{
		parent::__construct($message, 0, $cause);
	}
}

?>