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
			$iterator = Utils::iterator($iterable);

			$array = array();
			while ($iterator->valid())
			{
				array_push($array, $iterator->current());
				$iterator->next();
			}
			return join($obj, $array);
		}
		else
			throw new ArgumentTypeMismatchException("{}.join({})", $obj, $iterable);

	}
}

?>