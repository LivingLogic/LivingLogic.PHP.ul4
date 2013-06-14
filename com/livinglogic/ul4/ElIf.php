<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ElIf extends ConditionalBlockWithCondition
{
	public function __construct($location=null, $start=0, $end=0, $condition=null)
	{
		parent::__construct($location, $start, $end, $condition);
	}

	public function getType()
	{
		return "elif";
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.elif", "\com\livinglogic\ul4\ElIf");

?>