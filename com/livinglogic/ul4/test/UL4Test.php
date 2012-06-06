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
		$this->assertEquals('[42, 38, 42, 38]', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS17|<?print x and y?>S5|printi0|i17|i8|i15|OS22|de.livinglogic.ul4.and^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 17, "y" => 23));
		$p->evaluate($c);
		$this->assertEquals('17', $c->getOutput());
		$c = new EvaluationContext(array("x" => 42, "y" => 0));
		$p->evaluate($c);
		$this->assertEquals('0', $c->getOutput());
		$c = new EvaluationContext(array("x" => 0, "y" => 42));
		$p->evaluate($c);
		$this->assertEquals('0', $c->getOutput());
		$c = new EvaluationContext(array("x" => 0, "y" => array()));
		$p->evaluate($c);
		$this->assertEquals('[]', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x or y?>S5|printi0|i16|i8|i14|OS21|de.livinglogic.ul4.or^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 17, "y" => 23));
		$p->evaluate($c);
		$this->assertEquals('17', $c->getOutput());
		$c = new EvaluationContext(array("x" => 0, "y" => 23));
		$p->evaluate($c);
		$this->assertEquals('23', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x in y?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.contains^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 17, "y" => array(1, 2, 17, 28)));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 'def', "y" => 'abcdefghi'));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 'def', "y" => 'abcdeghi'));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => 'def', "y" => array('ghi' => 1, "def" => 'gurk', "x" => 'hurz')));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());

		$s1="OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x == y?>S5|printi0|i16|i8|i14|OS21|de.livinglogic.ul4.eq^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 17, "y" => 17));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 1.7, "y" => 1.7));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => True, "y" => True));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 'gurk', "y" => 'gurk'));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 'gurk', "y" => 'gurk2'));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x // y?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.floordiv^2|OS22|de.livinglogic.ul4.int^2|i9|O^9|^2|i4|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 9, "y" => 4));
		$p->evaluate($c);
		$this->assertEquals('2', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x >= y?>S5|printi0|i16|i8|i14|OS21|de.livinglogic.ul4.ge^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 9, "y" => 4));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => True, "y" => 4));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS14|<?print x[y]?>S5|printi0|i14|i8|i12|OS26|de.livinglogic.ul4.getitem^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => "abc", "y" => 1));
		$p->evaluate($c);
		$this->assertEquals('b', $c->getOutput());
		$c = new EvaluationContext(array("x" => array(0, 2), "y" => 1));
		$p->evaluate($c);
		$this->assertEquals('2', $c->getOutput());
		$c = new EvaluationContext(array("x" => array("a" => 0, "b" => "abc"), "y" => "b"));
		$p->evaluate($c);
		$this->assertEquals('abc', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print x > y?>S5|printi0|i15|i8|i13|OS21|de.livinglogic.ul4.gt^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 2, "y" => 1));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 1, "y" => 1));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => 2.0, "y" => 1));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 2.1, "y" => 2.1));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x <= y?>S5|printi0|i16|i8|i14|OS21|de.livinglogic.ul4.le^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 2, "y" => 1));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => 2, "y" => 2));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 2, "y" => 2.0));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print x < y?>S5|printi0|i15|i8|i13|OS21|de.livinglogic.ul4.lt^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 2, "y" => 1));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => 1, "y" => 2));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 2, "y" => 2));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => 2, "y" => 2.0));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print x % y?>S5|printi0|i15|i8|i13|OS22|de.livinglogic.ul4.mod^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 9, "y" => 2));
		$p->evaluate($c);
		$this->assertEquals('1', $c->getOutput());
		$c = new EvaluationContext(array("x" => 9.0, "y" => 2.0));
		$p->evaluate($c);
		$this->assertEquals('1.0', $c->getOutput());
		$c = new EvaluationContext(array("x" => new Color(1,2,3), "y" => new Color(4,5,6,7)));
		$p->evaluate($c);
		$this->assertEquals('#010203', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x != y?>S5|printi0|i16|i8|i14|OS21|de.livinglogic.ul4.ne^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 9, "y" => 2));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 9.0, "y" => 2.0));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => False, "y" => True));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => False, "y" => False));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS20|<?print x not in y?>S5|printi0|i20|i8|i18|OS30|de.livinglogic.ul4.notcontains^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 17, "y" => array(1, 2, 17, 28)));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => 'def', "y" => 'abcdefghi'));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => 'def', "y" => 'abcdeghi'));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 'def', "y" => array('ghi' => 1, "def" => 'gurk', "x" => 'hurz')));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print x / y?>S5|printi0|i15|i8|i13|OS26|de.livinglogic.ul4.truediv^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 17, "y" => 4));
		$p->evaluate($c);
		$this->assertEquals('4.25', $c->getOutput());
		$c = new EvaluationContext(array("x" => 17, "y" => True));
		$p->evaluate($c);
		$this->assertEquals('17', $c->getOutput());
		$c = new EvaluationContext(array("x" => 6.72, "y" => 3.2));
		$p->evaluate($c);
		$this->assertEquals('2.1', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS12|<?print -x?>S5|printi0|i12|i8|i10|OS22|de.livinglogic.ul4.neg^2|OS22|de.livinglogic.ul4.var^2|S1|x";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => 42));
		$p->evaluate($c);
		$this->assertEquals('-42', $c->getOutput());
		$c = new EvaluationContext(array("x" => 4.2));
		$p->evaluate($c);
		$this->assertEquals('-4.2', $c->getOutput());
		$c = new EvaluationContext(array("x" => False));
		$p->evaluate($c);
		$this->assertEquals('0', $c->getOutput());
		$c = new EvaluationContext(array("x" => True));
		$p->evaluate($c);
		$this->assertEquals('-1', $c->getOutput());

		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print not x?>S5|printi0|i15|i8|i13|OS22|de.livinglogic.ul4.not^2|OS22|de.livinglogic.ul4.var^2|S1|x";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => NULL));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => True));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => False));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 0));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 4));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => 0.0));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());
		$c = new EvaluationContext(array("x" => 4.0));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => array(0, 1)));
		$p->evaluate($c);
		$this->assertEquals('False', $c->getOutput());
		$c = new EvaluationContext(array("x" => array()));
		$p->evaluate($c);
		$this->assertEquals('True', $c->getOutput());

		// now()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print now()?>S5|printi0|i15|i8|i13|OS27|de.livinglogic.ul4.callfunc^2|S3|nowL.";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext();
		$p->evaluate($c);
		print "\n" . $c->getOutput() . "\n";
		// utcnow()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS18|<?print utcnow()?>S5|printi0|i18|i8|i16|OS27|de.livinglogic.ul4.callfunc^2|S6|utcnowL.";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext();
		$p->evaluate($c);
		print "\n" . $c->getOutput() . "\n";
		// vars()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print vars()?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S4|varsL.";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext();
		$p->evaluate($c);
		var_dump($c->getOutput());
		// random()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS18|<?print random()?>S5|printi0|i18|i8|i16|OS27|de.livinglogic.ul4.callfunc^2|S6|randomL.";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext();
		$p->evaluate($c);
		var_dump($c->getOutput());
	}
}

?>