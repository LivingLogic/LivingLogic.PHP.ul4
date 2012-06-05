<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class UnknownFunctionException extends \Exception
{
	public function __construct($functionName)
	{
		parent::__construct("Function '" + functionName + "' unknown!");
	}
}

?>