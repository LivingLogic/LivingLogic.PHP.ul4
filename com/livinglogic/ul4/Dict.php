<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class Dict extends AST
{
	var $items = array();

	public function __construct()
	{
		parent::__construct();
	}

	public function append($arg1)
	{
		if (func_num_args() == 2)
		{
			$key = $arg1;
			$value = func_get_arg(1);
			array_push($this->items, new DictItemKeyValue($key, $value));
		}
		elseif (func_num_args() == 1 && $arg1 instanceof AST)
		{
			array_push($this->items, new DictItemDict($arg1));
		}
		elseif (func_num_args() == 1 && $arg1 instanceof DictItem)
		{
			array_push($this->items, $arg1);
		}
	}

	public function toString($indent)
	{
		$buffer = "";
		$buffer .= "{";

		$first = true;
		foreach ($this->items as $item)
		{
			if ($first)
				$first = false;
			else
				$buffer .= ", ";
			$buffer .= $item;
		}
		$buffer .= "}";
		return $buffer;
	}

	public function getType()
	{
		return "dict";
	}

	public function evaluate($context)
	{
		$result = array();

		foreach ($this->items as $item)
			$item->addTo($context, $result);
		return $result;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$itemList = array();
		foreach ($this->items as $item)
			array_push($itemList, $item->object4UL4ON());
		$encoder->dump($itemList);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$itemList = $decoder->load();
		$this->items = array();
		foreach ($itemList as $item)
		{
			if (count($item) == 2)
				array_push($this->items, new DictItemKeyValue($item[0], $item[1]));
			else
				array_push($this->items, new DictItemDict($item[0]));
		}
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.dict", "\com\livinglogic\ul4\Dict");

?>