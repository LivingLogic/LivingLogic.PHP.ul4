<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionFromUL4ON implements _Function
{
	public function getName()
	{
		return "fromul4on";
	}

	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "fromul4on", count($args), 1);
	}

	public static function _call($obj)
	{
		if (is_string($obj))
			return \com\livinglogic\ul4on\Utils::loads($obj);
		throw new ArgumentTypeMismatchException("fromul4on({})", $obj);
	}
}

?>