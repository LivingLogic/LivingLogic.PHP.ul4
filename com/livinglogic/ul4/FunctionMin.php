<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionMin extends _Function
{
	public function nameUL4()
	{
		return "min";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"args", Signature::$remainingArguments
		);
	}

	public function evaluate($args)
	{
		if (count($args) == 0)
			self::call();
		else
			self::call($args);
	}

	public static function call()
	{
		$objs = null;

		if (func_num_args() == 0)
			throw new MissingArgumentException("min", "args", 0);
		else
			$objs = func_get_arg(0);

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