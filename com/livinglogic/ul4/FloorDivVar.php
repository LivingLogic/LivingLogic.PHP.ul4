<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FloorDivVar extends ChangeVar
{
	public function __construct($location=null, $start=0, $end=0, $varname=null, $value=null)
	{
		parent::__construct($location, $start, $end, $varname, $value);
	}

	public function getType()
	{
		return "floordivvar";
	}

	public function evaluate($context)
	{
		$context->put($this->varname, Utils::floordiv($context->get($this->varname), $this->value->evaluate($context)));
		return null;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.floordivvar", "\com\livinglogic\ul4\FloorDivVar");

?>