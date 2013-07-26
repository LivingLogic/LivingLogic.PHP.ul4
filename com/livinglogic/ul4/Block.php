<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class Block extends AST
{
	protected $endlocation;
	protected $content = array();

	public function __construct($location=null, $start=0, $end=0)
	{
		parent::__construct($location, $start, $end);
	}

	public function append($item)
	{
		array_push($this->content, $item);
	}

	public function finish($endlocation)
	{
		$this->endlocation = $endlocation;
	}

	public function evaluate($context)
	{
		foreach ($this->content as $item)
		{
			if (!is_object($item))
				print "Block.evaluate: item is " . gettype($item) . "\n";
			$item->evaluate($context);
		}
		return null;
	}

	abstract public function handleLoopControl($name);

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->endlocation);
		$encoder->dump($this->content);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->endlocation = $decoder->load();
		$this->content = $decoder->load();
	}

	protected static $attributes = null;

	public static function static_init()
	{
		$a = array_merge(AST::$attributes, array("endlocation", "content"));
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
		if ("endlocation" == $key)
			return $this->endlocation;
		else if ("content" == $key)
			return $this->content;
		else
			return parent::getItemStringUL4($key);
	}
}

Block::static_init();

?>