<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class ConditionalBlock extends Block
{
	public function __construct($location=null, $start=0, $end=0)
	{
		parent::__construct($location, $start, $end);
	}

	abstract public function hasToBeExecuted($context);

	public function handleLoopControl($name)
	{
		return false;
	}
}

?>