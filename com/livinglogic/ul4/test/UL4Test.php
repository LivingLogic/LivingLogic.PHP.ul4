<?php
namespace com\livinglogic\ul4\test;

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
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS12|<?print 42?>S5|printi0|i12|i8|i10|OS22|de.livinglogic.ul4.int^2|i42|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$s2 = \com\livinglogic\ul4on\Utils::dumps($p);
		$this->assertEquals($s1, $s2);

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print 42 + x?>S5|printi0|i16|i8|i14|OS22|de.livinglogic.ul4.add^2|OS22|de.livinglogic.ul4.int^2|i42|OS22|de.livinglogic.ul4.var^2|S1|x";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 3));
		$p->evaluate($c);
		$this->assertEquals("45", "" . $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print y + x?>S5|printi0|i15|i8|i13|OS22|de.livinglogic.ul4.add^2|OS22|de.livinglogic.ul4.var^2|S1|yO^9|^2|S1|x";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 42, "y" => 3));
		$p->evaluate($c);
		$this->assertEquals(45, $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print y - x?>S5|printi0|i15|i8|i13|OS22|de.livinglogic.ul4.sub^2|OS22|de.livinglogic.ul4.var^2|S1|yO^9|^2|S1|x";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 3, "y" => 3));
		$p->evaluate($c);
		$this->assertEquals(0, $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print x * y?>S5|printi0|i15|i8|i13|OS22|de.livinglogic.ul4.mul^2|OS22|de.livinglogic.ul4.var^2|S1|yO^9|^2|S1|x";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 3, "y" => 4));
		$p->evaluate($c);
		$this->assertEquals(12, $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print x * y?>S5|printi0|i15|i8|i13|OS22|de.livinglogic.ul4.mul^2|OS22|de.livinglogic.ul4.var^2|S1|yO^9|^2|S1|x";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => "ab3", "y" => 2));
		$p->evaluate($c);
		$this->assertEquals("ab3ab3", $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print x * y?>S5|printi0|i15|i8|i13|OS22|de.livinglogic.ul4.mul^2|OS22|de.livinglogic.ul4.var^2|S1|yO^9|^2|S1|x";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$x = array(42, 38);
		$c = new EvaluationContext(array("x" => $x, "y" => 2));
		$p->evaluate($c);
		$y = array();
		$y = array_merge($y, $x);
		$y = array_merge($y, $x);
		$this->assertEquals('[42 ,38 ,42 ,38]', $c->getOutput());

	}
}

?>