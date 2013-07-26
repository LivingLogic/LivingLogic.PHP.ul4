<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionMax extends _Function
{
	public function nameUL4()
	{
		return "max";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("args", Signature::$remainingArguments)
		);
	}

	public function evaluate($args)
	{
		if (count($args) == 0)
			self::call();
		else
			self::call($objs);
	}

	public static function call()
	{
		$objs = null;

		if (func_num_args() == 0)
			throw new MissingArgumentException("max", "args", 0);
		else
			$objs = func_get_arg(0);

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
			throw new \Exception("max() arg is an empty sequence!");

		return $maxValue;
	}
}

?>