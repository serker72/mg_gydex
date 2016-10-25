<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');


$res='';
if(isset($_POST['action'])&&($_POST['action']=="send_message")){
	
	$fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['fio']));
	$phone=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['phone']));
	$email=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['email']));
	$message=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	 
	
	$txt='Отправлена заявка с сайта '.SITEURL;
	//$txt.='<br /><br />Номер заявки: '.$code.'<br /><br />Контактное лицо:<br />'.$fio;
	
	 
	 
	//$txt.='<br /><br />E-mail:<br />'.$email;
	 
	
	$txt.='<br /><br />Электронная почта:<br />'.$email;
	
	//$txt.='<br /><br />Город:<br />'.$city;
	
	$txt.='<br /><br />Телефон:<br />'.$phone;
	
	
	$txt.='<br /><br />Текст заявки:<br />'.htmlspecialchars($message);
	
	
	//@mail('support_gn@gydex.ru', $code.': заявка с сайта '.SITEURL, $txt, "From: \"".$fio."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	@mail(FEEDBACK_EMAIL, iconv('windows-1251', 'utf-8', 'заявка с сайта '.SITEURL.''), $txt, "From: \"".$fio."\" <".$email.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	/*
	
	$txt_user='Спасибо, что обратились в службу поддержки. Gydex.знает и ответит в течение 24 часов!<br /><br />';
	
	$txt_user.='<br /><br />Текст Вашей заявки:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	
	
	@mail($email, $code.': заявка с сайта '.SITEURL, $txt_user, "From: \"".SITEURL."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: ".FEEDBACK_EMAIL."\n"."Content-Type: text/html; charset=\"windows-1251\"\n");*/
	
	
	
}elseif(isset($_POST['action'])&&($_POST['action']=="send_callback")){
	
	$fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['fio']));
 	$phone=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['phone']));
	 
	
	$txt='Отправлена заявка на обратный звонок с сайта '.SITEURL.':';
	 
	$txt.='<br /><br />Контактное лицо:<br />'.$fio;
	 
	$txt.='<br /><br />Телефон:<br />'.$phone;
	
	//$txt.='<br /><br />Электронная почта:<br />'.$email;
	
	//$txt.='<br /><br />Город:<br />'.$city;
	 
	
	
	//$txt.='<br /><br />Текст заявки:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	 
	
	
	@mail(FEEDBACK_EMAIL, iconv('windows-1251', 'utf-8', 'заявка на обратный звонок с сайта '.SITEURL.''), $txt, "From: \"".$fio."\" <".$email.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	 
	
}

echo $res;
?>