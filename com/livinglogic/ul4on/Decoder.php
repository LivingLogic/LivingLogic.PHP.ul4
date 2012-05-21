<?php

namespace com\livinglogic\ul4on;

include_once "com/livinglogic/ul4/Color.php";
include_once "com/livinglogic/ul4on/Utils.php";

use com\livinglogic\ul4on\Utils as Utils;

use \com\livinglogic\ul4\Color as Color;

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

		public function __call($name, $arguments)
		{
			if ($name == 'load' && ($arguments == NULL || count($arguments) == 0))
				return $this->load(-2);
			else if ($name == 'load' && ($arguments != NULL || count($arguments) == 1))
				return $this->load($arguments[0]);
		}

		private function loading($obj)
		{
			array_push($this->objects, $obj);
		}

		private function load($typecode)
		{
			if ($typecode == -2)
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
			else if ($typecode == 't' || $typecode == 'T')
			{
	// 			char[] chars = new char[20];
	// 			reader.read(chars);
				$buffer = "";
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

				if ($typecode == 'T')
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
			else if ($typecode == 'l' || $typecode == 'L')
			{
				$result = array();

				if ($typecode == 'L')
					$this->loading($result);

				while (true)
				{
					$typecode = $this->nextChar();
					if ($typecode == '.')
						return $result;
					else
						array_push($result, $this->load($typecode));
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
					if ($typecode == '.')
						return $result;
					else
					{
						$key = $this->load($typecode);
						$value = $this->load(-2);
						$result[$key] = $value;
					}
				}
			}
			else if ($typecode == 'o' || $typecode == 'O')
			{
				$oldpos = 1;
				if ($typecode == 'O')
				{
					// We have a problem here:
					// We have to record the object we're loading *now*, so that it is available for backreferences.
					// However until we've read the UL4ON name of the class, we can't create the object.
					// So we push null to the backreference list for now and put the right object in this spot,
					// once we've created it (This shouldn't be a problem, because during the time the backreference
					// is wrong, only the class name is read, so our object won't be refenced).
					$oldpos = count($this->objects);
					$this->loading(null);
				}
				$name = $this->load(-2);

				$factory = Utils::$registry[$name];

				if ($factory == null)
					throw new Exception("can't load object of type " . $name);
				$value = new $factory;
				// Fix object in backreference list
				if ($oldpos != -1)
					$this->objects[$oldpos] = $value;
				$value->loadUL4ON($this);
				return $value;
			}
			else
			{
				echo "unkown typecode: '$typecode'\n";
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
				throw new Exception("buffer is completely read. buffer = '$this->buffer', index = $this->index,  count =   " . \strlen($this->buffer));

// 			echo "index = $this->index \n";
			$c = $this->buffer[++$this->index];
// 			echo "c = $c\n";
// 			echo "buffer = $this->buffer\n";
			return $c;
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