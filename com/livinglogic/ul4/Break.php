<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _Break extends AST
{
	public function __construct($location=null, $start=0, $end=0)
	{
		parent::__construct($location, $start, $end);
	}

	public function getType()
	{
		return "break";
	}

	public function evaluate($context)
	{
		throw new BreakException();
	}

	public function toString($indent)
	{
		$buffer = "";
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "break\n";
		return $buffer;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.break", "\com\livinglogic\ul4\_Break");

?>