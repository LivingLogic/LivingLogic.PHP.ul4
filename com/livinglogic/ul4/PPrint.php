<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class PPrint extends Unary
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
		return "print";
	}

	public function evaluate($context)
	{
// 		echo "PPrint.evaluate: this->obj: " . gettype($this->obj) . "\n";
// 		$x = Utils::str($this->obj->evaluate($context));
		$context->write(Utils::str($this->obj->evaluate($context)));
		return null;
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.print", "\com\livinglogic\ul4\PPrint");

?>