<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class LoadTrue extends LoadConst
{
	public function __construct($location=null)
	{
		parent::__construct($location);
	}

	public function getType()
	{
		return "true";
	}

	public function getValue()
	{
		return Boolean.TRUE;
	}

	public function toString($indent)
	{
		return "True";
	}

	public function evaluate($context)
	{
		return true;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.true", "\com\livinglogic\ul4\LoadTrue");

?>