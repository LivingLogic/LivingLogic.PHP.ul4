<?php
class Encoder
{
	public $buffer;

	function Encoder()
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
		else if (is_a($obj, "DateTime"))
			$this->buffer .= "T" . date_format($obj, "YmdHis") . "000000";
		else if (is_a($obj, "Color"))
			$this->buffer .= "C" . $obj->dump();

	}
}
?>