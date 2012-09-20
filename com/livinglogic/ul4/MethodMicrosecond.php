<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodMicrosecond implements Method
{
	public function getName()
	{
		return "microsecond";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return self::call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "microsecond", count($args), 0);
		}
	}

	public static function call($obj)
	{
		if (!$obj instanceof \DateTime)
			throw new \Exception("microsecond(" . self::objectType($obj) . ") not supported!");

		return 0;  // no microseconds available in php
	}
}

?>