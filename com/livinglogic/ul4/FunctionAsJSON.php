<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAsJSON extends _Function
{
	public function nameUL4()
	{
		return "asjson";
	}

	protected function makeSignature()
	{
		return new Signature(
			$this->nameUL4(),
			array("obj", Signature::$required)
		);
	}

	public function evaluate($args)
	{
		return self::call($args[0]);
	}

	public static function call($obj)
   {
   	if (is_null($obj))
   		return "null";
   	else if (is_bool($obj))
   		return $obj ? "true" : "false";
   	else if (is_int($obj) || is_long($obj) || is_float($obj) || is_double($obj))
   		return "" . $obj;
   	else if (is_string($obj))
   		return json_encode($obj);
   	else if ($obj instanceof \DateTime)
   	{
   		$buffer = "new Date(";
   		$buffer .= date_format($obj, "Y, m, d, H, i, s");
   		$buffer .= ")";
   		return $buffer;
   	}
   	else if (obj instanceof UL4Attributes)
		{
			$sb = "";
			$sb .= "{";
			$first = true;
			$attributeNames = $obj->getAttributeNamesUL4();
			foreach ($attributeName as $attributeName)
			{
				if ($first)
					$first = false;
				else
					$sb .= ", ";
				$sb .= self::call($attributeName);
				$sb .= ": ";
				$value = $obj->getItemStringUL4($attributeName);
				$sb .= self::call($value);
			}
			$sb .= "}";
			return $sb;
		}
		else if ($obj instanceof Color)
		{
			return "ul4.Color.create(".$obj->getR().", ".$obj->getG().", ".$obj->getB().", ".$obj->getA().")";
		}
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
		{
			$b = "[";
			$first = true;
			foreach ($obj as $item)
			{
				if ($first)
					$first = false;
				else
					$b .= ", ";
				$b .= self::_call($item);
			}
			$b .= "]";
			return $b;
		}
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			$b = "{";
			$first = true;
			foreach ($obj as $key => $value)
			{
				if ($first)
					$first = false;
				else
					$b .= ", ";
				$b .= self::_call($key);
				$b .= ": ";
				$b .= self::_call($value);
			}
			$b .= "}";
			return $b;
		}

		return json_encode($obj);
   }
}

?>