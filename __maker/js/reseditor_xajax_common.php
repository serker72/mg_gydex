<?
$xajax_read  =  new  xajax("js/reseditor_xajax_serv.php");  

//$xajax_read->configure('debug', true);
$xajax_read->setCharEncoding('windows-1251');

//��������� �������� �����
$xajax_read->registerFunction("DrawRes");  

//�������� �� ���� �������
$xajax_read->registerFunction("DelVal");  

//�������� �������
$xajax_read->registerFunction("DelRes");  

//�������� �����
$xajax_read->registerFunction("DelFile");  

//������� �����
$xajax_read->registerFunction("AddFile");  

//������ �����
$xajax_read->registerFunction("EditFile");  

//������� �������
$xajax_read->registerFunction("AddRes");  

//������ �������
$xajax_read->registerFunction("EdRes");  

//������� �� ����
$xajax_read->registerFunction("AddVal");  

//������ �� ����
$xajax_read->registerFunction("EdVal");  
?>