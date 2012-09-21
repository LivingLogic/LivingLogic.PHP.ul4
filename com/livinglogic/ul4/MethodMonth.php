<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodMonth implements Method
{
	public function getName()
	{
		return "month";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return self::call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "month", count($args), 0);
		}
	}

	public static function call($obj)
	{
		if (!$obj instanceof \DateTime)
			throw new ArgumentTypeMismatchException("{}.month{}", $obj);

		return intval($obj->format("m"));
	}
}

?>