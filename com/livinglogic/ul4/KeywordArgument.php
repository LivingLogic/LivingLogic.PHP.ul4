<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class KeywordArgument
{
	protected $name;
	protected $arg;

	public function __construct($name, $arg)
	{
		$this->name = $name;
		$this->arg  = $arg;
	}

	public function toString($indent=0)
	{
		return $this->name . "=" . $this->arg->toString();
	}

	public function getName()
	{
		return $this->name;
	}

	public function getArg()
	{
		return $this->arg;
	}
}

?>