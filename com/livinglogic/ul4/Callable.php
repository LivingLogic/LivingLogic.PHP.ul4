<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class _Callable extends AST
{
	protected static $attributes;

	protected $arguments = array();
	protected $keywordArguments = array();
	protected $remainingArguments = null;
	protected $remainingKeywordArguments = null;

	public static function static_init()
	{
		self::$attributes = array_unique(array_merge(AST::$attributes, array("args", "kwargs", "remargs", "remkwargs")));
	}

	public function __construct($location, $start, $end)
	{
		parent::__construct($location, $start, $end);
	}

	public function append($arg0)
	{
		if (func_num_args() == 1)
			array_push($this->arguments, $arg0);
		else if (func_num_args() == 2)
		{
			$name = $arg0;
			$arg = func_get_arg(1);
			array_push($this->keywordArguments, new KeywordArgument($name, $arg));
		}
		else
			throw new \Exception("_Callable.append called with zero or more than two arguments!");
	}

	public function setRemainingArguments($arguments)
	{
		$this->remainingArguments = $arguments;
	}

	public function setRemainingKeywordArguments($arguments)
	{
		$this->remainingKeywordArguments = $arguments;
	}

	public function getAttributeNamesUL4()
	{
		return self::attributes;
	}

	public function getItemStringUL4($key)
	{
		if ("args" === $key)
			return $this->arguments;
		else if ("kwargs" === $key)
			return $this->keywordArguments;
		else if ("remargs" === $key)
			return $this->remainingArguments;
		else if ("remkwargs" === $key)
			return $this->remainingKeywordArguments;
		else
			return parent::getItemStringUL4($key);
	}
}

_Callable::static_init();

?>