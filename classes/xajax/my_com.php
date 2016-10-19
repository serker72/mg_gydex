<?
require_once('xajax_core/xajax.inc.php');
require_once('../../classes/filetext.php');

$xajax_read  =  new  xajax("my_serv.php");  
//$xajax_read->configure('debug', true);
//$xajax_read->configure('characterEncoding',"windows-1251");
$xajax_read->setCharEncoding('windows-1251');



$RF= &$xajax_read->registerFunction("ReadData");  
$WF= &$xajax_read->registerFunction("WriteData");  


?>