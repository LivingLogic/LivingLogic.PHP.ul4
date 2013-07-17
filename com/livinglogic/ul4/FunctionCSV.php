<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionCSV extends _Function
{
	public function nameUL4()
	{
		return "csv";
	}

	protected function makeSignature()
	{
		return new Signature(
				$this->nameUL4(),
				"obj", Signature::$required
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		if (is_null($obj))
			return "";
		if (! is_string($obj))
			$obj = self::repr($obj);

		$pos0 = strpos($obj, '"');
		$pos1 = strpos($obj, ',');
		$pos2 = strpos($obj, '\n');

		if (is_bool($pos0) && is_bool($pos1) && is_bool($pos2))
			return $obj;

		$obj = str_replace('"', '""', $obj);
		return '"' . $obj . '"';
	}

}

?>