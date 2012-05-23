<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Add extends Binary
{
	public function __construct($location, $obj1, $obj2)
	{
		parent::__construct($location, $obj1, $obj2);
	}

	public function getType()
	{
		return "add";
	}

	/*
	public Object evaluate(EvaluationContext context) throws IOException
	{
		return Utils.add(obj1.decoratedEvaluate(context), obj2.decoratedEvaluate(context));
	}
	*/
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.add", "\com\livinglogic\ul4\Add");

?>