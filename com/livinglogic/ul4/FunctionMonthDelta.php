<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionMonthDelta extends _Function
{
	public function nameUL4()
	{
		return "monthdelta";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"months", 0
		);
	}

	public function evaluate($args)
	{
		return $this->call($args[0]);
	}

	public static function call()
	{
		if (func_num_args() == 0)
			return new MonthDelta();
		else
		{
			$months = func_get_arg(0);
			return new MonthDelta(FunctionInt::call($months));
		}
	}
}

?>