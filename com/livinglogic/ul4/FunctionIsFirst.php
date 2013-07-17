<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';


class SequenceIsFirst implements \Iterator
{
	var $sequenceIterator;

	var $first = true;

	public function __construct($sequenceIterator)
	{
		$this->sequenceIterator = $sequenceIterator;
	}

	public function rewind()
	{
		// unused
	}

	public function current()
	{
		$retVal = array();
		array_push($retVal, $this->first);
		array_push($retVal, $this->sequenceIterator->current());
		return $retVal;
	}

	public function key()
	{
		return $this->sequenceIterator->key();
	}

	public function next()
	{
		$this->sequenceIterator->next();
		$this->first = false;
	}

	public function valid()
	{
		return $this->sequenceIterator->valid();
	}
}


class FunctionIsFirst extends _Function
{
	public function nameUL4()
	{
		return "isfirst";
	}

	protected function makeSignature()
	{
		return new Signature(
				$this->nameUL4(),
				"iterable", Signature::$required
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		return new SequenceIsFirst(Utils::iterator($obj));
	}
}

?>