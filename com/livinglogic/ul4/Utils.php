<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/Color.php';
include_once 'com/livinglogic/ul4on/Utils.php';

use com\livinglogic\ul4\Color as Color;

class Repr
{
	private $visited = array();

	public function toString($obj)
	{
		if ($obj == null)
			return "None";
		else if (is_bool($obj))
			return $obj ? "True" : "False";
		else if (is_int($obj) || is_long($obj) || is_double($obj) || is_float($obj))
			return "" . $obj;
		else if (is_string($obj))
			return '"' . addslashes($obj)	. '"';
		else if ($obj instanceof \DateTime)
		{
			return date_format($obj, "YmdHis") . "000000";
		}
		else if ($obj instanceof Color)
			return $obj->repr();
		else if (com\livinglogic\ul4on\Utils::isList($obj))
		{
			if ($this->seen($obj))
				return "[...]";
			array_push($this->visited, $obj);

			try
			{
				$sb = "[";
				$first = true;
				foreach ($obj as $o)
				{
					if ($first)
						$first = false;
					else
						$sb .= $this->toString($o);
				}
				$sb .= "]";

				return $sb;
			}
			catch (Exception $e)
			{
				array_pop($this->visited);
				return "{?}";
			}
		}
		else if (com\livinglogic\ul4on\Utils::isDict($obj))
		{
			if ($this->seen($obj))
				return "{...}";
			array_push($this->visited, $obj);

			try
			{
				$sb = "{";
				$first = true;

				foreach ($obj as $key => $value)
				{
					if ($first)
						$first = false;
					else
						$sb .= ", ";
					$sb .= $this->toString($key);
					$sb .= ": ";
					$sb .= $this->toString($value);
				}
				$sb .= "}";
				return $sb;
			}
			catch (Exception $e)
			{
				array_pop($this->visited);
				return "{?}";
			}
		}
		return "?";
	}

	private function seen($obj)
	{
		foreach ($this->visited as $item)
		{
			if ($obj === $item)
				return true;
		}
		return false;
	}
}

class Utils
{
	public static function repr($obj)
	{
		$r = new Repr();
		return $r>toString($obj);
	}

}

?>