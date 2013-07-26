<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MonthDelta implements UL4Bool, UL4Repr, UL4Type, UL4Abs, UL4MethodCall
{
	private $months;

	private $signatureMonths;

	public function __construct($months=0)
	{
		$this->months = $months;

		$this->signatureMonths = new Signature("months");
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

	public function boolUL4()
	{
		return $this->months != 0;
	}

	public function reprUL4()
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

	public function typeUL4()
	{
		return "monthdelta";
	}

	public function absUL4()
	{
		return $this->months < 0 ? new MonthDelta(-$this->months) : $this;
	}

	public function callMethodUL4($methodName, $args, $kwargs)
	{
		if ("months" == $methodName)
		{
			$args = $this->signatureMonths->makeArgumentArray($args, $kwargs);
			return $this->months;
		}
		else
			throw new UnknownMethodException($methodName);
	}
}

?>