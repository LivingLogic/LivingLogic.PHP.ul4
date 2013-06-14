<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class GetAttr extends AST
{
	var $obj;
	var $attrname;

	public function __construct($location=null, $start=0, $end=0, $obj=null, $attrname=null)
	{
		parent::__construct($location, $start, $end);
		$this->obj = $obj;
		$this->attrname = $attrname;
	}

	public function toString($indent)
	{
		return "getattr(" . $this->obj . ", " . Utils::repr($this->attrname) . ")";
	}

	public function getType()
	{
		return "getattr";
	}

	public function evaluate($context)
	{
		return $this->call($this->obj->evaluate($context), $this->attrname);
	}

	public function call($obj, $attrname)
	{
		return Utils::getItem($obj, $attrname);
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->obj);
		$encoder->dump($this->attrname);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->obj = $decoder->load();
		$this->attrname = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("obj", new ValueMaker(){public Object getValue(Object object){return ((GetAttr)object).obj;}});
			v.put("attrname", new ValueMaker(){public Object getValue(Object object){return ((GetAttr)object).attrname;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.getattr", "\com\livinglogic\ul4\GetAttr");

?>
