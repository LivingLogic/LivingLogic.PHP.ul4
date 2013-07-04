<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _And extends Binary
{
	public function __construct($location=null, $start=0, $end=0, $obj1=null, $obj2=null)
	{
		parent::__construct($location, $start, $end, $obj1, $obj2);
	}

	public function getType()
	{
		return "and";
	}

	public function evaluate($context)
	{
		$obj2ev = $this->obj2->evaluate($context);
		if (FunctionBool::call($obj2ev))
			return $this->obj1->evaluate($context);
		else
			return $obj2ev;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.and", "\com\livinglogic\ul4\_And");

?>