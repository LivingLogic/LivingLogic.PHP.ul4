<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class LoadStr extends LoadConst
{
	protected $value;

	public function __construct($location=null, $value=null)
	{
		parent::__construct($location);
		$this->value = $value;
	}

	public function getType()
	{
		return "str";
	}

	public function getValue()
	{
		return $this->value;
	}

	public function toString($indent=0)
	{
		return Utils::repr($this->value);
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->value);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->value = $decoder->load();
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.str", "\com\livinglogic\ul4\LoadStr");

?>