<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _Return extends Unary
{
	public function __construct($location=null, $start=0, $end=0, $obj=null)
	{
		parent::__construct($location, $start, $end, $obj);
	}

	public function toString($formatter)
	{
		$formatter->write("return ");
		parent::toString($formatter);
	}

	public function getType()
	{
		return "return";
	}

	public function evaluate($context)
	{
		throw new ReturnException($this->obj->evaluate($context));
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.return", "\com\livinglogic\ul4\_Return");

?>