<?php

include_once 'com/livinglogic/ul4on/Decoder.php';
include_once 'com/livinglogic/ul4on/Encoder.php';
include_once 'com/livinglogic/ul4on/UL4ONSerializable.php';
include_once 'com/livinglogic/ul4on/Utils.php';


include_once 'com/livinglogic/ul4/AST.php';
include_once 'com/livinglogic/ul4/Block.php';
include_once 'com/livinglogic/ul4/JsonSerializable.php';
include_once 'com/livinglogic/ul4/Color.php';
include_once 'com/livinglogic/ul4/Location.php';
include_once 'com/livinglogic/ul4/EvaluationContext.php';
include_once 'com/livinglogic/ul4/InterpretedTemplate.php';

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

include_once 'com/livinglogic/ul4/LoadConst.php';
include_once 'com/livinglogic/ul4/LoadInt.php';
include_once 'com/livinglogic/ul4/LoadStr.php';
include_once 'com/livinglogic/ul4/LoadVar.php';
include_once 'com/livinglogic/ul4/List.php';
include_once 'com/livinglogic/ul4/CallFunc.php';

include_once 'com/livinglogic/ul4/Binary.php';
include_once 'com/livinglogic/ul4/Add.php';
include_once 'com/livinglogic/ul4/Sub.php';
include_once 'com/livinglogic/ul4/Mul.php';
include_once 'com/livinglogic/ul4/LoadAnd.php';
include_once 'com/livinglogic/ul4/LoadOr.php';
include_once 'com/livinglogic/ul4/Contains.php';
include_once 'com/livinglogic/ul4/EQ.php';
include_once 'com/livinglogic/ul4/FloorDiv.php';
include_once 'com/livinglogic/ul4/GE.php';
include_once 'com/livinglogic/ul4/GetItem.php';
include_once 'com/livinglogic/ul4/GT.php';
include_once 'com/livinglogic/ul4/LE.php';
include_once 'com/livinglogic/ul4/LT.php';
include_once 'com/livinglogic/ul4/Mod.php';
include_once 'com/livinglogic/ul4/NE.php';
include_once 'com/livinglogic/ul4/NotContains.php';
include_once 'com/livinglogic/ul4/TrueDiv.php';

include_once 'com/livinglogic/ul4/Unary.php';
include_once 'com/livinglogic/ul4/Neg.php';
include_once 'com/livinglogic/ul4/Not.php';
include_once 'com/livinglogic/ul4/PPrint.php';
include_once 'com/livinglogic/ul4/PrintX.php';
// TODO render

include_once 'com/livinglogic/ul4/For.php';
include_once 'com/livinglogic/ul4/ForNormal.php';

include_once 'com/livinglogic/ul4/BreakException.php';
include_once 'com/livinglogic/ul4/ContinueException.php';
include_once 'com/livinglogic/ul4/LocationException.php';
include_once 'com/livinglogic/ul4/ArgumentCountMismatchException.php';
include_once 'com/livinglogic/ul4/ArgumentTypeMismatchException.php';
include_once 'com/livinglogic/ul4/UnknownFunctionException.php';

include_once 'com/livinglogic/ul4/Block.php';
//include_once 'com/livinglogic/ul4/InterpretedTemplate.php';
include_once 'com/livinglogic/ul4/Utils.php';

?>