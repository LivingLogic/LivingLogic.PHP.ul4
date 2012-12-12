<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionFromJSON implements _Function
{
	public function getName()
	{
		return "fromjson";
	}

	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "fromjson", count($args), 1);
	}

	static function _call($obj)
	{
		if (is_string($obj))
		{
			$result = json_decode($obj, true);
			return $result;
		}
		throw new ArgumentTypeMismatchException("fromjson({})", $obj);
	}
}

?>