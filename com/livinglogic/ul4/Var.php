<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _Var extends AST
{
	var $name;

	public function __construct($location=null, $start=0, $end=0, $name=null)
	{
		parent::__construct($location, $start, $end);
		$this->name = $name;
	}

	public function toString($indent)
	{
		return $this->name;
	}

	public function getType()
	{
		return "var";
	}

	public function evaluate($context)
	{
		return $context->get($this->name);
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->name);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->name = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("name", new ValueMaker(){public Object getValue(Object object){return ((Var)object).name;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.var", "\com\livinglogic\ul4\_Var");

?>
