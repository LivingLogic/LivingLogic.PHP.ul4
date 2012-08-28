<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ModVar extends ChangeVar
{
	public function __construct($location=null, $varname=null, $value=null)
	{
		parent::__construct($location, $varname, $value);
	}

	public function getType()
	{
		return "modvar";
	}

	public function evaluate($context)
	{
		$context->put($this->varname, Utils::mod($context->get($this->varname), $this->value->evaluate($context)));
		return null;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.modvar", "\com\livinglogic\ul4\ModVar");

?>