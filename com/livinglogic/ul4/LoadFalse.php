<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class LoadFalse extends LoadConst
{
	public function __construct($location=null)
	{
		parent::__construct($location);
	}

	public function getType()
	{
		return "false";
	}

	public function getValue()
	{
		return Boolean.FALSE;
	}

	public function toString($indent)
	{
		return "False";
	}

	public function evaluate($context)
	{
		return false;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.false", "\com\livinglogic\ul4\LoadFalse");

?>