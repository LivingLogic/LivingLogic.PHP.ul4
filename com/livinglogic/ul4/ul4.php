<?php

include_once 'com/livinglogic/ul4on/Decoder.php';
include_once 'com/livinglogic/ul4on/Encoder.php';
include_once 'com/livinglogic/ul4on/UL4ONSerializable.php';
include_once 'com/livinglogic/ul4on/Utils.php';

include_once 'com/livinglogic/ul4/Execution.php';
include_once 'com/livinglogic/ul4/Executor.php';
include_once 'com/livinglogic/ul4/FilteredIterator.php';

include_once 'com/livinglogic/ul4/AST.php';
include_once 'com/livinglogic/ul4/Tag.php';
include_once 'com/livinglogic/ul4/Const.php';
include_once 'com/livinglogic/ul4/Text.php';
include_once 'com/livinglogic/ul4/Block.php';
include_once 'com/livinglogic/ul4/Break.php';
include_once 'com/livinglogic/ul4/ChangeVar.php';
include_once 'com/livinglogic/ul4/AddVar.php';
include_once 'com/livinglogic/ul4/FloorDivVar.php';
include_once 'com/livinglogic/ul4/ModVar.php';
include_once 'com/livinglogic/ul4/MulVar.php';
include_once 'com/livinglogic/ul4/StoreVar.php';
include_once 'com/livinglogic/ul4/SubVar.php';
include_once 'com/livinglogic/ul4/TrueDivVar.php';
include_once 'com/livinglogic/ul4/Continue.php';
include_once 'com/livinglogic/ul4/DelVar.php';
include_once 'com/livinglogic/ul4/DictItem.php';
include_once 'com/livinglogic/ul4/DictItemDict.php';
include_once 'com/livinglogic/ul4/DictItemKeyValue.php';
include_once 'com/livinglogic/ul4/Dict.php';
include_once 'com/livinglogic/ul4/JsonSerializable.php';
include_once 'com/livinglogic/ul4/Color.php';
include_once 'com/livinglogic/ul4/MonthDelta.php';
include_once 'com/livinglogic/ul4/TimeDelta.php';
include_once 'com/livinglogic/ul4/Location.php';
include_once 'com/livinglogic/ul4/EvaluationContext.php';
include_once 'com/livinglogic/ul4/InterpretedTemplate.php';
include_once 'com/livinglogic/ul4/Var.php';
include_once 'com/livinglogic/ul4/DictComprehension.php';

include_once 'com/livinglogic/ul4/Function.php';
include_once 'com/livinglogic/ul4/FunctionNow.php';
include_once 'com/livinglogic/ul4/FunctionUTCNow.php';
include_once 'com/livinglogic/ul4/FunctionVars.php';
include_once 'com/livinglogic/ul4/FunctionRandom.php';
include_once 'com/livinglogic/ul4/FunctionXMLEscape.php';
include_once 'com/livinglogic/ul4/FunctionCSV.php';
include_once 'com/livinglogic/ul4/FunctionStr.php';
include_once 'com/livinglogic/ul4/FunctionRepr.php';
include_once 'com/livinglogic/ul4/FunctionInt.php';
include_once 'com/livinglogic/ul4/FunctionFloat.php';
include_once 'com/livinglogic/ul4/FunctionBool.php';
include_once 'com/livinglogic/ul4/FunctionLen.php';
include_once 'com/livinglogic/ul4/FunctionEnumerate.php';
include_once 'com/livinglogic/ul4/FunctionEnumFL.php';
include_once 'com/livinglogic/ul4/FunctionIsFirstLast.php';
include_once 'com/livinglogic/ul4/FunctionIsFirst.php';
include_once 'com/livinglogic/ul4/FunctionIsLast.php';
include_once 'com/livinglogic/ul4/FunctionIsNone.php';
include_once 'com/livinglogic/ul4/FunctionIsStr.php';
include_once 'com/livinglogic/ul4/FunctionIsInt.php';
include_once 'com/livinglogic/ul4/FunctionIsFloat.php';
include_once 'com/livinglogic/ul4/FunctionIsBool.php';
include_once 'com/livinglogic/ul4/FunctionIsDate.php';
include_once 'com/livinglogic/ul4/FunctionIsList.php';
include_once 'com/livinglogic/ul4/FunctionIsDict.php';
include_once 'com/livinglogic/ul4/FunctionIsTemplate.php';
include_once 'com/livinglogic/ul4/FunctionIsColor.php';
include_once 'com/livinglogic/ul4/FunctionChr.php';
include_once 'com/livinglogic/ul4/FunctionOrd.php';
include_once 'com/livinglogic/ul4/FunctionHex.php';
include_once 'com/livinglogic/ul4/FunctionOct.php';
include_once 'com/livinglogic/ul4/FunctionBin.php';
include_once 'com/livinglogic/ul4/FunctionAbs.php';
include_once 'com/livinglogic/ul4/FunctionRange.php';
include_once 'com/livinglogic/ul4/FunctionSorted.php';
include_once 'com/livinglogic/ul4/FunctionType.php';
include_once 'com/livinglogic/ul4/FunctionGet.php';
include_once 'com/livinglogic/ul4/FunctionAsJSON.php';
include_once 'com/livinglogic/ul4/FunctionAsUL4ON.php';
include_once 'com/livinglogic/ul4/FunctionReversed.php';
include_once 'com/livinglogic/ul4/FunctionRandRange.php';
include_once 'com/livinglogic/ul4/FunctionRandChoice.php';
include_once 'com/livinglogic/ul4/FunctionFormat.php';
include_once 'com/livinglogic/ul4/FunctionZip.php';
include_once 'com/livinglogic/ul4/FunctionRGB.php';
include_once 'com/livinglogic/ul4/FunctionHLS.php';
include_once 'com/livinglogic/ul4/FunctionHSV.php';
include_once 'com/livinglogic/ul4/FunctionAll.php';
include_once 'com/livinglogic/ul4/FunctionAny.php';
include_once 'com/livinglogic/ul4/FunctionDate.php';
include_once 'com/livinglogic/ul4/FunctionFromJSON.php';
include_once 'com/livinglogic/ul4/FunctionFromUL4ON.php';

