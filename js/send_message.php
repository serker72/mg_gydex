<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');


$res='';
if(isset($_POST['action'])&&($_POST['action']=="send_message")){
	
	$txt=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	
	
	mail(FEEDBACK_EMAIL, 'запрос с сайта nt-holding.ru', $txt, "From: \"nt-holding.ru\" <".FEEDBACK_EMAIL.">\n"."Reply-To: ".FEEDBACK_EMAIL."\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
}
echo $res;
?>