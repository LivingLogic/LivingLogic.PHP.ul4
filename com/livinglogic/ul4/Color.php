<?php

namespace com\livinglogic\ul4;

class Color implements UL4Repr, UL4Len, UL4Type, UL4MethodCall
{
	var $r;
	var $g;
	var $b;
	var $a;

	private $signatureR = null;
	private $signatureG = null;
	private $signatureB = null;
	private $signatureA = null;
	private $signatureLum = null;
	private $signatureHLS = null;
	private $signatureHLSA = null;
	private $signatureHSV = null;
	private $signatureHSVA = null;
	private $signatureWithA = null;
	private $signatureWithLum = null;

	function __construct($r, $g, $b, $a=255)
	{
		$this->setColorCoordinate($r, $this->r);
		$this->setColorCoordinate($g, $this->g);
		$this->setColorCoordinate($b, $this->b);
		$this->setColorCoordinate($a, $this->a);

		$this->signatureR = new Signature("r");
		$this->signatureG = new Signature("g");
		$this->signatureB = new Signature("b");
		$this->signatureA = new Signature("a");
		$this->signatureLum = new Signature("lum");
		$this->signatureHLS = new Signature("hls");
		$this->signatureHLSA = new Signature("hlsa");
		$this->signatureHSV = new Signature("hsv");
		$this->signatureHSVA = new Signature("hsva");
		$this->signatureWithA = new Signature("witha", array("a", Signature::$required));
		$this->signatureWithLum = new Signature("withlum", array("lum", Signature::$required));
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

	private static function hexstr($value)
	{
		$svalue = dechex($value);

		if (mb_strlen($svalue, \com\livinglogic\ul4on\Utils::$encoding) < 2)
			return "0" . $svalue;
		return $svalue;
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
				return "#" . dechex($this->r>>4) . dechex($this->g>>4) . dechex($this->b>>4);
			else
				return "#" . self::hexstr($this->r) . self::hexstr($this->g) . self::hexstr($this->b);
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
		$r = hexdec(mb_substr($value, 0, 2, \com\livinglogic\ul4on\Utils::$encoding));
		$g = hexdec(mb_substr($value, 2, 2, \com\livinglogic\ul4on\Utils::$encoding));
		$b = hexdec(mb_substr($value, 4, 2, \com\livinglogic\ul4on\Utils::$encoding));
		$a = hexdec(mb_substr($value, 6, 2, \com\livinglogic\ul4on\Utils::$encoding));

		return new Color($r, $g, $b, $a);
	}

	public static function fromrepr($value)
	{
		if ($value == null)
			return null;
		$len = mb_strlen($value, \com\livinglogic\ul4on\Utils::$encoding);
		$r;
		$g;
		$b;
		$a;
		if ($len === 4 || $len === 5)
		{
			$r = hexdec(mb_substr($value, 1, 1, \com\livinglogic\ul4on\Utils::$encoding)) * 0x11;
			$g = hexdec(mb_substr($value, 2, 1, \com\livinglogic\ul4on\Utils::$encoding)) * 0x11;
			$b = hexdec(mb_substr($value, 3, 1, \com\livinglogic\ul4on\Utils::$encoding)) * 0x11;
			$a = ($len === 4) ? 0xff : (hexdec(mb_substr($value, 4, 1, \com\livinglogic\ul4on\Utils::$encoding)) * 0x11);
		}
		else if ($len === 7 || $len === 9)
		{
			$r = hexdec(mb_substr($value, 1, 2, \com\livinglogic\ul4on\Utils::$encoding));
			$g = hexdec(mb_substr($value, 3, 2, \com\livinglogic\ul4on\Utils::$encoding));
			$b = hexdec(mb_substr($value, 5, 2, \com\livinglogic\ul4on\Utils::$encoding));
			$a = ($len == 7) ? 0xff : hexdec(mb_substr($value, 7, 2, \com\livinglogic\ul4on\Utils::$encoding));
		}
		else
			throw new \Exception("Invalid color repr '" + $value + "'");

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
		$minc = min(intval($this->r), intval($this->g), intval($this->b));

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
		else if ($this->g == $maxc)
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
		if ($this->r == $maxc)
			$h = $bc-$gc;
		else if ($this->g == $maxc)
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
		array_push($retVal, doubleval($this->a/255.));
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

	public function lenUL4()
	{
		return 4;
	}

	public function typeUL4()
	{
		return "color";
	}

	public function getItemIntegerUL4($index)
	{
		switch ($index)
		{
			case 0:
			case -4:
				return $this->r;
			case 1:
			case -3:
				return $this->g;
			case 2:
			case -2:
				return $this->b;
			case 3:
			case -1:
				return $this->a;
			default:
				throw new \Exception("invalid index: " . $index);
		}
	}

	public function callMethodUL4($methodName, $args, $kwargs)
	{
		if ("r" == $methodName)
		{
			$args = $this->signatureR->makeArgumentArray($args, $kwargs);
			return $this->r;
		}
		else if ("g" == $methodName)
		{
			$args = $this->signatureG->makeArgumentArray($args, $kwargs);
			return $this->g;
		}
		else if ("b" == $methodName)
		{
			$args = $this->signatureB->makeArgumentArray($args, $kwargs);
			return $this->b;
		}
		else if ("a" == $methodName)
		{
			$args = $this->signatureA->makeArgumentArray($args, $kwargs);
			return $this->a;
		}
		else if ("lum" == $methodName)
		{
			$args = $this->signatureLum->makeArgumentArray($args, $kwargs);
			return $this->lum();
		}
		else if ("hls" == $methodName)
		{
			$args = $this->signatureHLS->makeArgumentArray($args, $kwargs);
			return $this->hls();
		}
		else if ("hlsa" == $methodName)
		{
			$args = $this->signatureHLSA->makeArgumentArray($args, $kwargs);
			return $this->hlsa();
		}
		else if ("hsv" == $methodName)
		{
			$args = $this->signatureHSV->makeArgumentArray($args, $kwargs);
			return $this->hsv();
		}
		else if ("hsva" == $methodName)
		{
			$args = $this->signatureHSVA->makeArgumentArray($args, $kwargs);
			return $this->hsva();
		}
		else if ("witha" == $methodName)
		{
			$args = $this->signatureWithA->makeArgumentArray($args, $kwargs);
			return $this->witha(FunctionInt::call($args[0]));
		}
		else if ("withlum" == $methodName)
		{
			$args = $this->signatureWithLum->makeArgumentArray($args, $kwargs);
			return $this->withlum(FunctionFloat::call($args[0]));
		}
		else
			throw new UnknownMethodException($methodName);
	}

	public function reprUL4()
	{
		return $this->repr();
	}
}

?>
