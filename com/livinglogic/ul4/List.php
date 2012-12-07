<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class List_ extends AST
{
	protected $items = array();

	public function __construct()
	{
		parent::__construct();
	}

	public function toString($indent)
	{
		$buffer = "[";

		$first = true;
		foreach ($this->items as $item)
		{
			if ($first)
				$first = false;
			else
				$buffer .= ", ";
			$buffer .= $item->toString($indent);
		}
		$buffer .= "]";
		return $buffer;
	}

	public function getType()
	{
		return "list";
	}

	public function evaluate($context)
	{
		$result = array();

		foreach ($this->items as $item)
			array_push($result, $item->evaluate($context));
		return $result;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder.dump($this->items);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->items = $decoder->load();
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.list", "\com\livinglogic\ul4\List_");

?>