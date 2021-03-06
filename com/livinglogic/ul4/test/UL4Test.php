<?php
namespace com\livinglogic\ul4\test;

use com\livinglogic\ul4\InterpretedTemplate;

use com\livinglogic\ul4\EvaluationContext;

require_once 'PHPUnit/Autoload.php';

include_once 'com/livinglogic/ul4/ul4.php';

use \com\livinglogic\ul4\Color as Color;
use \com\livinglogic\ul4\UndefinedIndex as UndefinedIndex;
use \com\livinglogic\ul4\UndefinedKey as UndefinedKey;
use \com\livinglogic\ul4\UndefinedVariable as UndefinedVariable;
use \com\livinglogic\ul4\MonthDelta as MonthDelta;
use \com\livinglogic\ul4\TimeDelta as TimeDelta;

class UL4Test extends \PHPUnit_Framework_TestCase
{
	function testNew()
	{
		$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationOS27|de.livinglogic.ul4.templateS2|25S35|<?print @(2000-02-29).isoformat()?>nbTS2|<?S2|?>ni0|i0|nL^0|]^7|S5|printi0|i35|i8|i33|i8|i33|OS27|de.livinglogic.ul4.callmeth^2|i8|i33|S9|isoformatOS24|de.livinglogic.ul4.const^2|i8|i21|Z20000229000000000000L]^18|nn";
		$p = \com\livinglogic\ul4on\Utils::loads($s1);
		$c = new EvaluationContext(array());
		$p->evaluate($c);
// 		$this->assertEquals(45, $c->getOutput());
		print $c->getOutput() . "\n";
	}
