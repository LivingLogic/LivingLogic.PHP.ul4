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

	public function get($idx)
	{
		if ($idx == 0)
			return $this->r;
		else if ($idx == 1)
			return $this->g;
		else if ($idx == 2)
			return $this->b;
		else if ($idx == 3)
			return $this->a;
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

	public static function fromhsv($h, $s, $v, $a=1.0)
	{
		$h = fmod($h, 1.0);

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

		if ($s === 0.0)
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
		$hue = fmod($hue, 1.0);

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
		$h = fmod($h, 1.0);

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

		$r = self::_v($m1, $m2, $h+1./3.);
		$g = self::_v($m1, $m2, $h);
		$b = self::_v($m1, $m2, $h-1./3.);

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

	private function toString()
	{
		if ($this->a === 255)
		{
			if ((($this->r>>4) == ($this->r&0xf)) && (($this->g>>4) == ($this->g&0xf)) && (($this->b>>4) == ($this->b&0xf)))
				return "#" + Integer.toHexString($this->r>>4) + Integer.toHexString($this->g>>4) + Integer.toHexString($this->b>>4);
			else
			{
				$sr = dechex($this->r);
				if (strlen($sr) < 2)
					$sr = "0" . $sr;

				$sg = dechex($this->g);
				if (strlen($sg) < 2)
					$sg = "0" . $sg;

				$sb = dechex($this->b);
				if (strlen($sb) < 2)
					$sb = "0" . $sb;

				return "#" . $sr . $sg . $sb;
			}
		}
		else
		{
			return "rgba(" . $this->r . "," . $this->g . "," . $this->b . "," . $this->a/255. . ")";
		}
	}

	function __toString()
	{
		return $this->toString();
	}

	public function repr()
	{
		if ((($this->r>>4) == ($this->r&0xf)) && (($this->g>>4) == ($this->g&0xf)) && (($this->b>>4) == ($this->b&0xf)) && (($this->a>>4) == ($this->a&0xf)))
		{
			if ($this->a !== 255)
				return sprintf("#%x%x%x%x", $this->r>>4, $this->g>>4, $this->b>>4, $this->a>>4);
			else
				return sprintf("#%x%x%x", $this->r>>4, $this->g>>4, $this->b>>4);
		}
		else
		{
			if ($this->a != 255)
				return sprintf("#%02x%02x%02x%02x", $this->r, $this->g, $this->b, $this->a);
			else
				return sprintf("#%02x%02x%02x", $this->r, $this->g, $this->b);
		}
	}

	public function dump()
	{
		return sprintf("%02x%02x%02x%02x", $this->r, $this->g, $this->b, $this->a);
	}

	static function fromdump($value)
	{
		$r = hexdec(substr($value, 0, 2));
		$g = hexdec(substr($value, 2, 2));
		$b = hexdec(substr($value, 4, 2));
		$a = hexdec(substr($value, 6, 2));

		return new Color($r, $g, $b, $a);
	}

	public static function fromrepr($value)
	{
		if ($value == null)
			return null;
		$len = strlen($value);
		$r;
		$g;
		$b;
		$a;
		if ($len === 4 || $len === 5)
		{
			$r = hexdec(substr($value, 1, 1)) * 0x11;
			$g = hexdec(substr($value, 2, 1)) * 0x11;
			$b = hexdec(substr($value, 3, 1)) * 0x11;
			$a = ($len === 4) ? 0xff : (hexdec(substr($value, 4, 1)) * 0x11);
		}
		else if ($len === 7 || $len === 9)
		{
			$r = hexdec(substr($value, 1, 2));
			$g = hexdec(substr($value, 3, 2));
			$b = hexdec(substr($value, 5, 2));
			$a = ($len == 7) ? 0xff : hexdec(substr($value, 7, 2));
		}
		else
			throw new Exception("Invalid color repr '" + $value + "'");

		return new Color($r, $g, $b, $a);
	}

	public function blend($color)
	{
		$sa = $this->a/255.;
		$rsa = 1. - $sa;
		$nr = intval($this->r * $sa + $rsa * $color->r);
		$ng = intval($this->g * $sa + $rsa * $color->g);
		$nb = intval($this->b * $sa + $rsa * $color->b);
		$na = intval(255 - $rsa * (255 - $color->a));

		return new Color($nr, $ng, $nb, $na);
	}

	public function hls()
	{
		$maxc = max(intval($this->r), intval($this->g), intval($this->b));
		$minc = NumberUtils.min(intval($this->r), intval($this->g), intval($this->b));

		$dmaxc = $maxc/255.;
		$dminc = $minc/255.;

		$l = ($dminc+$dmaxc)/2.0;

		if ($minc === $maxc)
		{
			$retVal = array();
			array_push($retVal, 0.0);
			array_push($retVal, $l);
			array_push($retVal, 0.0);
			return $retVal;
		}
		$s = $l <= 0.5 ? ($dmaxc-$dminc) / ($dmaxc+$dminc) : ($dmaxc-$dminc) / (2.0-$dmaxc-$dminc);

		$rc = ($dmaxc-$this->r/255.) / ($dmaxc-$dminc);
		$gc = ($dmaxc-$this->g/255.) / ($dmaxc-$dminc);
		$bc = ($dmaxc-$this->b/255.) / ($dmaxc-$dminc);

		$h;
		if ($this->r == $maxc)
			$h = $bc-$gc;
		else if ($g == $maxc)
			$h = 2.0+$rc-$bc;
		else
			$h = 4.0+$gc-$rc;
		$h = fmod(($h/6.0), 1.0);

		$retVal = array();
		array_push($retVal, doubleval($h));
		array_push($retVal, doubleval($l));
		array_push($retVal, doubleval($s));
		return $retVal;
	}

	public function hlsa()
	{
		$retVal = $this->hls();
		array_push($retVal, doubleval($this->a/255.));
		return $retVal;
	}

	public function hsv()
	{
		$maxc = max(intval($this->r), intval($this->g), intval($this->b));
		$minc = min(intval($this->r), intval($this->g), intval($this->b));

		$dmaxc = $maxc/255.;
		$dminc = $minc/255.;

		$v = $dmaxc;
		if ($minc == $maxc)
		{
			$retVal = array();
			array_push($retVal, 0.0);
			array_push($retVal, 0.0);
			array_push($retVal, $v);
			return $retVal;
		}
		$s = ($dmaxc-$dminc) / $dmaxc;

		$rc = ($dmaxc-$this->r/255.) / ($dmaxc-$dminc);
		$gc = ($dmaxc-$this->g/255.) / ($dmaxc-$dminc);
		$bc = ($dmaxc-$this->b/255.) / ($dmaxc-$dminc);

		$h = 0;
		if ($r == $maxc)
			$h = $bc-$gc;
		else if ($g == $maxc)
			$h = 2.0+$rc-$bc;
		else
			$h = 4.0+$gc-$rc;
		$h = fmod(($h/6.0), 1.0);

		$retVal = array();
		array_push($retVal, $h);
		array_push($retVal, $s);
		array_push($retVal, $v);
		return $retVal;
	}

	public function hsva()
	{
		$retVal = $this->hsv();
		array_push($retVal, doubleval($a/255.));
		return $retVal;
	}

	public function lum()
	{
		$maxc = max(intval($this->r), intval($this->g), intval($this->b));
		$minc = min(intval($this->r), intval($this->g), intval($this->b));

		$dmaxc = $maxc/255.;
		$dminc = $minc/255.;

		return ($dminc+$dmaxc)/2.0;
	}

	public function withlum($lum)
	{
		$maxc = max(intval($this->r), intval($this->g), intval($this->b));
		$minc = min(intval($this->r), intval($this->g), intval($this->b));

		$dmaxc = $maxc/255.;
		$dminc = $minc/255.;

		$l = ($dminc+$dmaxc)/2.0;

		if ($minc == $maxc)
			return $this->fromhls(0., $lum, 0., $this->a);

		$s = $l <= 0.5 ? ($dmaxc-$dminc) / ($dmaxc+$dminc) : ($dmaxc-$dminc) / (2.0-$dmaxc-$dminc);

		$rc = ($dmaxc-$this->r/255.) / ($dmaxc-$dminc);
		$gc = ($dmaxc-$this->g/255.) / ($dmaxc-$dminc);
		$bc = ($dmaxc-$this->b/255.) / ($dmaxc-$dminc);

		$h = 0;
		if ($this->r == $maxc)
			$h = $bc-$gc;
		else if ($this->g == $maxc)
			$h = 2.0+$rc-$bc;
		else
			$h = 4.0+$gc-$rc;
		$h = fmod(($h/6.0), 1.0);

		return $this->fromhls($h, $lum, $s, $this->a);
	}

	public function witha($a)
	{
		return new Color($this->r, $this->g, $this->b, $a);
	}

}

?>
