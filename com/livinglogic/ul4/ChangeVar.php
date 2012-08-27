<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class ChangeVar extends AST
{
	var $varname;
	var $value;

	public function __construct($location=null, $varname=null, $value=null)
	{
		parent::__construct($location);
		$this->varname = $varname;
		$this->value = $value;
	}

	public function toString($indent)
	{
		$buffer = "";

		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= (getType() . "(" . FunctionRepr::call($this->varname) . ", " . $this->value . ")\n");
		return $buffer;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->varname);
		$encoder->dump($this->value);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->varname = $decoder->load();
		$this->value = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("varname", new ValueMaker(){public Object getValue(Object object){return ((ChangeVar)object).varname;}});
			v.put("value", new ValueMaker(){public Object getValue(Object object){return ((ChangeVar)object).value;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

?>