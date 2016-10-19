<?
$xajax_read  =  new  xajax("js/modfolder_xajax_serv.php");  

$xajax_read->configure('debug', true);
$xajax_read->setCharEncoding('windows-1251');

//новая папка
$xajax_read->registerFunction("CreateFolder");  


//переименование папки
$xajax_read->registerFunction("RenameFolder");  

//удаление папки
$xajax_read->registerFunction("DeleteFolder");  


//перерисовка поддерева папок 
//$xajax_read->registerFunction("DrawSubtree");  