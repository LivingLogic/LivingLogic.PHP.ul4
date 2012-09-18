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
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print now()?>S5|printi0|i15|i8|i13|OS27|de.livinglogic.ul4.callfunc^2|S3|nowL]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext();
		$p->evaluate($c);
		print "\n" . $c->getOutput() . "\n";
		// utcnow()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS18|<?print utcnow()?>S5|printi0|i18|i8|i16|OS27|de.livinglogic.ul4.callfunc^2|S6|utcnowL]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext();
		$p->evaluate($c);
		print "\n" . $c->getOutput() . "\n";
		// vars()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print vars()?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S4|varsL]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext();
		$p->evaluate($c);
		var_dump($c->getOutput());
		// random()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS18|<?print random()?>S5|printi0|i18|i8|i16|OS27|de.livinglogic.ul4.callfunc^2|S6|randomL]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext();
		$p->evaluate($c);
		var_dump($c->getOutput());
		// xmlescape()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS22|<?print xmlescape(x)?>S5|printi0|i22|i8|i20|OS27|de.livinglogic.ul4.callfunc^2|S9|xmlescapeLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => "<&>"));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// cvs()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print csv(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|csvLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => "'\"'"));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// str()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print str(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|strLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => new \DateTime()));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// repr()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS17|<?print repr(x)?>S5|printi0|i17|i8|i15|OS27|de.livinglogic.ul4.callfunc^2|S4|reprLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => new \DateTime()));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// int()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS15|<?print int()?>S5|printi0|i15|i8|i13|OS27|de.livinglogic.ul4.callfunc^2|S3|intL]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => new \DateTime()));
		$p->evaluate($c);
		$this->assertEquals("0", $c->getOutput());
		// int(x)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print int(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|intLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => "3"));
		$p->evaluate($c);
		$this->assertEquals("3", $c->getOutput());
		// int(x, y)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS19|<?print int(x, y)?>S5|printi0|i19|i8|i17|OS27|de.livinglogic.ul4.callfunc^2|S3|intLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|y]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => "a", 'y' => 16));
		$p->evaluate($c);
		$this->assertEquals("10", $c->getOutput());
		// float(x)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS18|<?print float(x)?>S5|printi0|i18|i8|i16|OS27|de.livinglogic.ul4.callfunc^2|S5|floatLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 10.10));
		$p->evaluate($c);
		$this->assertEquals("10.1", $c->getOutput());
		// bool(x)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS17|<?print bool(x)?>S5|printi0|i17|i8|i15|OS27|de.livinglogic.ul4.callfunc^2|S4|boolLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 1));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		$c = new EvaluationContext(array('x' => ""));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		// len()
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print len(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|lenLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => array()));
		$p->evaluate($c);
		$this->assertEquals("0", $c->getOutput());
		$c = new EvaluationContext(array('x' => 'abc'));
		$p->evaluate($c);
		$this->assertEquals("3", $c->getOutput());
		// enumerate(x)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS22|<?print enumerate(x)?>S5|printi0|i22|i8|i20|OS27|de.livinglogic.ul4.callfunc^2|S9|enumerateLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => array('a', 'b')));
		$p->evaluate($c);
		var_dump($c->getOutput());
