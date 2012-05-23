<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class PPrint extends Unary
{
	public function __construct($location=null, $obj=null)
	{
		parent::__construct($location, $obj);
	}

	public function toString($indent=0)
	{
		return self::line($indent, "print " . $this->obj->toString($indent));
	}

	public function getType()
	{
		return "print";
	}

	/*
	public Object evaluate(EvaluationContext context) throws IOException
	{
		context.write(Utils.str(obj.decoratedEvaluate(context)));
		return null;
	}
	*/

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.print", "\com\livinglogic\ul4\PPrint");

?>