<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class Block extends AST
{
	protected $endlocation;
	protected $content = array();

	public function __construct($location=null, $endlocation=null)
	{
		parent::__construct($location);
		$this->endlocation = $endlocation;
	}

	public function append($item)
	{
		array_push($this->content, $item);
	}

	public function finish($itemplate, $startLocation, $endLocation)
	{
	}

	public function evaluate($context)
	{
		foreach ($this->content as $item)
			$item->evaluate($context);
		return null;
	}

	abstract public function handleLoopControl($name);

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->endlocation);
		$encoder->dump($this->content);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->endlocation = $decoder->load();
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