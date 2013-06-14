<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class StoreVar extends ChangeVar
{
	public function __construct($location=null, $start=0, $end=0, $varname=null, $value=null)
	{
		parent::__construct($location, $start, $end, $varname, $value);
	}

	public function getType()
	{
		return "storevar";
	}

	public function evaluate($context)
	{
// 		if (is_null($this->value))
// 			print "StoreVar: this->value is null\n";
// 		else
// 		{
// 			print "StoreVar: this->value is not null: " . gettype($this->value) . ",  this->varname = " . $this->varname . "\n";
// 			if (gettype($this->value) == "string")
// 				print "StoreVar: this->value = " . $this->value . "\n";
// 		}

// 		$context->put($this->varname, $this->value->evaluate($context));
		$variables = &$context->getVariables();
		Utils::unpackVariable($variables, $this->varname, $this->value->evaluate($context));
		return null;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.storevar", "\com\livinglogic\ul4\StoreVar");

?>