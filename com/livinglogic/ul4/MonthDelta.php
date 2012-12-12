<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MonthDelta
{
	private $months;

	public function __construct($months=0)
	{
		$this->months = $months;
	}

	public function getMonths()
	{
		return $this->months;
	}

	public function add($other)
	{
		return new MonthDelta($this->months + $other->getMonths());
	}

	public function addTo($dateTime)
	{
		$dateInterval = new \DateInterval("P" . $this->months . "M");
		$dateTime->add($dateInterval);
		return $dateTime;
	}

	public function subtract($other)
	{
		return new MonthDelta($this->months - $other->getMonths());
	}

	public function subtractFrom($dateTime)
	{
		$dateInterval = new \DateInterval("P" . $this->months . "M");
		$dateTime->sub($dateInterval);
		return $dateTime;
	}

	public function negate()
	{
		return new MonthDelta(-$this->months);
	}

	public function mul($factor)
	{
		return new MonthDelta($factor * $this->months);
	}

	public function floordiv($divisor)
	{
		return new MonthDelta(Utils::floordiv($this->months, $divisor));
	}

	public function repr()
	{
		$buffer = "";

		$buffer .= "monthdelta(";
		if ($this->months != 0)
			$buffer .= $this->months;
		$buffer .= ")";
		return $buffer;
	}

	public function toString()
	{
		$buffer = "";

		$buffer .= $this->months;
		$buffer .= " month";
		if (($this->months != 1) && ($this->months != -1))
			$buffer .= "s";
		return $buffer;
	}
}

?>