<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class LoadConst extends AST
{
	public function __construct($location=null)
	{
		parent::__construct($location);
	}

	abstract public function getValue();

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("value", new ValueMaker(){public Object getValue(Object object){return ((LoadConst)object).getValue();}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

?>