<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _Or extends Binary
{
	public function __construct($location=null, $obj1=null, $obj2=null)
	{
		parent::__construct($location, $obj1, $obj2);
	}

	public function getType()
	{
		return "or";
	}

	public function evaluate($context)
	{
		$obj1ev = $this->obj1->evaluate($context);
		if (Utils::getBool($obj1ev))
			return $obj1ev;
		else
			return $this->obj2->evaluate($context);
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.or", "\com\livinglogic\ul4\_Or");

?>