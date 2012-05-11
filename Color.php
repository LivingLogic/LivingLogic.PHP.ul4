<?php

namespace com\livinglogic\ul4on;

class Color
{
	var $r;
	var $g;
	var $b;
	var $a;
	
	function __construct($r, $g, $b, $a=255)
	{
		$this->r = $r;
		$this->g = $g;
		$this->b = $b;
		$this->a = $a;
	}
	
	public function dump()
	{
		$buffer = "";

		$sr = dechex($this->r);
		if (strlen($sr) < 2)
			$buffer .= "0";
		$buffer .= $sr;

		$sg = dechex($this->g);
		if (strlen($sg) < 2)
			$buffer .= "0";
		$buffer .= $sg;

		$sb = dechex($this->b);
		if (strlen($sb) < 2)
			$buffer .= "0";
		$buffer .= $sb;

		$sa = dechex($this->a);
		if (strlen($sa) < 2)
			$buffer .= "0";
		$buffer .= $sa;
		
		return $buffer;
	}
	
	static function fromdump($value)
	{
		$r = hexdec(substr($value, 0, 2));
		$g = hexdec(substr($value, 2, 2));
		$b = hexdec(substr($value, 4, 2));
		$a = hexdec(substr($value, 6, 2));

		return new Color($r, $g, $b, $a);
	}
	
	function __toString()
	{
		return $this->dump();
	}
}

// welche Funktionen noch implementieren?
?>