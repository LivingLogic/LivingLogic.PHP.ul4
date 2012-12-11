<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class FilteredIterator implements \Iterator
{
	protected $nextItem;
	protected $hasNextItem;

	public function __construct()
	{
		$this->fetchNext();
	}

	abstract protected function fetchNext();

	protected function haveNextItem($item)
	{
		$this->hasNextItem = true;
		$this->nextItem = item;
	}

	protected function noNextItem()
	{
		$this->hasNextItem = false;
		$this->nextItem = null;
	}

	public function current()
	{
		return $this->nextItem;
	}

	public function valid()
	{
		return $this->hasNextItem;
	}

	public function next()
	{
		$this->fetchNext();
	}

	public function key()
	{
		throw new \Exception("method 'key' is unsupported for FilteredIterator instances");
	}

	public function rewind()
	{
		throw new \Exception("method 'rewind' is unsupported for FilteredIterator instances");
	}
}

?>