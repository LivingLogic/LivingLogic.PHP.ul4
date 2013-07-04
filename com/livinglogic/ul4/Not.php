<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Not extends Unary
{
	public function __construct($location=null, $start=0, $end=0, $obj=null)
	{
		parent::__construct($location, $start, $end, $obj);
	}

	public function getType()
	{
		return "not";
	}

	public function evaluate($context)
	{
		return !FunctionBool::call($this->obj->evaluate($context));
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.not", "\com\livinglogic\ul4\Not");

?>