/*
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

		$md = new \com\livinglogic\ul4\MonthDelta(2);
		$s1 = $md->repr();
		$d = \com\livinglogic\ul4on\Utils::dumps($md);
		$l = \com\livinglogic\ul4on\Utils::loads($d);
		$s2 = $l->repr();
		$this->assertEquals($s1, $s2);

		$md = new \com\livinglogic\ul4\TimeDelta(2, 3, 4);
		$s1 = $md->repr();
		$d = \com\livinglogic\ul4on\Utils::dumps($md);
		$l = \com\livinglogic\ul4on\Utils::loads($d);
		$s2 = $l->repr();
		$this->assertEquals($s1, $s2);
	}

	function testFor()
	{
		$s = "OS22|de.livinglogic.ul4.forOS27|de.livinglogic.ul4.locationS69|<?for (a,b,c) in d?><?printx a?> <?printx b?> <?printx c?><?end for?>S3|fori0|i20|i6|i18|O^3|^4|S3|endi58|i69|i64|i67|LOS25|de.livinglogic.ul4.printxO^3|^4|S6|printxi20|i32|i29|i30|OS22|de.livinglogic.ul4.varS1|aOS23|de.livinglogic.ul4.textO^3|^4|ni32|i33|i32|i33|O^10|O^3|^4|^12|i33|i45|i42|i43|O^14|S1|bO^17|O^3|^4|ni45|i46|i45|i46|O^10|O^3|^4|^12|i46|i58|i55|i56|O^14|S1|c]L^15|^22|^28|]O^14|S1|d";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("d" => array(array(1, 2, 3), array(10, 20, 30))));
		$p->evaluate($c);
		print $c->getOutput() . "\n";
	}

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

	function testDictComp()
	{
		$s = 'OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS79|<?code x = { c.upper() : "(" + c + ")" for c in "hurz" if c < "u"}?><?print x?>S4|codei0|i68|i7|i66|S1|xOS27|de.livinglogic.ul4.dictcompOS27|de.livinglogic.ul4.callmethS5|upperOS22|de.livinglogic.ul4.varS1|cL]OS22|de.livinglogic.ul4.addO^17|OS24|de.livinglogic.ul4.constS1|(O^13|^14|O^20|S1|)^14|O^20|S4|hurzOS21|de.livinglogic.ul4.ltO^13|^14|O^20|S1|u';
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext();
		$p->evaluate($c);
		$x = $c->get("x");
		$this->assertEquals($x, array("H" => "(h)", "R" => "(r)"));
	}

	function testFromJson()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS21|<?print fromjson(x)?>S5|printi0|i21|i8|i19|OS27|de.livinglogic.ul4.callfuncS8|fromjsonLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => '{"people":{"person":{"firstName":"Guillame","lastName":"Laforge","address":{"city":"Paris","country":"France","zip":12345},"married":true,"conferences":["JavaOne","Gr8conf"]}}}'));
		$p->evaluate($c);
		print $c->getOutput() . "\n";
		$s = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS24|<?code y = fromjson(x)?>S4|codei0|i24|i7|i22|S1|yOS27|de.livinglogic.ul4.callfuncS8|fromjsonLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => '{"people":{"person":{"firstName":"Guillame","lastName":"Laforge","address":{"city":"Paris","country":"France","zip":12345},"married":true,"conferences":["JavaOne","Gr8conf"]}}}'));
		$p->evaluate($c);
		$y = $c->get("y");
		$this->assertEquals($y["people"]["person"]["firstName"], "Guillame");
	}

	function testFromUL4ON()
	{
		$obj = array("a" => 1, "b" => array("b1" => 42, "b2" => 2.71), "c" => "gurk");
		$dump = \com\livinglogic\ul4on\Utils::dumps($obj);
		$s = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS25|<?code x = fromul4on(x)?>S4|codei0|i25|i7|i23|S1|xOS27|de.livinglogic.ul4.callfuncS9|fromul4onLOS22|de.livinglogic.ul4.var^6|]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => $dump));
		$p->evaluate($c);
		$x = $c->get("x");
		$this->assertEquals(1, $x["a"]);
		$this->assertEquals(42, $x["b"]["b1"]);
		$this->assertEquals("gurk", $x["c"]);
	}

	function testIsDefined()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS22|<?print isdefined(x)?>S5|printi0|i22|i8|i20|OS27|de.livinglogic.ul4.callfuncS9|isdefinedLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => new UndefinedIndex(0)));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array("x" => new UndefinedKey('b')));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array("x" => new UndefinedVariable('b')));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array("x" => 42));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
	}

	function testIsUndefined()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS24|<?print isundefined(x)?>S5|printi0|i24|i8|i22|OS27|de.livinglogic.ul4.callfuncS11|isundefinedLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => new UndefinedIndex(0)));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		$c = new EvaluationContext(array("x" => new UndefinedKey('b')));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		$c = new EvaluationContext(array("x" => new UndefinedVariable('b')));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
		$c = new EvaluationContext(array("x" => 42));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
	}

	function testIsMonthDelta()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS25|<?print ismonthdelta(x)?>S5|printi0|i25|i8|i23|OS27|de.livinglogic.ul4.callfuncS12|ismonthdeltaLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => new UndefinedIndex(0)));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array("x" => new MonthDelta(2)));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
	}

	function testIsTimeDelta()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS24|<?print istimedelta(x)?>S5|printi0|i24|i8|i22|OS27|de.livinglogic.ul4.callfuncS11|istimedeltaLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => new UndefinedIndex(0)));
		$p->evaluate($c);
		$this->assertEquals("False", $c->getOutput());
		$c = new EvaluationContext(array("x" => new TimeDelta(2, 3, 4)));
		$p->evaluate($c);
		$this->assertEquals("True", $c->getOutput());
	}

	function testMax()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print max(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfuncS3|maxLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => array(0, 4, 1, 2)));
		$p->evaluate($c);
		$this->assertEquals("4", $c->getOutput());
	}

	function testMin()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS16|<?print min(x)?>S5|printi0|i16|i8|i14|OS27|de.livinglogic.ul4.callfuncS3|minLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => array(4, 1, 2)));
		$p->evaluate($c);
		$this->assertEquals("1", $c->getOutput());
	}

	function testMonthDelta()
	{
		$s = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS26|<?code x = monthdelta(x)?>S4|codei0|i26|i7|i24|S1|xOS27|de.livinglogic.ul4.callfuncS10|monthdeltaLOS22|de.livinglogic.ul4.var^6|]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => 4));
		$p->evaluate($c);
		$x = $c->get("x");
		$this->assertEquals(4, $x->getMonths());
	}

	function testTimeDelta()
	{
		$s = "OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS31|<?code x = timedelta(a, b, c)?>S4|codei0|i31|i7|i29|S1|xOS27|de.livinglogic.ul4.callfuncS9|timedeltaLOS22|de.livinglogic.ul4.varS1|aO^12|S1|bO^12|S1|c]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("a" => 1, "b" => 2, "c" => 3));
		$p->evaluate($c);
		$x = $c->get("x");
		$this->assertEquals(1, $x->getDays());
		$this->assertEquals(2, $x->getSeconds());
		$this->assertEquals(3, $x->getMicroseconds());
	}

	function testURLQuote()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS21|<?print urlquote(x)?>S5|printi0|i21|i8|i19|OS27|de.livinglogic.ul4.callfuncS8|urlquoteLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => "abc def"));
		$p->evaluate($c);
		$this->assertEquals("abc+def", $c->getOutput());
	}

	function testURLUnquote()
	{
		$s = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS23|<?print urlunquote(x)?>S5|printi0|i23|i8|i21|OS27|de.livinglogic.ul4.callfuncS10|urlunquoteLOS22|de.livinglogic.ul4.varS1|x]";
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array("x" => "abc+def"));
		$p->evaluate($c);
		$this->assertEquals("abc def", $c->getOutput());
	}

	function testGeneratorExpression()
	{
		$s = 'OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS75|<?print ", ".join("(" + str(c) + ")" for c in [1, 2, 3, 4] if c % 2 == 0)?>S5|printi0|i75|i8|i73|OS27|de.livinglogic.ul4.callmethS4|joinOS24|de.livinglogic.ul4.constS2|, LOS26|de.livinglogic.ul4.genexprOS22|de.livinglogic.ul4.addO^16|O^10|S1|(OS27|de.livinglogic.ul4.callfuncS3|strLOS22|de.livinglogic.ul4.varS1|c]O^10|S1|)^26|OS23|de.livinglogic.ul4.listLO^10|i1|O^10|i2|O^10|i3|O^10|i4|]OS21|de.livinglogic.ul4.eqOS22|de.livinglogic.ul4.modO^25|^26|O^10|i2|O^10|i0|]';
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array());
		$p->evaluate($c);
 		$this->assertEquals("(2), (4)", $c->getOutput());
	}

	function testListComprehension()
	{
		$s = 'OS27|de.livinglogic.ul4.storevarOS27|de.livinglogic.ul4.locationS63|<?code x = ["(" + c.upper() + ")" for c in "hurz" if c < "u"]?>S4|codei0|i63|i7|i61|S1|xOS27|de.livinglogic.ul4.listcompOS22|de.livinglogic.ul4.addO^10|OS24|de.livinglogic.ul4.constS1|(OS27|de.livinglogic.ul4.callmethS5|upperOS22|de.livinglogic.ul4.varS1|cL]O^13|S1|)^20|O^13|S4|hurzOS21|de.livinglogic.ul4.ltO^19|^20|O^13|S1|u';
		$p = \com\livinglogic\ul4on\Utils::loads($s);
		$c = new EvaluationContext(array());
		$p->evaluate($c);
		$x = $c->get("x");
		$this->assertEquals("(H)", $x[0]);
	}
	*/
}

?>
