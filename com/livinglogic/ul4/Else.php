<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _Else extends ConditionalBlock
{
	public function __construct($location=null)
	{
		parent::__construct($location, $start, $end, $start=0, $end=0);
	}

	public function getType()
	{
		return "else";
	}

	public function hasToBeExecuted($context)
	{
		return true;
	}

	public function toString($indent)
	{
		$buffer = "";

		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "else\n";
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "{\n";
		foreach ($this->content as $item)
			$buffer .= $item.toString($indent+1);
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "}\n";
		return $buffer;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.else", "\com\livinglogic\ul4\_Else");

?>