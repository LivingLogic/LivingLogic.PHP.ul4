<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionURLUnquote implements _Function
{
	public function getName()
	{
		return "urlunquote";
	}

	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "urlunquote", count($args), 1);
	}

	public static function _call($obj)
	{
		if (is_string($obj))
		{
			return urldecode($obj);
		}
		throw new ArgumentTypeMismatchException("urlunquote({})", obj);
	}
}

?>