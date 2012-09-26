<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodWithA implements Method
{
	public function getName()
	{
		return "witha";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 1:
				return self::call($obj, $args[0]);
			default:
				throw new ArgumentCountMismatchException("method", "witha", count($args), 1);
		}
	}

	public static function call($obj, $a)
	{
		if (!$obj instanceof Color)
			throw new ArgumentTypeMismatchException("{}.witha({})", $obj, $a);
		return $obj->witha(Utils::toInteger($a));
	}
}

?>