<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class LT extends Binary
{
	public function __construct($location=null, $obj1=null, $obj2=null)
	{
		parent::__construct($location, $obj1, $obj2);
	}

	public function getType()
	{
		return "lt";
	}

	public function evaluate($context)
	{
		return Utils::lt($this->obj1->evaluate($context), $this->obj2->evaluate($context));
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.lt", "\com\livinglogic\ul4\LT");

?>