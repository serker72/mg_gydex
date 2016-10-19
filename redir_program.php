<?
session_start();
require_once('classes/global.php');
 

require_once('classes/program_item.php');


 
 

// определим товар
if(!isset($_GET['program_id']))
	if(!isset($_POST['program_id'])) {
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
	else $program_id = $_POST['program_id'];		
else $program_id = $_GET['program_id'];		
$program_id=abs((int)$program_id);	

$gd=new ProgramItem;
$good=$gd->GetItemById($program_id);

if(($good===false)||($good['is_active']==0)){
	
		header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
}
	
if(!isset($_GET['login']))
	if(!isset($_POST['login'])) {
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
	else $login = $_POST['login'];		
else $login = $_GET['login'];		
 	 


if(!isset($_GET['password']))
	if(!isset($_POST['password'])) {
			header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	include("404.php");
	}
	else $password = $_POST['password'];		
else $password = $_GET['password'];
$password=md5($password);

$debug_prefix=''; if(DEBUG_REDIRECT) $debug_prefix='debug_';
 
if(!isset($_GET['org_id']))
	if(!isset($_POST['org_id'])) {
		$org_id='';	 
	}
	else $org_id = $_POST['org_id'];		
else $org_id = $_GET['org_id'];
if($org_id!='') $org_id='&org_id='.$org_id;


//echo "Location: ".$good[$debug_prefix.'url']."/user_mail_login.php?login=".$login."&password=".$password. $org_id;

//редирект в программу
header("Location: ".$good[$debug_prefix.'url']."/user_mail_login.php?login=".$login."&password=".$password. $org_id);

die();


?>