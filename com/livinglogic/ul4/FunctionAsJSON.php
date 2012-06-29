<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAsJSON implements _Function
{
	public function call($context, $args)
	{
      if (count($args) == 1)
         return self::_call($args[0]);
      throw new ArgumentCountMismatchException("function", "asjson", count($args), 1);
	}

	public function getName()
	{
		return "asjson";
	}

   public static function _call($obj)
   {
   	if ($obj instanceof \DateTime)
   	{
   		$buffer = "new Date(";
   		$buffer .= date_format($obj, "Y, m, d, H, i, s");
   		$buffer .= ")";
   		return $buffer;
   	}
		else if ($obj instanceof InterpretedTemplate)
		{
			// TODO
		}
		else if ($obj instanceof JsonSerializable)  // TODO remove this else if when php 5.4 is used everywhere
		{
			return $obj->jsonSerialize();
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