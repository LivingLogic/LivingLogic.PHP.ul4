<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class Binary extends AST
{
	protected $obj1;
	protected $obj2;

	public function __construct($location=null, $obj1=null, $obj2=null)
	{
		parent::__construct($location);
		$this->obj1 = $obj1;
		$this->obj2 = $obj2;
	}

	public function toString($indent=0)
	{
		return $this->getType() . "(" . $this->obj1 . ", " . $this->obj2 . ")";
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->obj1);
		$encoder->dump($this->obj2);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->obj1 = $decoder->load();
		$this->obj2 = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("obj1", new ValueMaker(){public Object getValue(Object object){return ((Binary)object).obj1;}});
			v.put("obj2", new ValueMaker(){public Object getValue(Object object){return ((Binary)object).obj2;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

?>