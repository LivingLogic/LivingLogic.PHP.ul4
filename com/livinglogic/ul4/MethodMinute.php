<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodMinute implements Method
{
	public function getName()
	{
		return "minute";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return self::call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "minute", count($args), 0);
		}
	}

	public static function call($obj)
	{
		if (!$obj instanceof \DateTime)
			throw new ArgumentTypeMismatchException("{}.minute()", $obj);

		return intval($obj->format("i"));
	}
}

?>