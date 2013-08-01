<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';


class FunctionPrint extends FunctionWithContext
{
	public function nameUL4()
	{
		return "print";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("values", Signature::$remainingArguments)
		);
	}

	public function evaluate($context, $args)
	{
		return self::call($context, $args[0]);
	}

	public static function call($context, $values)
	{
		$st = debug_backtrace();
		print "FunctionPrint::call st = " . FunctionRepr::call($st) . "\n";
		for ($i = 0; $i < count($values); ++$i)
		{
			if ($i != 0)
				$context->write(" ");
			$context->write(FunctionStr::call($values[$i]));
		}
		return null;
	}
}

?>