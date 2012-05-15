<?php

namespace com\livinglogic\ul4on;

include_once 'Utils.php';

class Encoder
{
	public $buffer;

	function __construct()
	{
		$this->buffer = '';
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
			$this->buffer .= "S" . strlen($obj) . "|" . $obj;
		else if ($obj instanceof \DateTime)
			$this->buffer .= "T" . date_format($obj, "YmdHis") . "000000";
		else if ($obj instanceof Color)
			$this->buffer .= "C" . $obj->dump();
		else if (Utils::isList($obj))
		{
			$this->buffer .= "l";
			for ($i = 0; $i < count($obj); $i++)
				$this->dump($obj[$i]);
			$this->buffer .= ".";
		}
		else if (Utils::isDict($obj))
		{
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