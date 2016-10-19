<?
session_start();
header('Content-type: text/html; charset=windows-1251');

require_once('../classes/global.php');
require_once('../classes/authuser.php');
require_once('../classes/smarty/SmartyAdm.class.php');
require_once('../classes/smarty/SmartyAj.class.php');
 
require_once('../classes/authuser.php');

 
require_once('../classes/program_group.php');
 
require_once('../inc/lang_define.php');

 
 

$ret='';
if(isset($_POST['action'])&&($_POST['action']=="pre_auth")){
	
	$login=iconv('utf-8', 'windows-1251', $_POST['login']);
	$password=iconv('utf-8', 'windows-1251', $_POST['password']); 
	 
	/*
	3 исхода:
	ничего не подошло - писать об этом
	подошло только к одной программе - перейти в эту программу
	подошло к нескольким программам - вывести их список
	*/ 
	$_pg=new ProgramGroup;
	$ret=$_pg->FindAccess($login, $password, 'login_program.html');
	 
} 

//if(DO_RECODE) $ret=iconv('windows-1251','utf-8',$ret);
echo $ret;	
?>