<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class DelVar extends AST
{
	var $varname;

	public function __construct($varname=null)
	{
		parent::__construct();
		$this->varname = $varname;
	}

	public function toString($indent)
	{
		$buffer = "";

		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "delvar(" . FunctionRepr::call($this->varname) . ")\n";
		return $buffer;
	}

	public function getType()
	{
		return "delvar";
	}

	public function evaluate($context)
	{
		$context->remove($this->varname);
		return null;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->varname);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->varname = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("varname", new ValueMaker(){public Object getValue(Object object){return ((ChangeVar)object).varname;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.delvar", "\com\livinglogic\ul4\DelVar");

?>