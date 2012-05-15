<?php
namespace com\livinglogic\ul4on\test;

include_once '/usr/share/php/PHPUnit/Framework/TestCase.php';
include_once 'Decoder.php';
include_once 'Encoder.php';
include_once 'Utils.php';
include_once 'Color.php';

use \com\livinglogic\ul4on\Encoder as Encoder;
use \com\livinglogic\ul4on\Decoder as Decoder;
use \com\livinglogic\ul4on\Utils as Utils;
use \com\livinglogic\ul4on\Color as Color;

class UL4ONTest extends \PHPUnit_Framework_TestCase
{
	private function encodeDecode($obj1)
	{
		$this->e = new Encoder();
		$this->e->dump($obj1);
		$this->d = new Decoder($this->e->buffer);
		$obj2 = $this->d->load();
		
		return $obj2;
	}
	
	public function testNull()
	{
		$obj1 = NULL;
		$obj2 = $this->encodeDecode($obj1);
	
		$this->assertEquals($obj1, $obj2);
	}
	
	public function testBool()
	{
		$obj1 = True;
		$obj2 = $this->encodeDecode($obj1);
	
		$this->assertEquals($obj1, $obj2);
		
		$obj1 = False;
		$obj2 = $this->encodeDecode($obj1);
	
		$this->assertEquals($obj1, $obj2);
	}
	
	public function testInt()
	{
		$obj1 = 42;
		$obj2 = $this->encodeDecode($obj1);
		
		$this->assertEquals($obj1, $obj2);
	}
	
	public function testFloat()
	{
		$obj1 = 2.71;
		$obj2 = $this->encodeDecode($obj1);
		
		$this->assertEquals($obj1, $obj2);
	}
	
	public function testString()
	{
		$obj1 = "abc|xyz";
		$obj2 = $this->encodeDecode($obj1);
		
		$this->assertEquals($obj1, $obj2);
	}
	
	public function testDateTime()
	{
		$obj1 = Utils::makeDate(2012, 8, 10, 15, 0, 20);
		$obj2 = $this->encodeDecode($obj1);
		
		$this->assertEquals($obj1, $obj2);
	}
	
	public function testColor()
	{
		$obj1 = new Color(20, 40, 60, 100);
		$obj2 = $this->encodeDecode($obj1);
		
		$this->assertEquals($obj1, $obj2);
	}
	
	public function testList()
	{
		$obj1 = array(1, 2.7, "abc", new Color(2, 3, 4, 5));
		$obj2 = $this->encodeDecode($obj1);
		
		$this->assertEquals($obj1, $obj2);
	}
	
	public function testDict()
	{
		$obj1 = array("0b" => "a", "x2.7" => 'b', "color" => new Color(2, 3, 4, 5), 'dateTime' => Utils::makeDate(2011, 3, 24));
		$obj2 = $this->encodeDecode($obj1);
		
		$this->assertEquals($obj1, $obj2);
	}
}
?>