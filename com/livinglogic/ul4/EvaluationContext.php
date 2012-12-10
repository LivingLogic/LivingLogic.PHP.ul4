<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class EvaluationContext
{
	protected $buffer;
	protected $variables;

	public function __construct($variables=null)
	{
		$this->buffer = "";
		if ($variables == null)
			$variables = array();
		$this->variables = $variables;
	}

	public function &getVariables()
	{
		return $this->variables;
	}

	public function getOutput()
	{
		return $this->buffer;
	}

	public function write($string)
	{
		$this->buffer .= $string;
	}

	public function put($key, $value)
	{
		$this->variables[$key] = $value;
	}

	public function get($key)
	{
		return Utils::getItem($this->variables, $key);
	}

	public function remove($key)
	{
		unset($this->variables[$key]);
	}

	/*
	public Object push(Object object)
	{
		stack.push(object);
		return object;
	}

	public Object pop()
	{
		return stack.pop();
	}

	public Object pop(Object object)
	{
		stack.pop();
		return object;
	}
	*/
}

?>