<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodReplace implements Method
{
	public function getName()
	{
		return "replace";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 2:
				return self::call($obj, $args[0], $args[1]);
			default:
				throw new ArgumentCountMismatchException("method", "replace", count($args), 2);
		}
	}

	public static function call($obj, $search, $replace)
	{
		if (!(is_string($obj) && is_string($search) && is_string($replace)))
			throw new ArgumentTypeMismatchException("{}.replace({}, {})", $obj, $search, $replace);

		$result = str_replace($search, $replace, $obj);

		if (is_array($result))
			$result = implode('', $result);

		return $result;
	}
}

?>