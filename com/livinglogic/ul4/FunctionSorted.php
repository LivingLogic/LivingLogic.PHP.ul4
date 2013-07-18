<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionSorted extends _Function
{
	public function nameUL4()
	{
		return "sorted";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			"iterable", Signature::$required
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
	{
		if (is_string($obj))
		{
			$retVal = array();
			$length = strlen($obj);
			for ($i = 0; $i < $length; $i++)
			{
				array_push($retVal, $obj[$i]);
			}
			asort($retVal);
			return $retVal;
		}
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$length = count($obj);
			$retVal = array();
			for ($i = 0; $i < $length; $i++)
			{
				array_push($retVal, $obj[$i]);
			}
			asort($retVal);
			return $retVal;
		}
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			$retVal = array_keys($obj);
			asort($retVal);
			return $retVal;
		}
		else if ($obj instanceof \Iterator)
		{
			$retVal = array();
			foreach ($obj as $key => $value)
			{
				array_push($retVal, $value);
			}
			asort($retVal);
			return $retVal;
		}

		throw new \Exception("sorted(" . Utils::objectType($obj) . ") not supported!");
	}
}

?>