<?php
namespace com\livinglogic\ul4\test;

use com\livinglogic\ul4\InterpretedTemplate;

use com\livinglogic\ul4\EvaluationContext;

require_once 'PHPUnit/Autoload.php';

include_once 'com/livinglogic/ul4/ul4.php';

use \com\livinglogic\ul4\Color as Color;

class UL4Test extends \PHPUnit_Framework_TestCase
{
	function testColor()
	{
		$this->assertEquals("#3f7f7f4c", Color::fromhsv(0.5, 0.5, 0.5, 0.3)->repr());
		$this->assertEquals("#3f7f7f", Color::fromhsv(0.5, 0.5, 0.5)->repr());

		$this->assertEquals("#3fbfbf4c", Color::fromhls(0.5, 0.5, 0.5, 0.3)->repr());
		$this->assertEquals("#3fbfbf", Color::fromhls(0.5, 0.5, 0.5)->repr());

		$this->assertEquals("#3fbfbf4c", Color::fromrepr("#3fbfbf4c")->repr());
		$this->assertEquals("#3fbfbf", Color::fromrepr("#3fbfbf")->repr());

		$this->assertEquals("3fbfbf4c", Color::fromdump("3fbfbf4c")->dump());

		$this->assertEquals("#fff", Color::fromdump("ffffffff")->repr());
	}

	function testLoadDump()
	{
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS12|<?print 42?>S5|printi0|i12|i8|i10|OS24|de.livinglogic.ul4.consti42|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$s2 = \com\livinglogic\ul4on\Utils::dumps($p);
		$this->assertEquals($s1, $s2);

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print x + y?>S5|printi0|i15|i8|i13|OS22|de.livinglogic.ul4.addOS22|de.livinglogic.ul4.varS1|xO^9|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 42, "y" => 3));
		$p->evaluate($c);
		$this->assertEquals(45, $c->getOutput());
	}

	function testFor()
	{
		$s = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS69|<?for (a,b,c) in d?><?printx a?> <?printx b?> <?printx c?><?end for?>S3|fori0|i20|i6|i18|O^3|^4|S3|endi58|i69|i64|i67|LOS25|de.livinglogic.ul4.printxO^3|^4|S6|printxi20|i32|i29|i30|OS22|de.livinglogic.ul4.varS1|aOS23|de.livinglogic.ul4.textO^3|^4|ni32|i33|i32|i33|O^10|O^3|^4|^12|i33|i45|i42|i43|O^14|S1|bO^17|O^3|^4|ni45|i46|i45|i46|O^10|O^3|^4|^12|i46|i58|i55|i56|O^14|S1|c]L^15|^22|^28|]O^14|S1|d";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("d" => array(array(1, 2, 3), array(10, 20, 30))));
		$p->evaluate($c);
		print $c->getOutput() . "\n";
	}

	/*
	function testDictComp()
	{
		$s = 'OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS79|<?code x = { c.upper() : "(" + c + ")" for c in "hurz" if c < "u"}?><?print x?>S4|codei0|i68|i7|i66|S1|xOS27|de.livinglogic.ul4.dictcompOS27|de.livinglogic.ul4.callmethS5|upperOS22|de.livinglogic.ul4.varS1|cL]OS22|de.livinglogic.ul4.addO^17|OS24|de.livinglogic.ul4.constS1|(O^13|^14|O^20|S1|)^14|O^20|S4|hurzOS21|de.livinglogic.ul4.ltO^13|^14|O^20|S1|u';
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext();
		$p->evaluate($c);
		print $c->getOutput() . "\n";
	}
	*/

	function testFunctionAll()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print all(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfuncS3|allLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => array(1, 2, 3)));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		$c = new EvaluationContext(array("x" => array(1, 2, 3, 0)));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
	}

	function testFunctionAny()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print any(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfuncS3|anyLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => array(0,0,0, 3)));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		$c = new EvaluationContext(array("x" => ""));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
	}

	function testFunctionDate()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS32|<?print date(a, b, c, d, e, f)?>S5|printi0|i32|i8|i30|OS27|de.livinglogic.ul4.callfuncS4|dateLOS22|de.livinglogic.ul4.varS1|aO^11|S1|bO^11|S1|cO^11|S1|dO^11|S1|eO^11|S1|f]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("a" => 2001, "b" => 9, "c" => 11, "d" => 14, "e" => 0, "f" => 49));
		$p->evaluate($c);
		print $c->getOutput() . "\n";
	}
}

?>