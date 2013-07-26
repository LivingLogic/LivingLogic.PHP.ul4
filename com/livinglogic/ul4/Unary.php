<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class Unary extends AST
{
	protected $obj;

	public function __construct($location=null, $start=0, $end=0, $obj=null)
	{
		parent::__construct($location, $start, $end);
		$this->obj = $obj;
	}

	public function toString($indent=0)
	{
		return $this->getType() . "(" . $this->obj . ")";
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->obj);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->obj = $decoder->load();
	}

	protected static $attributes;

	public static function static_init()
	{
		$a = array_merge(AST::$attributes, array("obj"));
		$a = array_unique($a);
		$a = array_merge($a);

		self::$attributes = $a;
	}

	public function getAttributeNamesUL4()
	{
		return self::$attributes;
	}

	public function getItemStringUL4($key)
	{
		if ("obj" == $key)
			return $this->obj;
		else
			return parent::getItemStringUL4($key);
	}
}

Unary::static_init();

?>