include_once 'com/livinglogic/ul4/Method.php';
include_once 'com/livinglogic/ul4/MethodSplit.php';
include_once 'com/livinglogic/ul4/MethodRSplit.php';
include_once 'com/livinglogic/ul4/MethodStrip.php';
include_once 'com/livinglogic/ul4/MethodLStrip.php';
include_once 'com/livinglogic/ul4/MethodRStrip.php';
include_once 'com/livinglogic/ul4/MethodUpper.php';
include_once 'com/livinglogic/ul4/MethodLower.php';
include_once 'com/livinglogic/ul4/MethodCapitalize.php';
include_once 'com/livinglogic/ul4/MethodItems.php';
include_once 'com/livinglogic/ul4/MethodHour.php';
include_once 'com/livinglogic/ul4/MethodMinute.php';
include_once 'com/livinglogic/ul4/MethodSecond.php';
include_once 'com/livinglogic/ul4/MethodMicrosecond.php';
include_once 'com/livinglogic/ul4/MethodISOFormat.php';
include_once 'com/livinglogic/ul4/MethodMIMEFormat.php';
include_once 'com/livinglogic/ul4/MethodR.php';
include_once 'com/livinglogic/ul4/MethodG.php';
include_once 'com/livinglogic/ul4/MethodB.php';
include_once 'com/livinglogic/ul4/MethodA.php';
include_once 'com/livinglogic/ul4/MethodHLS.php';
include_once 'com/livinglogic/ul4/MethodHLSA.php';
include_once 'com/livinglogic/ul4/MethodHSV.php';
include_once 'com/livinglogic/ul4/MethodHSVA.php';
include_once 'com/livinglogic/ul4/MethodLum.php';
include_once 'com/livinglogic/ul4/MethodDay.php';
include_once 'com/livinglogic/ul4/MethodMonth.php';
include_once 'com/livinglogic/ul4/MethodYear.php';
include_once 'com/livinglogic/ul4/MethodWeekday.php';
include_once 'com/livinglogic/ul4/MethodYearday.php';
include_once 'com/livinglogic/ul4/MethodStartsWith.php';
include_once 'com/livinglogic/ul4/MethodEndsWith.php';
include_once 'com/livinglogic/ul4/MethodFind.php';
include_once 'com/livinglogic/ul4/MethodRFind.php';
include_once 'com/livinglogic/ul4/MethodGet.php';
include_once 'com/livinglogic/ul4/MethodWithLum.php';
include_once 'com/livinglogic/ul4/MethodWithA.php';
include_once 'com/livinglogic/ul4/MethodJoin.php';
include_once 'com/livinglogic/ul4/MethodReplace.php';

