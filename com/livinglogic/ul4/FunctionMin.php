<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionMin implements _Function
{
	public function getName()
	{
		return "min";
	}

	public function call($context, $args)
	{
		if (count($args) > 0)
			return self::_call($args);
		throw new ArgumentCountMismatchException("function", "min", 0, 1, -1);
	}

	public static function _call($objs)
	{
		$iter = Utils::iterator(count($objs) == 1 ? $objs[0] : $objs);

		$minValue = null;
		$first = true;

		for (;$iter->valid(); $iter->next())
		{
			$testValue = $iter->current();
			if ($first || Utils::lt($testValue, $minValue))
				$minValue = $testValue;
			$first = false;
		}
		if ($first)
			throw new \Exception("max() arg is an empty sequence!");
		return $minValue;
	}
}

?>