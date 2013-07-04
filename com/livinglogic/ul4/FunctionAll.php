<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAll implements _Function
{
	public function getName()
	{
		return "all";
	}

	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "all", count($args), 1);
	}

	public static function _call($obj)
	{
		if (is_string($obj))
		{
			for ($i = 0; $i < strlen($obj); ++$i)
			{
				if ($obj[$i] == '\0')
					return false;
			}
			return true;
		}
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			for ($i = 0; $i < count($obj); ++$i)
			{
				if (!FunctionBool::call($obj[$i]))
					return false;
			}
			return true;
		}
		else if ($obj instanceof \Iterator)
		{
			for (; $obj->valid(); $obj->next())
			{
				if (!FunctionBool::call($obj->current()))
					return false;
			}
			return true;
		}
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			$ao = new \ArrayObject(array_keys($obj));
			return self::call($ao->getIterator());
		}
		throw new ArgumentTypeMismatchException("all({})", obj);
	}
}

?>