/*
include_once 'com/livinglogic/ul4/LoadConst.php';
include_once 'com/livinglogic/ul4/LoadColor.php'; // de.livinglogic.ul4.color  1
include_once 'com/livinglogic/ul4/LoadDate.php';  // de.livinglogic.ul4.date   3
include_once 'com/livinglogic/ul4/LoadFalse.php'; // de.livinglogic.ul4.false  1
include_once 'com/livinglogic/ul4/LoadFloat.php'; // de.livinglogic.ul4.float  1
include_once 'com/livinglogic/ul4/LoadInt.php';   // de.livinglogic.ul4.int   15  TODO
include_once 'com/livinglogic/ul4/LoadNone.php';  // de.livinglogic.ul4.none   1
include_once 'com/livinglogic/ul4/LoadStr.php';   // de.livinglogic.ul4.str   12
include_once 'com/livinglogic/ul4/LoadTrue.php';  // de.livinglogic.ul4.true   3
*/
include_once 'com/livinglogic/ul4/List.php';
include_once 'com/livinglogic/ul4/CallFunc.php';
include_once 'com/livinglogic/ul4/CallMeth.php';

include_once 'com/livinglogic/ul4/Binary.php';
include_once 'com/livinglogic/ul4/Add.php';
include_once 'com/livinglogic/ul4/And.php';
include_once 'com/livinglogic/ul4/Or.php';
include_once 'com/livinglogic/ul4/Sub.php';
include_once 'com/livinglogic/ul4/Mul.php';
include_once 'com/livinglogic/ul4/Contains.php';
include_once 'com/livinglogic/ul4/EQ.php';
include_once 'com/livinglogic/ul4/FloorDiv.php';
include_once 'com/livinglogic/ul4/GE.php';
include_once 'com/livinglogic/ul4/GetAttr.php';
include_once 'com/livinglogic/ul4/GetItem.php';
include_once 'com/livinglogic/ul4/GetSlice.php';
include_once 'com/livinglogic/ul4/GT.php';
include_once 'com/livinglogic/ul4/LE.php';
include_once 'com/livinglogic/ul4/LT.php';
include_once 'com/livinglogic/ul4/Mod.php';
include_once 'com/livinglogic/ul4/NE.php';
include_once 'com/livinglogic/ul4/NotContains.php';
include_once 'com/livinglogic/ul4/TrueDiv.php';

include_once 'com/livinglogic/ul4/Unary.php';
include_once 'com/livinglogic/ul4/UnaryTag.php';
include_once 'com/livinglogic/ul4/Neg.php';
include_once 'com/livinglogic/ul4/Not.php';
include_once 'com/livinglogic/ul4/PPrint.php';
include_once 'com/livinglogic/ul4/PrintX.php';
// TODO render

include_once 'com/livinglogic/ul4/For.php';

include_once 'com/livinglogic/ul4/ASTException.php';
include_once 'com/livinglogic/ul4/BlockException.php';
include_once 'com/livinglogic/ul4/BreakException.php';
include_once 'com/livinglogic/ul4/ContinueException.php';
include_once 'com/livinglogic/ul4/KeyException.php';
include_once 'com/livinglogic/ul4/LocationException.php';
include_once 'com/livinglogic/ul4/ArgumentCountMismatchException.php';
include_once 'com/livinglogic/ul4/ArgumentTypeMismatchException.php';
include_once 'com/livinglogic/ul4/SyntaxException.php';
include_once 'com/livinglogic/ul4/TagException.php';
include_once 'com/livinglogic/ul4/TemplateException.php';
include_once 'com/livinglogic/ul4/UnknownFunctionException.php';
include_once 'com/livinglogic/ul4/UnknownMethodException.php';
include_once 'com/livinglogic/ul4/UnpackingException.php';

include_once 'com/livinglogic/ul4/Block.php';
include_once 'com/livinglogic/ul4/ConditionalBlock.php';
include_once 'com/livinglogic/ul4/ConditionalBlockWithCondition.php';
include_once 'com/livinglogic/ul4/ConditionalBlockBlock.php';
include_once 'com/livinglogic/ul4/If.php';
include_once 'com/livinglogic/ul4/ElIf.php';
include_once 'com/livinglogic/ul4/Else.php';
//include_once 'com/livinglogic/ul4/InterpretedTemplate.php';
include_once 'com/livinglogic/ul4/Utils.php';

?>