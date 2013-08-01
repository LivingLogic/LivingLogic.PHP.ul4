<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';


class FunctionPrintX extends FunctionWithContext
{
	public function nameUL4()
	{
		return "printx";
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
		for ($i = 0; $i < count($values); ++$i)
		{
			if ($i != 0)
				$context->write(" ");
			$context->write(FunctionXMLEscape::call($values[$i]));
		}
		return null;
	}
}

?>