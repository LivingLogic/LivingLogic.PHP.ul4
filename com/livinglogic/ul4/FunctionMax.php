<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionMax implements _Function
{
	public function getName()
	{
		return "max";
	}

	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($args[0]);
		throw new ArgumentCountMismatchException("function", "max", 0, 1, -1);
	}

	public static function _call($objs)
	{
		$iter = Utils::iterator(count($objs) == 1 ? $objs[0] : $objs);

		$maxValue = null;
		$first = true;

		for (;$iter->valid(); $iter->next())
		{
			$testValue = $iter->current();
			if ($first || Utils::gt($testValue, $maxValue))
				$maxValue = $testValue;
			$first = false;
		}
		if ($first)
			throw new Exception("max() arg is an empty sequence!");
		return $maxValue;
	}
}

?>