// 		$this->assertEquals("0", $c->getOutput());
		$s1 = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS52|<?for i in enumerate([1, 2])?><?print i?><?end for?>S3|fori0|i30|i6|i28|O^3|^4|S3|endi41|i52|i47|i50|LOS24|de.livinglogic.ul4.printO^3|^4|S5|printi30|i41|i38|i39|OS22|de.livinglogic.ul4.var^11|S1|i]OS27|de.livinglogic.ul4.callfunc^2|S9|enumerateLOS23|de.livinglogic.ul4.list^2|LOS22|de.livinglogic.ul4.int^2|i1|O^24|^2|i2|]]^15|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array());
		$p->evaluate($c);
		var_dump($c->getOutput());
		// enumfl
		$s1 = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS55|<?for i in enumfl([2, 3, 5, 7])?><?print i?><?end for?>S3|fori0|i33|i6|i31|O^3|^4|S3|endi44|i55|i50|i53|LOS24|de.livinglogic.ul4.printO^3|^4|S5|printi33|i44|i41|i42|OS22|de.livinglogic.ul4.var^11|S1|i]OS27|de.livinglogic.ul4.callfunc^2|S6|enumflLOS23|de.livinglogic.ul4.list^2|LOS22|de.livinglogic.ul4.int^2|i2|O^24|^2|i3|O^24|^2|i5|O^24|^2|i7|]]^15|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array());
		$p->evaluate($c);
		var_dump($c->getOutput());
		// isfirstlast
		$s1 = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS60|<?for i in isfirstlast([2, 3, 5, 7])?><?print i?><?end for?>S3|fori0|i38|i6|i36|O^3|^4|S3|endi49|i60|i55|i58|LOS24|de.livinglogic.ul4.printO^3|^4|S5|printi38|i49|i46|i47|OS22|de.livinglogic.ul4.var^11|S1|i]OS27|de.livinglogic.ul4.callfunc^2|S11|isfirstlastLOS23|de.livinglogic.ul4.list^2|LOS22|de.livinglogic.ul4.int^2|i2|O^24|^2|i3|O^24|^2|i5|O^24|^2|i7|]]^15|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array());
		$p->evaluate($c);
		var_dump($c->getOutput());
		// isfirst
		$s1 = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS56|<?for i in isfirst([2, 3, 5, 7])?><?print i?><?end for?>S3|fori0|i34|i6|i32|O^3|^4|S3|endi45|i56|i51|i54|LOS24|de.livinglogic.ul4.printO^3|^4|S5|printi34|i45|i42|i43|OS22|de.livinglogic.ul4.var^11|S1|i]OS27|de.livinglogic.ul4.callfunc^2|S7|isfirstLOS23|de.livinglogic.ul4.list^2|LOS22|de.livinglogic.ul4.int^2|i2|O^24|^2|i3|O^24|^2|i5|O^24|^2|i7|]]^15|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array());
		$p->evaluate($c);
		var_dump($c->getOutput());
		// islast
		$s1 = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS55|<?for i in islast([2, 3, 5, 7])?><?print i?><?end for?>S3|fori0|i33|i6|i31|O^3|^4|S3|endi44|i55|i50|i53|LOS24|de.livinglogic.ul4.printO^3|^4|S5|printi33|i44|i41|i42|OS22|de.livinglogic.ul4.var^11|S1|i]OS27|de.livinglogic.ul4.callfunc^2|S6|islastLOS23|de.livinglogic.ul4.list^2|LOS22|de.livinglogic.ul4.int^2|i2|O^24|^2|i3|O^24|^2|i5|O^24|^2|i7|]]^15|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array());
		$p->evaluate($c);
		var_dump($c->getOutput());
		// isnone
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS19|<?print isnone(x)?>S5|printi0|i19|i8|i17|OS27|de.livinglogic.ul4.callfunc^2|S6|isnoneLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => null));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// isstr
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS18|<?print isstr(x)?>S5|printi0|i18|i8|i16|OS27|de.livinglogic.ul4.callfunc^2|S5|isstrLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => null));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => 'abc'));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// isint
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS18|<?print isint(x)?>S5|printi0|i18|i8|i16|OS27|de.livinglogic.ul4.callfunc^2|S5|isintLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		$c = new EvaluationContext(array('x' => null));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => 'abc'));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		// isfloat
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS20|<?print isfloat(x)?>S5|printi0|i20|i8|i18|OS27|de.livinglogic.ul4.callfunc^2|S7|isfloatLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => null));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => 3.141592));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// isbool
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS19|<?print isbool(x)?>S5|printi0|i19|i8|i17|OS27|de.livinglogic.ul4.callfunc^2|S6|isboolLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => null));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => False));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// isdate
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS19|<?print isdate(x)?>S5|printi0|i19|i8|i17|OS27|de.livinglogic.ul4.callfunc^2|S6|isdateLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => null));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => new \DateTime()));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// islist
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS19|<?print islist(x)?>S5|printi0|i19|i8|i17|OS27|de.livinglogic.ul4.callfunc^2|S6|islistLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => null));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => array(1, 1, 2, 3, 5, 8, 13)));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// isdict
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS19|<?print isdict(x)?>S5|printi0|i19|i8|i17|OS27|de.livinglogic.ul4.callfunc^2|S6|isdictLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => null));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => array('1, 1, 2, 3, 5, 8, 13' => 353)));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// istemplate
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS23|<?print istemplate(x)?>S5|printi0|i23|i8|i21|OS27|de.livinglogic.ul4.callfunc^2|S10|istemplateLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => new InterpretedTemplate()));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// iscolor
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS20|<?print iscolor(x)?>S5|printi0|i20|i8|i18|OS27|de.livinglogic.ul4.callfunc^2|S7|iscolorLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array('x' => new Color(1, 2, 3, 4)));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		// chr
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print chr(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|chrLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 65));
		$p->evaluate($c);
		$this->assertEquals("A", $c->getOutput());
		// ord
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print ord(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|ordLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
// 		$c = new EvaluationContext(array('x' => "й"));  // TODO  1081
		$c = new EvaluationContext(array('x' => "A"));
		$p->evaluate($c);
		$this->assertEquals("65", $c->getOutput());
		// hex
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print hex(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|hexLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => -40));
		$p->evaluate($c);
		$this->assertEquals("-0x28", $c->getOutput());
		// oct
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print oct(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|octLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => -41));
		$p->evaluate($c);
		$this->assertEquals("-0o51", $c->getOutput());
		// bin
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print bin(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|binLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => -21));
		$p->evaluate($c);
		$this->assertEquals("-0b10101", $c->getOutput());
		// abs
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print abs(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfunc^2|S3|absLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => -21));
		$p->evaluate($c);
		$this->assertEquals("21", $c->getOutput());
		// range
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS24|<?print range(x, y, z)?>S5|printi0|i24|i8|i22|OS27|de.livinglogic.ul4.callfunc^2|S5|rangeLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|yO^11|^2|S1|z]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 1, 'y' => 5, 'z' => 2));
		$p->evaluate($c);
		$this->assertEquals("[1, 3]", $c->getOutput());
		// sorted
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS19|<?print sorted(x)?>S5|printi0|i19|i8|i17|OS27|de.livinglogic.ul4.callfunc^2|S6|sortedLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => array('b' => 1, 'a' => 0)));
		$p->evaluate($c);
		$this->assertEquals('["a", "b"]', $c->getOutput());
		$c = new EvaluationContext(array('x' => array(1, 0)));
		$p->evaluate($c);
		$this->assertEquals('[0, 1]', $c->getOutput());
		$c = new EvaluationContext(array('x' => "cab"));
		$p->evaluate($c);
		$this->assertEquals('["a", "b", "c"]', $c->getOutput());
		// type
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS17|<?print type(x)?>S5|printi0|i17|i8|i15|OS27|de.livinglogic.ul4.callfunc^2|S4|typeLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 1));
		$p->evaluate($c);
		$this->assertEquals('int', $c->getOutput());
		$c = new EvaluationContext(array('x' => array("a" => 2)));
		$p->evaluate($c);
		$this->assertEquals('dict', $c->getOutput());
		// get('x')
      $s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS18|<?print get('x')?>S5|printi0|i18|i8|i16|OS27|de.livinglogic.ul4.callfunc^2|S3|getLOS22|de.livinglogic.ul4.str^2|S1|x]";
      $p = \com\livinglogic\ul4on\Utils::loads($s1);
      $c = new EvaluationContext(array('x' => 1));
      $p->evaluate($c);
      $this->assertEquals('1', $c->getOutput());
      // get('x', 'default')
      $s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS29|<?print get('x', 'default')?>S5|printi0|i29|i8|i27|OS27|de.livinglogic.ul4.callfunc^2|S3|getLOS22|de.livinglogic.ul4.str^2|S1|xO^11|^2|S7|default]";
      $p = \com\livinglogic\ul4on\Utils::loads($s1);
      $c = new EvaluationContext(array('a' => 1));
      $p->evaluate($c);
      $this->assertEquals('default', $c->getOutput());
      // asjson
      $s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS19|<?print asjson(x)?>S5|printi0|i19|i8|i17|OS27|de.livinglogic.ul4.callfunc^2|S6|asjsonLOS22|de.livinglogic.ul4.var^2|S1|x]";
      $p = \com\livinglogic\ul4on\Utils::loads($s1);
      $c = new EvaluationContext(array('x' => 1));
      $p->evaluate($c);
      $this->assertEquals('1', $c->getOutput());
      $c = new EvaluationContext(array('x' => array("a" => 1, "b" => 2)));
      $p->evaluate($c);
      $this->assertEquals('{"a": 1, "b": 2}', $c->getOutput());
      $c = new EvaluationContext(array('x' => new Color(1, 2, 3, 4)));
      $p->evaluate($c);
      $this->assertEquals('ul4.Color.create(1, 2, 3, 4)', $c->getOutput());
      // asul4on
      $s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS20|<?print asul4on(x)?>S5|printi0|i20|i8|i18|OS27|de.livinglogic.ul4.callfunc^2|S7|asul4onLOS22|de.livinglogic.ul4.var^2|S1|x]";
      $p = \com\livinglogic\ul4on\Utils::loads($s1);
      $c = new EvaluationContext(array('x' => 1));
      $p->evaluate($c);
      $this->assertEquals('i1|', $c->getOutput());
		// reversed(x)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS21|<?print reversed(x)?>S5|printi0|i21|i8|i19|OS27|de.livinglogic.ul4.callfunc^2|S8|reversedLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => array(3, 2, 1, 0)));
		$p->evaluate($c);
		var_dump($c->getOutput());
		$c = new EvaluationContext(array('x' => "cba"));
		$p->evaluate($c);
		var_dump($c->getOutput());
      // randrange(x)
      $s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS22|<?print randrange(x)?>S5|printi0|i22|i8|i20|OS27|de.livinglogic.ul4.callfunc^2|S9|randrangeLOS22|de.livinglogic.ul4.var^2|S1|x]";
      $p = \com\livinglogic\ul4on\Utils::loads($s1);
      $c = new EvaluationContext(array('x' => 10));
      $p->evaluate($c);
      var_dump($c->getOutput());
		// randrange(x, y)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS25|<?print randrange(x, y)?>S5|printi0|i25|i8|i23|OS27|de.livinglogic.ul4.callfunc^2|S9|randrangeLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|y]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 10, 'y' => 20));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// randrange(x, y, z)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS28|<?print randrange(x, y, z)?>S5|printi0|i28|i8|i26|OS27|de.livinglogic.ul4.callfunc^2|S9|randrangeLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|yO^11|^2|S1|z]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 10, 'y' => 20, 'z' => 2));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// randchoice(x)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS23|<?print randchoice(x)?>S5|printi0|i23|i8|i21|OS27|de.livinglogic.ul4.callfunc^2|S10|randchoiceLOS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => "abcdefg"));
		$p->evaluate($c);
		var_dump($c->getOutput());
		$c = new EvaluationContext(array('x' => array(0, 1, 2, 3, 4, 5)));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// format(x)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS22|<?print format(x, y)?>S5|printi0|i22|i8|i20|OS27|de.livinglogic.ul4.callfunc^2|S6|formatLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|y]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => new \DateTime(), 'y' => "%a"));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// zip(x)
		$s1 = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS65|<?for i in zip(x, y)?><?print i[0]?> - <?print i[1]?>;<?end for?>S3|fori0|i22|i6|i20|O^3|^4|S3|endi54|i65|i60|i63|LOS24|de.livinglogic.ul4.printO^3|^4|S5|printi22|i36|i30|i34|OS26|de.livinglogic.ul4.getitem^11|OS22|de.livinglogic.ul4.var^11|S1|iOS22|de.livinglogic.ul4.int^11|i0|OS23|de.livinglogic.ul4.textO^3|^4|ni36|i39|i36|i39|O^10|O^3|^4|^12|i39|i53|i47|i51|O^14|^24|O^16|^24|^17|O^19|^24|i1|O^21|O^3|^4|ni53|i54|i53|i54|]OS27|de.livinglogic.ul4.callfunc^2|S3|zipLO^16|^2|S1|xO^16|^2|S1|y]^17|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => "gurk", 'y' => "hurz"));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// rgb(x, y, z)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS22|<?print rgb(x, y, z)?>S5|printi0|i22|i8|i20|OS27|de.livinglogic.ul4.callfunc^2|S3|rgbLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|yO^11|^2|S1|z]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 0.1, 'y' => 0.2, 'z' => 0.3));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// rgb(x, y, z, a)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS25|<?print rgb(x, y, z, a)?>S5|printi0|i25|i8|i23|OS27|de.livinglogic.ul4.callfunc^2|S3|rgbLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|yO^11|^2|S1|zO^11|^2|S1|a]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 0.1, 'y' => 0.2, 'z' => 0.3, 'a' => 0.4));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// hls(x,y,z)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS22|<?print hls(x, y, z)?>S5|printi0|i22|i8|i20|OS27|de.livinglogic.ul4.callfunc^2|S3|hlsLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|yO^11|^2|S1|z]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 0.1, 'y' => 0.2, 'z' => 0.3));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// hls(x, y, z, a)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS25|<?print hls(x, y, z, a)?>S5|printi0|i25|i8|i23|OS27|de.livinglogic.ul4.callfunc^2|S3|hlsLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|yO^11|^2|S1|zO^11|^2|S1|a]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 0.1, 'y' => 0.2, 'z' => 0.3, 'a' => 0.4));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// hsv(x, y, z)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS22|<?print hsv(x, y, z)?>S5|printi0|i22|i8|i20|OS27|de.livinglogic.ul4.callfunc^2|S3|hsvLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|yO^11|^2|S1|z]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 0.1, 'y' => 0.2, 'z' => 0.3));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// hsv(x, y, z, a)
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS25|<?print hsv(x, y, z, a)?>S5|printi0|i25|i8|i23|OS27|de.livinglogic.ul4.callfunc^2|S3|hsvLOS22|de.livinglogic.ul4.var^2|S1|xO^11|^2|S1|yO^11|^2|S1|zO^11|^2|S1|a]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 0.1, 'y' => 0.2, 'z' => 0.3, 'a' => 0.4));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// foru
		$s1 = "OS23|de.livinglogic.ul4.foruOS27|de.livinglogic.ul4.locationS64|<?for (i, j) in zip(x, y)?><?print i?> - <?print j?>;<?end for?>S3|fori0|i27|i6|i25|O^3|^4|S3|endi53|i64|i59|i62|LOS24|de.livinglogic.ul4.printO^3|^4|S5|printi27|i38|i35|i36|OS22|de.livinglogic.ul4.var^11|S1|iOS23|de.livinglogic.ul4.textO^3|^4|ni38|i41|i38|i41|O^10|O^3|^4|^12|i41|i52|i49|i50|O^14|^20|S1|jO^17|O^3|^4|ni52|i53|i52|i53|]OS27|de.livinglogic.ul4.callfunc^2|S3|zipLO^14|^2|S1|xO^14|^2|S1|y]L^15|^22|]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => "gurk", 'y' => "hurz"));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// if (true)
		$s1 = "OS23|de.livinglogic.ul4.ieieOS27|de.livinglogic.ul4.locationS22|<?if (x)?>ja<?end if?>S2|ifi0|i10|i5|i8|O^3|^4|S3|endi12|i22|i18|i20|LOS21|de.livinglogic.ul4.if^2|^6|LOS23|de.livinglogic.ul4.textO^3|^4|ni10|i12|i10|i12|]OS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => true));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// if (false)
		$s1 = "OS23|de.livinglogic.ul4.ieieOS27|de.livinglogic.ul4.locationS22|<?if (x)?>ja<?end if?>S2|ifi0|i10|i5|i8|O^3|^4|S3|endi12|i22|i18|i20|LOS21|de.livinglogic.ul4.if^2|^6|LOS23|de.livinglogic.ul4.textO^3|^4|ni10|i12|i10|i12|]OS22|de.livinglogic.ul4.var^2|S1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => false));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// if elif else
		$s1 = "OS23|de.livinglogic.ul4.ieieOS27|de.livinglogic.ul4.locationS52|<?if x == 0?>0<?elif x == 1?>1<?else?>else<?end if?>S2|ifi0|i13|i5|i11|O^3|^4|S3|endi42|i52|i48|i50|LOS21|de.livinglogic.ul4.if^2|nLOS23|de.livinglogic.ul4.textO^3|^4|ni13|i14|i13|i14|]OS21|de.livinglogic.ul4.eq^2|OS22|de.livinglogic.ul4.var^2|S1|xOS22|de.livinglogic.ul4.int^2|i0|OS23|de.livinglogic.ul4.elifO^3|^4|S4|elifi14|i29|i21|i27|nLO^13|O^3|^4|ni29|i30|i29|i30|]O^16|^24|O^18|^24|^19|O^21|^24|i1|OS23|de.livinglogic.ul4.elseO^3|^4|S4|elsei30|i38|i36|i36|^6|LO^13|O^3|^4|ni38|i42|i38|i42|]]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 0));
		$p->evaluate($c);
		var_dump($c->getOutput());
		$c = new EvaluationContext(array('x' => 1));
		$p->evaluate($c);
		var_dump($c->getOutput());
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// break
		$s1 = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS75|<?for i in x?><?if i > 1?><?break?><?else?><?print i?><?end if?><?end for?>S3|fori0|i14|i6|i12|O^3|^4|S3|endi64|i75|i70|i73|LOS23|de.livinglogic.ul4.ieieO^3|^4|S2|ifi14|i26|i19|i24|O^3|^4|^7|i54|i64|i60|i62|LOS21|de.livinglogic.ul4.if^11|nLOS24|de.livinglogic.ul4.breakO^3|^4|S5|breaki26|i35|i33|i33|]OS21|de.livinglogic.ul4.gt^11|OS22|de.livinglogic.ul4.var^11|S1|iOS22|de.livinglogic.ul4.int^11|i1|OS23|de.livinglogic.ul4.elseO^3|^4|S4|elsei35|i43|i41|i41|^13|LOS24|de.livinglogic.ul4.printO^3|^4|S5|printi43|i54|i51|i52|O^25|^36|^26|]]]O^25|^2|S1|x^26|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => array(0, 1, 2)));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// continue
		$s1 = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS79|<?for i in x?><?if i <= 1?><?print i?><?else?><?continue?><?end if?><?end for?>S3|fori0|i14|i6|i12|O^3|^4|S3|endi68|i79|i74|i77|LOS23|de.livinglogic.ul4.ieieO^3|^4|S2|ifi14|i27|i19|i25|O^3|^4|^7|i58|i68|i64|i66|LOS21|de.livinglogic.ul4.if^11|nLOS24|de.livinglogic.ul4.printO^3|^4|S5|printi27|i38|i35|i36|OS22|de.livinglogic.ul4.var^20|S1|i]OS21|de.livinglogic.ul4.le^11|O^23|^11|^24|OS22|de.livinglogic.ul4.int^11|i1|OS23|de.livinglogic.ul4.elseO^3|^4|S4|elsei38|i46|i44|i44|^13|LOS27|de.livinglogic.ul4.continueO^3|^4|S8|continuei46|i58|i56|i56|]]]O^23|^2|S1|x^24|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => array(0, 1, 2)));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// addvar
		$s1 = "OS25|de.livinglogic.ul4.addvarOS27|de.livinglogic.ul4.locationS27|<?code x += 42?><?print x?>S4|codei0|i16|i7|i14|S1|xOS22|de.livinglogic.ul4.int^2|i42|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2));
		$p->evaluate($c);
		var_dump($c->get("x"));
		// x // y
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x // y?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.floordiv^2|OS22|de.livinglogic.ul4.var^2|S1|xO^9|^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 9, 'y' => 2));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// x %= y
		$s1 = "OS25|de.livinglogic.ul4.modvarOS27|de.livinglogic.ul4.locationS15|<?code x %= y?>S4|codei0|i15|i7|i13|S1|xOS22|de.livinglogic.ul4.var^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 3, 'y' => 2));
		$p->evaluate($c);
		var_dump($c->get("x"));
		// x *= y
		$s1 = "OS25|de.livinglogic.ul4.mulvarOS27|de.livinglogic.ul4.locationS15|<?code x *= y?>S4|codei0|i15|i7|i13|S1|xOS22|de.livinglogic.ul4.var^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 3, 'y' => 2));
		$p->evaluate($c);
		var_dump($c->get("x"));
		// x = y
		$s1 = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS14|<?code x = y?>S4|codei0|i14|i7|i12|S1|xOS22|de.livinglogic.ul4.var^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 3, 'y' => 2));
		$p->evaluate($c);
		var_dump($c->get("x"));
		// x -= y
		$s1 = "OS25|de.livinglogic.ul4.subvarOS27|de.livinglogic.ul4.locationS15|<?code x -= y?>S4|codei0|i15|i7|i13|S1|xOS22|de.livinglogic.ul4.var^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2, 'y' => 3));
		$p->evaluate($c);
		var_dump($c->get("x"));
		// x /= y
		$s1 = "OS29|de.livinglogic.ul4.truedivvarOS27|de.livinglogic.ul4.locationS15|<?code x /= y?>S4|codei0|i15|i7|i13|S1|xOS22|de.livinglogic.ul4.var^2|S1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 2, 'y' => 3));
		$p->evaluate($c);
		var_dump($c->get("x"));
		// dict
		$s1 = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS19|<?code a = {x: y}?>S4|codei0|i19|i7|i17|S1|aOS23|de.livinglogic.ul4.dict^2|LLOS22|de.livinglogic.ul4.var^2|S1|xO^12|^2|S1|y]]";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => 42, 'y' => 'def'));
		$p->evaluate($c);
		var_dump($c->get("a"));
		// getAttr
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS13|<?print x.y?>S5|printi0|i13|i8|i11|OS26|de.livinglogic.ul4.getattr^2|OS22|de.livinglogic.ul4.var^2|S1|xS1|y";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array("x" => array("a" => "2", "b" => "1", "y" => "42")));
		$p->evaluate($c);
		$this->assertEquals('42', $c->getOutput());
		// getSlice
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print x[1:3]?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.getslice^2|OS22|de.livinglogic.ul4.var^2|S1|xOS22|de.livinglogic.ul4.int^2|i1|O^12|^2|i3|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array('x' => array(0, 1, 2, 3, 4)));
		$p->evaluate($c);
		var_dump($c->getOutput());
		// LoadColor
		$s1 = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS15|<?code c=#fed?>S4|codei0|i15|i7|i13|S1|cOS24|de.livinglogic.ul4.color^2|Cffeeddff";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$p->evaluate($c);
		var_dump($c->get("c"));
		// LoadDate    DATE: '@' '(' DIGIT DIGIT DIGIT DIGIT '-' DIGIT DIGIT '-' DIGIT DIGIT ('T' TIME?)? ')';
		$s1 = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS24|<?code c=@(2012-09-18)?>S4|codei0|i24|i7|i22|S1|cOS23|de.livinglogic.ul4.date^2|T20120918000000000000";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$p->evaluate($c);
		var_dump($c->get("c"));
		// LoadFalse
		$s1 = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS16|<?code c=False?>S4|codei0|i16|i7|i14|S1|cOS24|de.livinglogic.ul4.false^2|";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$p->evaluate($c);
		var_dump($c->get("c"));

	}
}

?>