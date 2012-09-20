<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodHour implements Method
{
	public function getName()
	{
		return "hour";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return self::call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "hour", count($args), 0);
		}
	}

	public static function call($obj)
	{
		if (!$obj instanceof \DateTime)
			throw new ArgumentTypeMismatchException("{}.hour()", $obj);

		return intval($obj->format("H"));
	}
}

?>