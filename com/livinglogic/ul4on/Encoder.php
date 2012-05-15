<?php

namespace com\livinglogic\ul4on;

include_once 'com/livinglogic/ul4on/Utils.php';
include_once 'com/livinglogic/ul4/Color.php';

use \com\livinglogic\ul4\Color as Color;

class Encoder
{
	public $buffer;
	private $objects = array();
	private $object2id = array();
	
	function __construct()
	{
		$this->buffer = '';
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
			$this->object2id[$obj] = count($this->objects);
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
			$this->record($obj);
			$this->buffer .= "S" . strlen($obj) . "|" . $obj;
		}
		else if ($obj instanceof \DateTime)
		{
			$this->record($obj);
			$this->buffer .= "T" . date_format($obj, "YmdHis") . "000000";
		}
		else if ($obj instanceof Color)
		{
			$this->record($obj);
			$this->buffer .= "C" . $obj->dump();
		}
		else if (Utils::isList($obj))
		{
			$this->record($obj);
			$this->buffer .= "l";
			for ($i = 0; $i < count($obj); $i++)
				$this->dump($obj[$i]);
			$this->buffer .= ".";
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
			$this->buffer .= ".";
		}
	}
}
?>