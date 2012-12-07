<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Not extends Unary
{
	public function __construct($obj=null)
	{
		parent::__construct($obj);
	}

	public function getType()
	{
		return "not";
	}

	public function evaluate($context)
	{
		return !Utils::getBool($this->obj->evaluate($context));
	}

}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.not", "\com\livinglogic\ul4\Not");

?>