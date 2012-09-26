<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodWithLum implements Method
{
	public function getName()
	{
		return "withlum";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 1:
				return self::call($obj, $args[0]);
			default:
				throw new ArgumentCountMismatchException("method", "withlum", count($args), 1);
		}
	}

	public static function call($obj, $lum)
	{
		if (!$obj instanceof Color)
			throw new ArgumentTypeMismatchException("{}.withlum({})", $obj, $lum);
		return $obj->withlum($lum);
	}
}

?>