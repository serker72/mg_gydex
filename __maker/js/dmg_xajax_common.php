<?
$xajax_read  =  new  xajax("js/dmg_xajax_serv.php");  

//$xajax_read->configure('debug', true);
$xajax_read->setCharEncoding('windows-1251');

//������� ������
$xajax_read->registerFunction("AddGroup");

//������ ������
$xajax_read->registerFunction("EditGroup");

//�������� ������
$xajax_read->registerFunction("DelGroup");

//������� �������
$xajax_read->registerFunction("AddObj");  

//������ �������
$xajax_read->registerFunction("EdObj");  

//�������� �������
$xajax_read->registerFunction("DelObj");

//���������� ����� �� ������ ������
$xajax_read->registerFunction("GrantOnObjectToGroup");

//��������� ����� �� ������  � ������
$xajax_read->registerFunction("RevokeFromObjectToGroup");

//������� �����
$xajax_read->registerFunction("AddUser");

//������ �����
$xajax_read->registerFunction("EditUser");

//�������� �����
$xajax_read->registerFunction("DelUser");

//����� � ������
$xajax_read->registerFunction("ShowUsers");

//�������� ������ � ������
$xajax_read->registerFunction("AddUsersToGroup");

//��������� ������ �� ������
$xajax_read->registerFunction("DelUsersFromGroup");

//������, � ������� ������� ����
$xajax_read->registerFunction("ShowGroups");

//�������� ����� � ������
$xajax_read->registerFunction("AddUserToGroups");

//��������� ����� �� �����
$xajax_read->registerFunction("DelUserFromGroups");

//���������� ����� �� ������ �����
$xajax_read->registerFunction("GrantOnObjectToUser");

//��������� ����� �� ������  � �����
$xajax_read->registerFunction("RevokeFromObjectToUser");

?>