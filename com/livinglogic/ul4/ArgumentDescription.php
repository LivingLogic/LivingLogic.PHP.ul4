<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ArgumentDescription
{
	protected $name;
	protected $position;
	protected $hasDefaultValue;
	protected $defaultValue;

	public function __construct($name, $position, $defaultValue=null)
	{

		$this->name = $name;
		$this->position = $position;

		if (func_num_args() == 2)
			$this->hasDefaultValue = false;
		else
			$this->hasDefaultValue = true;

		$this->defaultValue = $defaultValue;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getPosition()
	{
		return $this->position;
	}

	public function hasDefaultValue()
	{
		return $this->hasDefaultValue;
	}

	public function getDefaultValue()
	{
		return $this->defaultValue;
	}
}

?>
