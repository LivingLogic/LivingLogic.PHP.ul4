<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAbs implements _Function
{
	/*
	public function call($context, $args)
	{
		if (count($args) == 1)
			return Utils::abs($args[0]);
		throw new ArgumentCountMismatchException("function", "abs", count($args), 1);
	}

	public function getName()
	{
		return "abs";
	}
	*/

	public function nameUL4()
	{
		return "abs";
	}

	protected function makeSignature()
	{
		return new Signature(
				$this->nameUL4(),
				"number", Signature::required
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}
}

?>