<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class GeneratorExpression extends AST implements \Iterator
{
	protected $item;
	protected $varname;
	protected $container;
	protected $condition;

	private $context;
	private $iterator;
	private $valid;

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
		$buffer .= "(";
		$buffer .= $this->item->toString($indent);
		$buffer .= " for ";
		Utils::formatVarname($buffer, $this->varname);
		$buffer .= " in ";
		$buffer .= $this->container->toString($indent);
		if (!is_null($this->condition))
		{
			$buffer .= " if ";
			$buffer .= $this->condition->toString($indent);
		}
		$buffer .= ")";
		return $buffer;
	}

	public function getType()
	{
		return "genexpr";
	}

	public function evaluate($context)
	{
		$this->context = $context;
		$this->iterator = Utils::iterator($this->container->evaluate($context));
		$this->fetchNextItem();
		return $this;
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

	private function fetchNextItem()
	{
		while ($this->iterator->valid())
		{
			$variables = &$this->context->getVariables();
			Utils::unpackVariable($variables, $this->varname, $this->iterator->current());
			$useIt = false;
			if (is_null($this->condition))
				$useIt = true;
			else
			{
				$useIt = Utils::getBool($this->condition->evaluate($this->context));
			}

			$this->iterator->next();

			if ($useIt)
			{
				$this->valid = true;
				return;
			}
		}
		$this->valid = false;
	}

	public function rewind()
	{
		if (!is_null($this->iterator))
			$this->iterator->rewind();
	}

	public function current()
	{
		$result = $this->item->evaluate($this->context);
		return $result;
	}

	public function key()
	{
		throw new \Exception("method 'key()' not applicable for class GeneratorExpression");
	}

	public function next()
	{
		$this->fetchNextItem();
	}

	public function valid()
	{
		return $this->valid;
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.genexpr", "\com\livinglogic\ul4\GeneratorExpression");

?>
