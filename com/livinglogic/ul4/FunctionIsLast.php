<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';


class SequenceIsLast implements \Iterator
{
	var $sequenceIterator;

	var $current;
	var $key;
	var $valid;
	var $internalNextCalled;

	public function __construct($sequenceIterator)
	{
		$this->sequenceIterator = $sequenceIterator;
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
	}

	public function valid()
	{
		return $this->valid;
	}
}


class FunctionIsLast extends _Function
{
	public function nameUL4()
	{
		return "islast";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("iterable", Signature::$required)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		return new SequenceIsLast(Utils::iterator($obj));
	}

}

?>