<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class UnpackingException extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}

?>