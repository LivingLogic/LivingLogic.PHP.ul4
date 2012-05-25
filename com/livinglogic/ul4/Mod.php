<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Mod extends Binary
{
	public function __construct($location=null, $obj1=null, $obj2=null)
	{
		parent::__construct($location, $obj1, $obj2);
	}

	public function getType()
	{
		return "mod";
	}

	public function evaluate($context)
	{
		return Utils::mod($this->obj1->evaluate($context), $this->obj2->evaluate($context));
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.mod", "\com\livinglogic\ul4\Mod");

?>