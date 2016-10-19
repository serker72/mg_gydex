<?
session_start();
require_once('../classes/global.php');

require_once('../classes/discr_authuser.php');


$au=new DiscrAuthUser(); //$backurl=getenv('HTTP_REFERER');

$backurl='index.php';


//разлогинимся
if(isset($_GET['doOut'])){
		$au->DeAuthorize();
		header('Location: '.$backurl);
		die();
}

//залогинимся
if(isset($_POST['doLog'])){
	$login=$_POST['login'];
	$passw=md5(($_POST['passw']));
	if(isset($_POST['rem_me'])) $rem=true; else $rem=false;
	//echo htmlspecialchars($login);
	
	//авторизуем юзера на сайте
	$res=$au->Authorize($login,$passw,$rem);
	
	$code=0;
	$code=$au->GetErrorCode();
	
	
	header('Location: '.$backurl.'?code='.$code);
	die();
 
}


header('Location: index.php');
die();

?>