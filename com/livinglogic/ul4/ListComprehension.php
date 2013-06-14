<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ListComprehension extends AST
{
	protected $item;
	protected $varname;
	protected $container;
	protected $condition;

	public function __construct($location=null, $start=0, $end=0, $item=null, $varname=null, $container=null, $condition=null)
	{
		parent::__construct($location, $start, $end);
		$this->item = $item;
		$this->varname = $varname;
		$this->container = $container;
		$this->condition = $condition;
	}

	public function toString($indent)
	{
		$buffer = "";
		$buffer .= "[";
		$buffer .= item.toString(indent);
		$buffer .= " for ";
		Utils::formatVarname($buffer, varname);
		$buffer .= " in ";
		$buffer .= container.toString(indent);
		if (condition != null)
		{
			$buffer .= " if ";
			$buffer .= condition.toString(indent);
		}
		$buffer .= "]";
		return $buffer;
	}

	public function getType()
	{
		return "listcomp";
	}

	public function evaluate($context)
	{
		$result = array();

		$container = $this->container->evaluate($context);

		$iter = Utils::iterator($container);

		while ($iter->valid())
		{
			$variables = &$context->getVariables();
			Utils::unpackVariable($variables, $this->varname, $iter->current());

			if (is_null($this->condition) || Utils::getBool($this->condition->evaluate($context)))
			{
				$item = $this->item->evaluate($context);
				array_push($result, $item);
			}
			$iter->next();
		}
		return $result;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->item);
		$encoder->dump($this->varname);
		$encoder->dump($this->container);
		$encoder->dump($this->condition);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->item = $decoder->load();
		$this->varname = $decoder->load();
		$this->container = $decoder->load();
		$this->condition = $decoder->load();
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.listcomp", "\com\livinglogic\ul4\ListComprehension");

?>
