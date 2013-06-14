<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class Tag extends AST
{
	protected $location = null;

	public function __construct($location=null)
	{
		parent::__construct();
		$this->location = $location;
	}

	public function getLocation()
	{
		return $this->location;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->location);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->location = $decoder->load();
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
