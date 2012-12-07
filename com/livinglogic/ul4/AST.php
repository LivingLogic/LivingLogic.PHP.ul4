<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class AST implements \com\livinglogic\ul4on\UL4ONSerializable
{
	public function __construct()
	{
	}

	abstract public function evaluate($context);

	abstract public function getType();

	public abstract function toString($indent);

	public function __toString()
	{
		return $this->toString(0);
	}

	protected static function line($indent, $line)
	{
		$buffer = "";

		for ($i = 0; $i < $indent; $i++)
			$buffer .= "\t";

		return $buffer . $line . "\n";
	}

	public function getUL4ONName()
	{
		return "de.livinglogic.ul4." . $this->getType();
	}

	public function dumpUL4ON($encoder)
	{
	}

	public function loadUL4ON($decoder)
	{
	}

	/*
	private static $valueMakers = array();

	public function getValueMakers()
	{
		if (self::$valueMakers == null)
		{
			$v = array();
			$v.put("type", new ValueMaker(){
				public Object getValue(Object object){
					return ((AST)object).getType();
				}
			});
			v.put("location", new ValueMaker(){
				public Object getValue(Object object){
					return ((AST)object).getLocation();
				}
			});
			valueMakers = v;
		}
		return valueMakers;
	}
	*/
}

?>