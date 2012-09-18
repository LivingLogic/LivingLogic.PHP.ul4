<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class LoadNone extends LoadConst
{
	public function __construct($location=null)
	{
		parent::__construct($location);
	}

	public function getType()
	{
		return "none";
	}

	public function getValue()
	{
		return null;
	}

	public function toString($indent)
	{
		return "None";
	}

	public function evaluate($context)
	{
		return null;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.none", "\com\livinglogic\ul4\LoadNone");

?>