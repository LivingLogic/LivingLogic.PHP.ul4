<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _Const extends AST
{
	var $value;

	function __construct($value=null)
	{
		parent::__construct();
		$this->value = $value;
	}

	function getType()
	{
		return "const";
	}

	function toString($indent)
	{
		return Utils::repr($this->value);
	}

	function evaluate($context)
	{
		return $this->value;
	}

	function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->value);
	}

	function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->value = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("value", new ValueMaker(){public Object getValue(Object object){return ((Const)object).value;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.const", "\com\livinglogic\ul4\_Const");

?>