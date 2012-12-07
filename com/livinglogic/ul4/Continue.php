<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _Continue extends Tag
{
	public function __construct($location=null)
	{
		parent::__construct($location);
	}

	public function getType()
	{
		return "continue";
	}

	public function evaluate($context)
	{
		throw new ContinueException();
	}

	public function toString($indent)
	{
		$buffer = "";
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "continue\n";
		return $buffer;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.continue", "\com\livinglogic\ul4\_Continue");

?>