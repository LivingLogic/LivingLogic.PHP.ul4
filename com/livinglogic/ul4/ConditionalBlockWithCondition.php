<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class ConditionalBlockWithCondition extends ConditionalBlock
{
	protected $condition;

	public function __construct($location, $start=0, $end=0, $condition)
	{
		parent::__construct($location, $start, $end);
		$this->condition = $condition;
	}

	public function hasToBeExecuted($context)
	{
		return FunctionBool::call($this->condition->evaluate($context));
	}

	public function toString($indent)
	{
		$buffer = "";

		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= getType();
		$buffer .= " (";
		$buffer .= condition.toString(indent);
		$buffer .= ")\n";
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "{\n";
		foreach ($this->content as $item)
			$buffer .= $item->toString($indent+1);
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .= "}\n";
		return $buffer;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->condition);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->condition = $decoder->load();
	}

	/*
	private static Map<String, ValueMaker> valueMakers = null;

	public Map<String, ValueMaker> getValueMakers()
	{
		if (valueMakers == null)
		{
			HashMap<String, ValueMaker> v = new HashMap<String, ValueMaker>(super.getValueMakers());
			v.put("condition", new ValueMaker(){public Object getValue(Object object){return ((ConditionalBlockWithCondition)object).condition;}});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

?>