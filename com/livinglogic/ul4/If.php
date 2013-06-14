<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _If extends ConditionalBlockWithCondition
{
	public function __construct($location=null, $start=0, $end=0, $condition=null)
	{
		parent::__construct($location, $start, $end, $condition);
	}

	public function getType()
	{
		return "if";
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.if", "\com\livinglogic\ul4\_If");

?>
