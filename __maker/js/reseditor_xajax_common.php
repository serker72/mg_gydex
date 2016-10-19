<?
$xajax_read  =  new  xajax("js/reseditor_xajax_serv.php");  

//$xajax_read->configure('debug', true);
$xajax_read->setCharEncoding('windows-1251');

//отрисовка ресурсов файла
$xajax_read->registerFunction("DrawRes");  

//удаление яз знач ресурса
$xajax_read->registerFunction("DelVal");  

//удаление ресурса
$xajax_read->registerFunction("DelRes");  

//удаление файла
$xajax_read->registerFunction("DelFile");  

//добавка файла
$xajax_read->registerFunction("AddFile");  

//правка файла
$xajax_read->registerFunction("EditFile");  

//добавка ресурса
$xajax_read->registerFunction("AddRes");  

//правка ресурса
$xajax_read->registerFunction("EdRes");  

//добавка яз знач
$xajax_read->registerFunction("AddVal");  

//правка яз знач
$xajax_read->registerFunction("EdVal");  
?>