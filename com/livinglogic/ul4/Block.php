<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class Block extends AST
{
	protected $content = array();

	public function __construct($location=null)
	{
		parent::__construct($location);
	}

	public function append($item)
	{
		array_push($this->content, $item);
	}

	public function finish($itemplate, $startLocation, $endLocation)
	{
	}

	abstract public function handleLoopControl($name);

	/*
	public Object evaluate(EvaluationContext context) throws IOException
	{
		for (AST item : content)
			item.decoratedEvaluate(context);
		return null;
	}
	*/

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->content);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->content = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("content", new ValueMaker(){public Object getValue(Object object){return ((InterpretedTemplate)object).content;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

?>