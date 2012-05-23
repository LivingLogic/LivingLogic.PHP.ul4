<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class LoadVar extends AST
{
	protected $varname;

	public function __construct($location=null, $varname=null)
	{
		parent::__construct($location);
		$this->varname = $varname;
	}

	public function toString($indent=0)
	{
		return $this->varname;
	}

	public function getType()
	{
		return "var";
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

	public function evaluate($context)
	{
		return $context->get($this->varname);
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("obj", new ValueMaker(){public Object getValue(Object object){return ((Unary)object).obj;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.var", "\com\livinglogic\ul4\LoadVar");

?>