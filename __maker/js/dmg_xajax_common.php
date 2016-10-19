<?
$xajax_read  =  new  xajax("js/dmg_xajax_serv.php");  

//$xajax_read->configure('debug', true);
$xajax_read->setCharEncoding('windows-1251');

//добавка группы
$xajax_read->registerFunction("AddGroup");

//правка группы
$xajax_read->registerFunction("EditGroup");

//удаление группы
$xajax_read->registerFunction("DelGroup");

//добавка объекта
$xajax_read->registerFunction("AddObj");  

//правка объекта
$xajax_read->registerFunction("EdObj");  

//удаление объекта
$xajax_read->registerFunction("DelObj");

//присвоение права на объект группе
$xajax_read->registerFunction("GrantOnObjectToGroup");

//удаленине права на объект  у группы
$xajax_read->registerFunction("RevokeFromObjectToGroup");

//добавка юзера
$xajax_read->registerFunction("AddUser");

//правка юзера
$xajax_read->registerFunction("EditUser");

//удаление юзера
$xajax_read->registerFunction("DelUser");

//юзеры в группе
$xajax_read->registerFunction("ShowUsers");

//добавить юзеров в группу
$xajax_read->registerFunction("AddUsersToGroup");

//исключить юзеров из группы
$xajax_read->registerFunction("DelUsersFromGroup");

//группы, в которых состоит юзер
$xajax_read->registerFunction("ShowGroups");

//добавить юзера в группы
$xajax_read->registerFunction("AddUserToGroups");

//исключить юзера из групп
$xajax_read->registerFunction("DelUserFromGroups");

//присвоение права на объект юзеру
$xajax_read->registerFunction("GrantOnObjectToUser");

//удаленине права на объект  у юзера
$xajax_read->registerFunction("RevokeFromObjectToUser");

?>