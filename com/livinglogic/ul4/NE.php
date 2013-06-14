<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class NE extends Binary
{
	public function __construct($location=null, $start=0, $end=0, $obj1=null, $obj2=null)
	{
		parent::__construct($location, $start, $end, $obj1, $obj2);
	}

	public function getType()
	{
		return "ne";
	}

	public function evaluate($context)
	{
		return Utils::ne($this->obj1->evaluate($context), $this->obj2->evaluate($context));
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.ne", "\com\livinglogic\ul4\NE");

?>
