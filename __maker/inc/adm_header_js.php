<?
require_once('../../classes/discr_authuser.php');
require_once('../../classes/distr_rights_man.php');
$au=new DiscrAuthUser();
//проверим авторизацию
$global_profile=$au->Auth();
if($global_profile===NULL){
	
	Header('HTTP/1.1 403 Forbidden');
	Header('Location: login.php');
	die();		
}
else{
	
}

?>