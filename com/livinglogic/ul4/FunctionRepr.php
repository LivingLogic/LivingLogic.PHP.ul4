<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionRepr implements _Function
{
	public function nameUL4()
	{
		return "repr";
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
		$r = new Repr();
		return $r->toString($obj);
	}
}

class Repr
{
	private $visited = array();

	public function toString($obj)
	{
		if (is_null($obj))
			return "None";
		else if ($obj instanceof Undefined)
			return "Undefined";
		else if (is_bool($obj))
			return $obj ? "True" : "False";
		else if (is_int($obj) || is_long($obj) || is_double($obj) || is_float($obj))
			return "" . $obj;
		else if (is_string($obj))
			return '"' . addslashes($obj)	. '"';
		else if ($obj instanceof \DateTime)
		{
			$date = date_format($obj, "Y-m-d");
			$time = date_format($obj, "H:i:s");
			if ($time == "00:00:00")
				return "@(" . $date . ")";
			else
				return "@(" . $date . "T" . $time . ")";
		}
		else if ($obj instanceof TimeDelta)
			return $obj->repr();
		else if ($obj instanceof MonthDelta)
			return $obj->repr();
		else if ($obj instanceof Color)
			return $obj->repr();
		else if (\com\livinglogic\ul4on\Utils::isList($obj))
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
						$sb .= ", ";
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
		else if (\com\livinglogic\ul4on\Utils::isDict($obj))
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
		/*
		 else if ($obj instanceof \Iterator)
		 {
		$first = true;
		$sb = "iterator[";
		foreach ($obj as $o)
		{
		if ($first)
			$first = false;
		else
			$sb .= ", ";
		$sb .= $this->toString($o);
		}
		$sb .= "]";
		return $sb;
		}
		*/
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

?>