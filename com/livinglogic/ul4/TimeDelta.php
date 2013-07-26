<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class TimeDelta implements UL4Bool, UL4Repr, UL4Type, UL4Abs, UL4MethodCall
{
	private $days;
	private $seconds;
	private $microseconds;

	private $signatureDays;
	private $signatureSeconds;
	private $signatureMicroseconds;

	public function __construct($days=0, $seconds=0, $microseconds=0)
	{
		if (is_float($days) || is_double($days) || is_float($seconds) || is_double($seconds) || is_float($microseconds) || is_double($microseconds))
		{
			$microseconds = FunctionInt::call(($days%(1./(24*60*60)))*24*60*60*1000000+($seconds%1.0)*1000000+$microseconds);
			$seconds = FunctionInt::call(($days % 1.0)*24*60*60+seconds);
			$days = FunctionInt::call($days);
		}

		$microseconds_div = Utils::floordiv($microseconds, 1000000);
		$microseconds = $microseconds % 1000000;

		if ($microseconds < 0)
		{
			$microseconds += 1000000;
			--$microseconds_div;
		}
		$seconds += $microseconds_div;
		$this->microseconds = FunctionInt::call($microseconds);

		$seconds_div = Utils::floordiv($seconds, 24*60*60);
		$seconds = $seconds % (24*60*60);

		if ($seconds < 0)
		{
			$seconds += (24*60*60);
			--$seconds_div;
		}
		$days += $seconds_div;
		$this->seconds = FunctionInt::call($seconds);

		$this->days = $days;

		$this->signatureDays = new Signature("days");
		$this->signatureSeconds = new Signature("seconds");
		$this->signatureMicroseconds = new Signature("microseconds");
	}

	public function getDays()
	{
		return $this->days;
	}

	public function getSeconds()
	{
		return $this->seconds;
	}

	public function getMicroseconds()
	{
		return $this->microseconds;
	}


	public function add($other)
	{
		return new TimeDelta(
			$this->days + $other->getDays(),
			$this->seconds + $other->getSeconds(),
			$this->microseconds + $other->getMicroseconds()
		);
	}

	public function addTo($dateTime)
	{
		$distr = "P" . $this->days . "D";
		if (!is_null($this->seconds) && $this->seconds != 0)
			$distr .= "T" . $this->seconds . "S";
		$dateInterval = new \DateInterval($distr);
		$dateTime->add($dateInterval);
		return $dateTime;
	}

	public function subtract($other)
	{
		return new TimeDelta(
			$this->days - $other->getDays(),
			$this->seconds - $other->getSeconds(),
			$this->microseconds - $other->getMicroseconds()
		);
	}

	public function subtractFrom($dateTime)
	{
		$distr = "P" . $this->days . "D";
		if (!is_null($this->seconds) && $this->seconds != 0)
			$distr .= "T" . $this->seconds . "S";
		$dateInterval = new \DateInterval($distr);
		$dateTime->sub($dateInterval);
		return $dateTime;
	}

	public function negate()
	{
		return new TimeDelta(-$this->days, -$this->seconds, -$this->microseconds);
	}

	public function mul($factor)
	{
		return new TimeDelta(
			$factor * $this->days,
			$factor * $this->seconds,
			$factor * $this->microseconds
		);
	}

	public function truediv($divisor)
	{
		return new TimeDelta(
			$this->days / $divisor,
			$this->seconds / $divisor,
			$this->microseconds / $divisor
		);
	}

	public function floordiv($divisor)
	{
		return new TimeDelta(
			Utils::floordiv($days, $divisor),
			Utils::floordiv($seconds, $divisor),
			Utils::floordiv($microseconds, $divisor)
		);
	}

	private static $twodigits = "%02d";
	private static $sixdigits = "%06d";

	public function toString()
	{
		$buffer = "";

		if ($this->days != 0)
		{
			$buffer .= $this->days;
			$buffer .= " day";
			if (($this->days != 1) && ($this->days != -1))
				$buffer .= "s";
			$buffer .= ", ";
		}
		$ss = $this->seconds%60;
		$mm = $this->seconds/60;
		$hh = $mm/60;
		$mm = $mm%60;

		$buffer .= $hh;
		$buffer .= ":";
		$buffer .= sprintf(self::$twodigits, $mm);
		$buffer .= ":";
		$buffer .= sprintf(self::$twodigits, $ss);

		if ($this->microseconds != 0)
		{
			$buffer .= ".";
			$buffer .= sprintf(self::$sixdigits, $this->microseconds);
		}
		return $buffer;
	}

	public function boolUL4()
	{
		return $this->days != 0 || $this->seconds != 0 || $this->microseconds != 0;
	}

	public function reprUL4()
	{
		$buffer = "";

		$buffer .= "timedelta(";
		if ($this->days != 0 || $this->seconds != 0 || $this->microseconds != 0)
		{
			$buffer .= $this->days;
			if ($this->seconds != 0 || $this->microseconds != 0)
			{
				$buffer .= ", ";
				$buffer .= $this->seconds;
				if ($this->microseconds != 0)
				{
					$buffer .= ", ";
					$buffer .= $this->microseconds;
				}
			}
		}
		$buffer .= ")";
		return $buffer;
	}

	public function typeUL4()
	{
		return "timedelta";
	}

	public function absUL4()
	{
		return $this->days < 0 ? new TimeDelta(-$this->days, -$this->seconds, -$this->microseconds) : $this;
	}

	public function callMethodUL4($methodName, $args, $kwargs)
	{
		if ("days" == $methodName)
		{
			$args = $this->signatureDays->makeArgumentArray($args, $kwargs);
			return $this->days;
		}
		else if ("seconds" == $methodName)
		{
			$args = $this->signatureSeconds->makeArgumentArray($args, $kwargs);
			return $this->seconds;
		}
		else if ("microseconds" == $methodName)
		{
			$args = $this->signatureSeconds->makeArgumentArray($args, $kwargs);
			return $this->microseconds;
		}
		else
			throw new UnknownMethodException($methodName);
	}
}

?>