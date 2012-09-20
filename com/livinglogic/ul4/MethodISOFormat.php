<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodISOFormat implements Method
{
	public function getName()
	{
		return "isoformat";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return $this->call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "isoformat", count($args), 0);
		}
	}

	private static $isoDateFormat = "Y-m-d";
	private static $isoDateTime2Format = "Y-m-d'T'H:i:s";
	private static $isoTimestampMicroFormat = "Y-m-d'T'H:i:s000000";

	public function call($obj)
	{
		if (!$obj instanceof \DateTime)
			throw new ArgumentTypeMismatchException("{}.isoformat()", $obj);

		if (MethodMicrosecond::call($obj) != 0)
			return $obj->format(self::$isoTimestampMicroFormat);
		else
		{
			if (MethodHour::call($obj) != 0 || MethodMinute::call($obj) != 0 || MethodSecond::call($obj) != 0)
				return $obj->format(self::$isoDateTime2Format);
			else
				return $obj->format(self::$isoDateFormat);
		}
	}
}

?>