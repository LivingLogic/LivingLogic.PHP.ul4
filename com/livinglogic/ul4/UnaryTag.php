<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class UnaryTag extends AST
{
	var $obj; // AST

	public function ___construct($location=null, $start=0, $end=0, $obj=null)
	{
		parent::__construct($location, $start, $end);
		$this->obj = $obj;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->obj);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->obj = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("obj", new ValueMaker(){public Object getValue(Object object){return ((UnaryTag)object).obj;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

?>