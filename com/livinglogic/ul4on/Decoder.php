<?php

namespace com\livinglogic\ul4on;

include_once "com/livinglogic/ul4/ul4.php";

use com\livinglogic\ul4on\Utils as Utils;

use \com\livinglogic\ul4\Color as Color;
use \com\livinglogic\ul4\MonthDelta as MonthDelta;
use \com\livinglogic\ul4\TimeDelta as TimeDelta;

class Decoder
{
	var $buffer;
	var $index;
	var $objects;

	function __construct($buf)
	{
		$this->buffer = $buf;
		$this->index = -1;
		$this->objects = array();
	}

	private function loading(&$obj)
	{
		if ($obj === $this)
			array_push($this->objects, null);
		else
			array_push($this->objects, $obj);
	}

	public function load()
	{
		$typecode = $this->nextChar();

		if ($typecode == '^')
		{
			$index = $this->readInt();
			return $this->objects[$index];
		}
		else if ($typecode == 'n' || $typecode == 'N')
		{
			if ($typecode == 'N')
				$this->loading(NULL);
			return NULL;
		}
		else if ($typecode == 'b' || $typecode == 'B')
		{
			$data = $this->nextChar();
			$value = NULL;
			if ($data == 'T')
				$value = true;
			else if ($data == 'F')
				$value = false;
			else
				throw new Exception("broken stream: expected 'T' or 'F', got '\\u" . dechex((int)$data) . "'");
			if ($typecode == 'B')
				$this->loading($value);
			return $value;
		}
		else if ($typecode == 'i' || $typecode == 'I')
		{
			$value = $this->readInt();
			if ($typecode == 'I')
				$this->loading($value);
			return $value;
		}
		else if ($typecode == 'f' || $typecode == 'F')
		{
			$value = $this->readFloat();
			if ($typecode == 'F')
				$this->loading($value);
			return $value;
		}
		else if ($typecode == 's' || $typecode == 'S')
		{
			$count = $this->readInt();
			$value = "";
			for ($i = 0; $i < $count; $i++)
				$value .= $this->nextChar();
			if ($typecode == 'S')
				$this->loading($value);
			return $value;
		}
		else if ($typecode == 'c' || $typecode == 'C')
		{
			$buffer = "";
			for ($i = 0; $i < 8; $i++)
				$buffer .= $this->nextChar();
			$value = Color::fromdump($buffer);
			if ($typecode == 'C')
				$this->loading($value);
			return $value;
		}
		else if ($typecode == 'z' || $typecode == 'Z')
		{
			$value  = new \DateTime();

			$year   = $this->convertCharsToInt(4);
			$month  = $this->convertCharsToInt(2);
			$day    = $this->convertCharsToInt(2);
			$hour   = $this->convertCharsToInt(2);
			$minute = $this->convertCharsToInt(2);
			$second = $this->convertCharsToInt(2);
			$msecs  = $this->convertCharsToInt(6); // read the here unused microseconds

			$value->setDate($year, $month, $day);
			$value->setTime($hour, $minute, $second);

			if ($typecode == 'Z')
				$this->loading($value);
			return $value;
		}
		else if ($typecode == 't' || $typecode == 'T')
		{
			$days    = $this->readInt();
			$seconds = $this->readInt();
			$msecs   = $this->readInt(); // read the here unused microseconds

			$value = new TimeDelta($days, $seconds, $msecs);

			if ($typecode == 'T')
				$this->loading($value);

			return $value;
		}
		else if ($typecode == 'm' || $typecode == 'M')
		{
			$months = $this->readInt();
			$value = new MonthDelta($months);
			if ($typecode == 'M')
				$this->loading($value);
			return $value;
		}
		else if ($typecode == 'l' || $typecode == 'L')
		{
			$result = array();

			if ($typecode == 'L')
				$this->loading($result);

			while (true)
			{
				$typecode = $this->nextChar();
				if ($typecode == ']')
					return $result;
				else
				{
					$this->backup();
					array_push($result, $this->load());
				}
			}
		}
		else if ($typecode == 'd' || $typecode == 'D')
		{
			$result = array();

			if ($typecode == 'D')
				$this->loading($result);

			while (true)
			{
				$typecode = $this->nextChar();
				if ($typecode == '}')
					return $result;
				else
				{
					$this->backup();
					$key = $this->load();
					$value = $this->load();
					$result[$key] = $value;
				}
			}
		}
		else if ($typecode == 'o' || $typecode == 'O')
		{
			$oldpos = -1;
			if ($typecode == 'O')
			{
				// We have a problem here:
				// We have to record the object we're loading *now*, so that it is available for backreferences.
				// However until we've read the UL4ON name of the class, we can't create the object.
				// So we push null to the backreference list for now and put the right object in this spot,
				// once we've created it (This shouldn't be a problem, because during the time the backreference
				// is wrong, only the class name is read, so our object won't be referenced).
				$oldpos = count($this->objects);
				$this->loading($this);
			}
			$name = $this->load();

			$factory = Utils::$registry[$name];

			if ($factory == null)
				throw new \Exception("can't load object of type " . $name);
			$value = new $factory;
			// Fix object in backreference list
			if ($oldpos != -1)
				$this->objects[$oldpos] = $value;
			$value->loadUL4ON($this);
			return $value;
		}
		else
		{
			throw new \Exception("unkown typecode: '$typecode'");
		}
	}

	private function convertCharsToInt($count)
	{
		$buffer = "";

		for ($i = 0; $i < $count; $i++)
		{
			$buffer .= $this->nextChar();
		}

		return \intval($buffer);
	}

	private function nextChar()
	{
		if (\strlen($this->buffer) <= ($this->index - 1))
			throw new \Exception("buffer is completely read. buffer = '$this->buffer', index = $this->index,  count =   " . \strlen($this->buffer));

		$c = $this->buffer[++$this->index];

		return $c;
	}

	private function backup()
	{
		--$this->index;
	}

	private function readInt()
	{
		$buffer = "";

		while (true)
		{
			$c = $this->nextChar();
			if ($c == '|')
				return \intval($buffer);
			$buffer .= $c;
		}
	}

	private function readFloat()
	{
		$buffer = "";

		while (true)
		{
			$c = $this->nextChar();
			if ($c == '|')
				return \doubleval($buffer);
			$buffer .= $c;
		}
	}
}

?>