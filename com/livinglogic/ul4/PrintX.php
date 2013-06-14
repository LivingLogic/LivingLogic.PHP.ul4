<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class PrintX extends Unary
{
	public function __construct($location=null, $start=0, $end=0, $obj=null)
	{
		parent::__construct($location, $start, $end, $obj);
	}

	public function toString($indent=0)
	{
		return self::line($indent, "print " . $this->obj->toString($indent));
	}

	public function getType()
	{
		return "printx";
	}

	public function evaluate($context)
	{
		$context->write(Utils::xmlescape($this->obj->evaluate($context)));
		return null;
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.printx", "\com\livinglogic\ul4\PrintX");

?>