<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodJoin implements Method
{
	public function getName()
	{
		return "join";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 1:
				return self::call($obj, $args[0]);
			default:
				throw new ArgumentCountMismatchException("method", "join", count($args), 1);
		}
	}

	public static function call($obj, $iterable)
	{
		if (is_string($obj))
		{
			if ($iterable instanceof \Iterator)
			{
				$array = array();
				while ($iterable->valid())
				{
					array_push($array, $iterable->current());
					$iterable->next();
				}
				return join($obj, $array);
			}
			return join($obj, str_split($iterable));
		}
		else
			throw new ArgumentTypeMismatchException("{}.join({})", $obj, $iterable);

	}
}

?>