<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionURLQuote implements _Function
{
	public function getName()
	{
		return "urlquote";
	}

	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "urlquote", count($args), 1);
	}

	public static function _call($obj)
	{
		if (is_string($obj))
		{
			return urlencode($obj);
		}
		throw new ArgumentTypeMismatchException("urlquote({})", $obj);
	}
}

?>