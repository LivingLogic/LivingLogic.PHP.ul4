<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodSecond implements Method
{
	public function getName()
	{
		return "second";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return self::call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "second", count($args), 0);
		}
	}

	public static function call($obj)
	{
		if (!$obj instanceof \DateTime)
			throw new UnsupportedOperationException(Utils::objectType($obj) + ".second() not supported!");

		return intval($obj->format("s"));
	}
}

?>