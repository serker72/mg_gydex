<?
$xajax_read  =  new  xajax("js/modfolder_xajax_serv.php");  

$xajax_read->configure('debug', true);
$xajax_read->setCharEncoding('windows-1251');

//����� �����
$xajax_read->registerFunction("CreateFolder");  


//�������������� �����
$xajax_read->registerFunction("RenameFolder");  

//�������� �����
$xajax_read->registerFunction("DeleteFolder");  


//����������� ��������� ����� 
//$xajax_read->registerFunction("DrawSubtree");  