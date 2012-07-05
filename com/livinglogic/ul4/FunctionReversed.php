<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionReversed implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "reversed", count($args), 1);
	}

	public function getName()
	{
		return "reversed";
	}

	public static function _call($obj)
	{
		if (is_string($obj))
			return new StringReversedIterator($obj);
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
			return new ListReversedIterator($obj);
		throw new ArgumentTypeMismatchException("reversed({})", $obj);
	}
}

?>