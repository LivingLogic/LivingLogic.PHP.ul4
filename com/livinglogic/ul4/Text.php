<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Text extends AST
{
	public function __construct($location=null, $start=0, $end=0)
	{
		parent::__construct($location, $start, $end);
	}

	public function toString($indent=0)
	{
		return self::line($indent, "text " . Utils::repr($this->location->getCode()));
	}

	public function getType()
	{
		return "text";
	}

	public function evaluate($context)
	{
		$context->write($this->location->getCode());
		return null;
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.text", "\com\livinglogic\ul4\Text");

?>
