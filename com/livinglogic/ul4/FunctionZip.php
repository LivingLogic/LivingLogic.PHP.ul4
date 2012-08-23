<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionZip implements _Function
{
	public function call($context, $args)
	{
		return self::_call($args);
	}

	public function getName()
	{
		return "zip";
	}

	public static function _call($objs)
	{
		return new ZipIterator($objs);
	}
}

class ZipIterator implements \Iterator
{
	var $iterators;
	var $index;

	function __construct($iterators)
	{
		$this->index = 0;
		$this->iterators = array();

		for ($i = 0; $i < count($iterators); ++$i)
			array_push($this->iterators, Utils::iterator($iterators[$i]));
	}

	public function rewind()
	{
		$this->index = 0;
	}

	public function current()
	{
		$retVal = array();

		foreach ($this->iterators as $iterator)
		{
			array_push($retVal, $iterator->current());
		}

		return $retVal;
	}

	public function key()
	{
		return $this->index;
	}

	public function next()
	{
		foreach ($this->iterators as $iterator)
		{
			$iterator->next();
		}

		++$this->index;
	}

	public function valid()
	{
		if (count($this->iterators) == 0)
			return false;

		foreach ($this->iterators as $iterator)
		{
			if (!$iterator->valid())
				return false;
		}

		return true;
	}
}

?>