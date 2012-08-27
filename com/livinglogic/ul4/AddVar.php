<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class AddVar extends ChangeVar
{
	public function __construct($location=null, $varname=null, $value=null)
	{
		parent::__construct($location, $varname, $value);
	}

	public function getType()
	{
		return "addvar";
	}

	public function evaluate($context)
	{
		$context->put($this->varname, Utils::add($context->get($this->varname), $this->value->evaluate($context)));
		return null;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.addvar", "\com\livinglogic\ul4\AddVar");

?>