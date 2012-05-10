<?php
class Decoder
{
	var $buffer;
	var $index;
	var $objects;
	
	function Decoder($buffer)
	{
		$this->buffer = $buffer;
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
// 		echo "typecode = $typecode \n";
		if ($typecode == -2)
			$typecode = $this->nextChar();
// 		echo "typecode = $typecode \n";
		
		if ($typecode == '^')
		{
			// TODO
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
	}
	
	private function nextChar()
	{
		if (strlen($this->buffer) <= ($this->index - 1))
			throw new Exception("buffer is completely read. buffer = '$this->buffer', index = $this->index,  count =   " . strlen($this->buffer));

		return $this->buffer[++$this->index];
	}
	
	private function readInt()
	{
		$buffer = "";
		
		while (true)
		{
			$c = $this->nextChar();
			if ($c == '|')
				return intval($buffer);
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
				return doubleval($buffer);
			$buffer .= $c;
		}
	}
}
?>