<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodHLSA implements Method
{
	public function getName()
	{
		return "hlsa";
	}

	public function evaluate($context, $obj, $args)
	{
		if (count($args) == 0)
			return self::call($obj);
		throw new ArgumentCountMismatchException("method", "hlsa", count($args), 0);
	}

	public static function call($obj)
	{
		if (!$obj instanceof Color)
			throw new ArgumentTypeMismatchException("{}.hlsa()", $obj);

		return $obj->hlsa();
	}
}

?>