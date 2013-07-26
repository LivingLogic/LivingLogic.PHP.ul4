<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class SequenceEnumerator implements \Iterator
{
	var $sequenceIterator;
	var $index;
	var $start;

	public function __construct($sequenceIterator, $start)
	{
		$this->sequenceIterator = $sequenceIterator;
		$this->index = $start;
		$this->start = $start;
	}

	public function rewind()
	{
		$this->sequenceIterator->rewind();
		$this->index = $this->start;
	}

	public function current()
	{
		$retVal = array();
		array_push($retVal, $this->index);
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
		$this->index++;
	}

	public function valid()
	{
		return $this->sequenceIterator->valid();
	}
}


class FunctionEnumerate extends _Function
{
	public function nameUL4()
	{
		return "enumerate";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("iterable", Signature::$required,
			"start", 0)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0], $args[1]);
	}

	public static function call($obj, $start)
	{
		return new SequenceEnumerator(Utils::iterator($obj), Utils::toInt($start));
	}
}

?>