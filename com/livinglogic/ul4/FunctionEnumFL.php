<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';


class SequenceEnumFL implements \Iterator
{
	var $sequenceIterator;

	var $index;
	var $start;
	var $current;
	var $key;
	var $valid;
	var $internalNextCalled;

	public function __construct($sequenceIterator, $start)
	{
		$this->sequenceIterator = $sequenceIterator;
		$this->index = $start;
		$this->start = $start;
		$this->valid = $this->sequenceIterator->valid();
		$this->internalNextCalled = false;

		if ($this->valid)
		{
			$this->current = $this->sequenceIterator->current();
			$this->key = $this->sequenceIterator->key();
		}
	}

	public function rewind()
	{
		// unused
	}

	public function current()
	{
		$retVal = array();
		array_push($retVal, $this->index);
		array_push($retVal, $this->index === $this->start);

		if (!$this->internalNextCalled)
		{
			$this->sequenceIterator->next();
			$this->internalNextCalled = true;
		}

		array_push($retVal, !$this->sequenceIterator->valid());

		array_push($retVal, $this->current);
		return $retVal;
	}

	public function key()
	{
		return $this->key;
	}

	public function next()
	{
		if (!$this->internalNextCalled)
			$this->sequenceIterator->next();

		$this->internalNextCalled = false;

		if ($this->sequenceIterator->valid())
		{
			$this->current = $this->sequenceIterator->current();
			$this->key = $this->sequenceIterator->key();
		}
		$this->valid = $this->sequenceIterator->valid();

		$this->index++;
	}

	public function valid()
	{
		return $this->valid;
	}
}


class FunctionEnumFL extends _Function
{
	public function nameUL4()
	{
		return "enumfl";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"iterable", Signature::$required,
			"start", 0
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0], $args[1]);
	}

	public static function call($obj, $start)
	{
		return new SequenceEnumFL(Utils::iterator($obj), Utils::toInt($start));
	}
}

?>