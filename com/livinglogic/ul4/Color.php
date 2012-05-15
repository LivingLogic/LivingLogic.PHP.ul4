<?php

namespace com\livinglogic\ul4;

class Color
{
	var $r;
	var $g;
	var $b;
	var $a;
	
	function __construct($r, $g, $b, $a=255)
	{
		$this->setColorCoordinate($r, $this->r);
		$this->setColorCoordinate($g, $this->g);
		$this->setColorCoordinate($b, $this->b);
		$this->setColorCoordinate($a, $this->a);
	}
	
	private function setColorCoordinate($value, &$member)
	{
		if (is_double($value) || is_float($value))
			$value = intval($value * 255);

		if ($value < 0)
			$value = 0;
		else if ($value > 255)
			$value = 255;

		$member = $value;
	}
	
	private function hexstr($value)
	{
		$buffer = "";
		$svalue = dechex($value);

		if (strlen($svalue) < 2)
			$buffer .= "0";
		$buffer .= $svalue;
		
		return $buffer;
	}
	
	public function dump()
	{
		$buffer = "";
		
		$buffer .= $this->hexstr($this->r);
		$buffer .= $this->hexstr($this->g);
		$buffer .= $this->hexstr($this->b);
		$buffer .= $this->hexstr($this->a);
		
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

	public static function fromhsv($h, $s, $v, $a=1.0)
	{
		$h %= 1.0;

		if ($s < 0.0)
			$s = 0.0;
		else if ($s > 1.0)
			$s = 1.0;

		if ($v < 0.0)
			$v = 0.0;
		else if ($v > 1.0)
			$v = 1.0;

		if ($a < 0.0)
			$a = 0.0;
		else if ($a > 1.0)
			$a = 1.0;

		$rr = 0;
		$rg = 0;
		$rb = 0;
		$ra = intval(255.*$a);

		if ($s == 0.0)
 			$rr = $rg = $rb = intval(255.*$v);
		else
		{
			$i = intval($h*6.0);
			$f = ($h*6.0) - $i;
			$p = $v*(1.0 - $s);
			$q = $v*(1.0 - $s*$f);
			$t = $v*(1.0 - $s*(1.0-$f));

			switch ($i)
			{
				case 0:
				case 6:
					$rr = intval(255.*$v);
					$rg = intval(255.*$t);
					$rb = intval(255.*$p);
					break;
				case 1:
					$rr = intval(255.*$q);
					$rg = intval(255.*$v);
					$rb = intval(255.*$p);
					break;
				case 2:
					$rr = intval(255.*$p);
					$rg = intval(255.*$v);
					$rb = intval(255.*$t);
					break;
				case 3:
					$rr = intval(255.*$p);
					$rg = intval(255.*$q);
					$rb = intval(255.*$v);
					break;
				case 4:
					$rr = intval(255.*$t);
					$rg = intval(255.*$p);
					$rb = intval(255.*$v);
					break;
				case 5:
					$rr = intval(255.*$v);
					$rg = intval(255.*$p);
					$rb = intval(255.*$q);
					break;
			}
		}
		return new Color($rr, $rg, $rb, $ra);
	}

	private static function _v($m1, $m2, $hue)
	{
		$hue %= 1.0;

		if ($hue < 1./6.)
			return $m1 + ($m2-$m1)*$hue*6.0;
		else if ($hue < 0.5)
			return $m2;
		else if ($hue < 2./3.)
			return $m1 + ($m2-$m1)*(2./3.-$hue)*6.0;
		else
			return $m1;
	}

	public static function fromhls($h, $l, $s, $a=1.0)
	{
		$h %= 1.0;

		if ($l < 0.0)
			$l = 0.0;
		else if ($l > 1.0)
			$l = 1.0;

		if ($s < 0.0)
			$s = 0.0;
		else if ($s > 1.0)
			$s = 1.0;

		if ($a < 0.0)
			$a = 0.0;
		else if ($a > 1.0)
			$a = 1.0;

		if ($s == 0.0)
			return new Color(intval(255.*$l), intval(255.*$l), intval(255.*$l), intval(255.*$a));

		$m2 = $l <= 0.5 ? $l * (1.0+$s) : $l+$s-($l*$s);
		$m1 = 2.0*$l - $m2;

		$r = $this->_v($m1, $m2, $h+1./3.);
		$g = $this->_v($m1, $m2, $h);
		$b = $this->_v($m1, $m2, $h-1./3.);

		return new Color(intval(255.*$r), intval(255.*$g), intval(255.*$b), intval(255.*$a));
	}

	public function getR()
	{
		return $this->r;
	}
	
	public function getG()
	{
		return $this->g;
	}
	
	public function getB()
	{
		return $this->b;
	}
	
	public function getA()
	{
		return $this->a;
	}
	
	
	// TODO weiter mit toString
	function __toString()
	{
		return $this->dump();
	}
}

// welche Funktionen noch implementieren?
?>