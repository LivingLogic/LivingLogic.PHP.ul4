<?php

namespace com\livinglogic\ul4on;

include_once 'com/livinglogic/ul4/ul4.php';

use \com\livinglogic\ul4\Color as Color;
use \com\livinglogic\ul4on\UL4ONSerializable as UL4ONSerializable;

class Encoder
{
	private $buffer;
	private $objects = array();
	private $object2id = array();
	private $string2id = array();

	function __construct()
	{
		$this->buffer = "";
	}

	public function getOutput()
	{
		return $this->buffer;
	}

	private function record($obj)
	{
		if (is_object($obj))
		{
			$key = spl_object_hash($obj); // TODO use id from obj itself -> problem with DateTime???
			$this->object2id[$key] = count($this->objects);
			array_push($this->objects, $obj);
		}
		else if (is_string($obj))
		{
			$this->string2id[$obj] = count($this->objects);
			array_push($this->objects, $obj);
		}
	}

	public function dump($obj)
	{
		if (False)
			echo "Encoder.dump: obj = " . var_export($obj, true) . "\n";

		if ($obj === NULL)
			$this->buffer .= 'n';
		else if (is_int($obj) || is_long($obj))
			$this->buffer .= "i$obj|";
		else if (is_double($obj) || is_float($obj))
			$this->buffer .= "f" .sprintf("%F", $obj) . "|";
		else if (is_bool($obj))
			$this->buffer .= ("b" . ($obj ? "T" : "F"));
		else if (is_string($obj))
		{
			if (array_key_exists($obj, $this->string2id))
				$this->buffer .= "^" . $this->string2id[$obj] . "|";
			else
			{
				$this->record($obj);
				$this->buffer .= "S" . strlen($obj) . "|" . $obj;
			}
		}
		else if ($obj instanceof \DateTime)
		{
			if (array_key_exists(spl_object_hash($obj), $this->object2id))
				$this->buffer .= "^" . $this->object2id[spl_object_hash($obj)] . "|";
			else
			{
				$this->record($obj);
				$this->buffer .= "T" . date_format($obj, "YmdHis") . "000000";
			}
		}
		else if ($obj instanceof Color)
		{
			if (array_key_exists(spl_object_hash($obj), $this->object2id))
				$this->buffer .= "^" . $this->object2id[spl_object_hash($obj)] . "|";
			else
			{
				$this->record($obj);
				$this->buffer .= "C" . $obj->dump();
			}
		}
		else if ($obj instanceof UL4ONSerializable) // check this before Collection and Map
		{
			if (array_key_exists(spl_object_hash($obj), $this->object2id))
				$this->buffer .= "^" . $this->object2id[spl_object_hash($obj)] . "|";
			else
			{
				$this->record($obj);
				$this->buffer .= "O";
				$this->dump($obj->getUL4ONName());
				$obj->dumpUL4ON($this);
			}
		}
		else if (Utils::isList($obj))
		{
			$this->record($obj);
			$this->buffer .= "l";
			for ($i = 0; $i < count($obj); $i++)
				$this->dump($obj[$i]);
			$this->buffer .= "]";
		}
		else if (Utils::isDict($obj))
		{
			$this->record($obj);
			$this->buffer .= "d";
			$keys = array_keys($obj);
			for ($i = 0; $i < count($keys); $i++)
			{
				$this->dump($keys[$i]);
				$this->dump($obj[$keys[$i]]);
			}
			$this->buffer .= "]";
		}
	}
}
?>