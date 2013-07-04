<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class DictComprehension extends AST
{
	protected $key;
	protected $value;
	protected $varname;
	protected $container;
	protected $condition;

	public function __construct($location=null, $start=0, $end=0, $key=null, $value=null, $varname=null, $container=null, $condition=null)
	{
		parent::__construct($location, $start, $end);
		$this->key = $key;
		$this->value = $value;
		$this->varname = $varname;
		$this->container = $container;
		$this->condition = $condition;
	}

	public function toString($indent)
	{
		$buffer = "";
		$buffer .= "{";
		$buffer .= $this->key->toString($indent);
		$buffer .= ":";
		$buffer .= $this->value->toString($indent);
		$buffer .= " for ";
		Utils::formatVarname($buffer, $this->varname);
		$buffer .= " in ";
		$buffer .= $this->container->toString($indent);
		if ($this->condition != null)
		{
			$buffer .= " if ";
			$buffer .= $this->condition->toString($indent);
		}
		$buffer .= "}";
		return $buffer;
	}

	public function getType()
	{
		return "dictcomp";
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

			if ($this->condition == null || FunctionBool::call($this->condition->evaluate($context)))
			{
				$key = $this->key->evaluate($context);
				$value = $this->value->evaluate($context);
				$result[$key] = $value;
			}

			$iter->next();
		}
		return $result;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->key);
		$encoder->dump($this->value);
		$encoder->dump($this->varname);
		$encoder->dump($this->container);
		$encoder->dump($this->condition);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->key = $decoder->load();
		$this->value = $decoder->load();
		$this->varname = $decoder->load();
		$this->container = $decoder->load();
		$this->condition = $decoder->load();
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.dictcomp", "\com\livinglogic\ul4\DictComprehension");

?>