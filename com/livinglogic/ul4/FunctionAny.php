<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAny implements _Function
{
	public function getName()
	{
		return "any";
	}

	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "any", count($args), 1);
	}

	public static function _call($obj)
	{
		if (is_string($obj))
		{
			for ($i = 0; $i < strlen($obj); ++$i)
			{
				if ($obj[$i] != '\0')
					return true;
			}
			return false;
		}
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			for ($i = 0; $i < count($obj); ++$i)
			{
				if (Utils::getBool($obj[$i]))
					return true;
			}
			return false;
		}
		else if ($obj instanceof \Iterator)
		{
			for (; $obj->valid(); $obj->next())
			{
				if (Utils::getBool($obj->current()))
					return true;
			}
			return false;
		}
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			$ao = new \ArrayObject(array_keys($obj));
			return self::call($ao->getIterator());
		}
		throw new ArgumentTypeMismatchException("any({})", obj);
	}
}

?>