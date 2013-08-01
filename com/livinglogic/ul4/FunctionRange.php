<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';


class Range implements \Iterator
{
	var $start;
	var $stop;
	var $step;
	var $index;

	public function __construct($start, $stop, $step)
	{
		$this->start = $start;
		$this->stop = $stop;
		$this->step = $step;
		$this->index = 0;
	}

	public function rewind()
	{
		$this->index = 0;
	}

	public function current()
	{
		return $this->start + $this->index * $this->step;
	}

	public function key()
	{
		return $this->index;
	}

	public function next()
	{
		++$this->index;
	}

	public function valid()
	{
		return $this->step > 0 ? $this->current() < $this->stop : $this->current() > $this->stop;
	}
}


class FunctionRange implements UL4Call
{
	public function getName()
	{
		return "range";
	}

	public function callUL4($args, $kwargs)
	{
		if (count($kwargs) != 0)
			throw new KeywordArgumentsNotSupportedException($this->getName());

		$start = 0;
		$stop = $obj;
		$step = 1;

		if (func_num_args() == 1)
			;
		else if (func_num_args() == 2)
		{
			$start = $obj;
			$stop  = func_get_arg(1);
		}
		else if (func_num_args() == 3)
		{
			$start = $obj;
			$stop  = func_get_arg(1);
			$step  = func_get_arg(2);
		}
		else
			throw new ArgumentCountMismatchException("function", "range", count($args), 1, 3);

		if ($step == 0)
			throw new \Exception("Step argument must be non-zero!");

		return new Range(Utils::_toInt($start), Utils::_toInt($stop), Utils::_toInt($step));
	}
}

?>