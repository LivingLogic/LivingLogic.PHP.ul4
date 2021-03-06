<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class AST implements \com\livinglogic\ul4on\UL4ONSerializable
{
	protected $location = null;
	protected $start;
	protected $end;

	public function __construct($location=null, $start=0, $end=0)
	{
		$this->location = $location;
		$this->start = $start;
		$this->end = $end;
	}

	abstract public function evaluate($context);

	abstract public function getType();

	/**
	 * Format this object using a Formatter object.
	 * @param formatter the Fomatter object
	 */
	public function toStringFormatter($formatter)
	{
		$this->toStringFromSource($formatter);
	}

	public function toStringFromSource($formatter)
	{
		$formatter->write(mb_substr($this->location->getSource(), $this->start, $this->end - $this->start, \com\livinglogic\ul4on\Utils::$encoding));
	}

	public function toString($indent)
	{
		$formatter = new Formatter();
		for ($i=0; i<$indent; $i++)
			$formatter->indent();
		$this->toStringFormatter(formatter);
		formatter.finish();
		return formatter.toString();
	}

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
		$encoder->dump($this->location);
		$encoder->dump($this->start);
		$encoder->dump($this->end);
	}

	public function loadUL4ON($decoder)
	{
		$this->location = $decoder->load();
		$this->start = $decoder->load();
		$this->end = $decoder->load();
	}

	protected static $attributes = array("type", "location", "start", "end");

	public function getAttributeNamesUL4()
	{
		return self::attributes;
	}

	public function getItemStringUL4($key)
	{
		if ("type" == $key)
			return $this->getType();
		else if ("location" == $key)
			return $this->location;
		else if ("start" == $key)
			return $this->start;
		else if ("end" == $key)
			return $this->end;
		else
			return new UndefinedKey($key);
	}
}

?>