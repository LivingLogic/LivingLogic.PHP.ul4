<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class UndefinedVariable extends Undefined
{
	private $varname;

	public function __construct($varname)
	{
		$this->$varname = $varname;
	}

	public function toString()
	{
		return "undefined variable " . Utils::repr($this->varname);
	}
}

?>