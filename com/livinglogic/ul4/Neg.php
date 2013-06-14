<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Neg extends Unary
{
	public function __construct($location=null, $start=0, $end=0, $obj=null)
	{
		parent::__construct($location, $start, $end);
	}

	public function getType()
	{
		return "neg";
	}

	public function evaluate($context)
	{
		return Utils::neg($this->obj->evaluate($context));
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.neg", "\com\livinglogic\ul4\Neg");

?>
