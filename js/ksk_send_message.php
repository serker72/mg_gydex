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
	 
	
	$txt='���������� ������ � ����� '.SITEURL;
	//$txt.='<br /><br />����� ������: '.$code.'<br /><br />���������� ����:<br />'.$fio;
	
	 
	 
	//$txt.='<br /><br />E-mail:<br />'.$email;
	 
	
	$txt.='<br /><br />����������� �����:<br />'.$email;
	
	//$txt.='<br /><br />�����:<br />'.$city;
	
	$txt.='<br /><br />�������:<br />'.$phone;
	
	
	$txt.='<br /><br />����� ������:<br />'.htmlspecialchars($message);
	
	
	//@mail('support_gn@gydex.ru', $code.': ������ � ����� '.SITEURL, $txt, "From: \"".$fio."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	@mail(FEEDBACK_EMAIL, iconv('windows-1251', 'utf-8', '������ � ����� '.SITEURL.''), $txt, "From: \"".$fio."\" <".$email.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	/*
	
	$txt_user='�������, ��� ���������� � ������ ���������. Gydex.����� � ������� � ������� 24 �����!<br /><br />';
	
	$txt_user.='<br /><br />����� ����� ������:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	
	
	@mail($email, $code.': ������ � ����� '.SITEURL, $txt_user, "From: \"".SITEURL."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: ".FEEDBACK_EMAIL."\n"."Content-Type: text/html; charset=\"windows-1251\"\n");*/
	
	
	
}elseif(isset($_POST['action'])&&($_POST['action']=="send_callback")){
	
	$fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['fio']));
 	$phone=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['phone']));
	 
	
	$txt='���������� ������ �� �������� ������ � ����� '.SITEURL.':';
	 
	$txt.='<br /><br />���������� ����:<br />'.$fio;
	 
	$txt.='<br /><br />�������:<br />'.$phone;
	
	//$txt.='<br /><br />����������� �����:<br />'.$email;
	
	//$txt.='<br /><br />�����:<br />'.$city;
	 
	
	
	//$txt.='<br /><br />����� ������:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	 
	
	
	@mail(FEEDBACK_EMAIL, iconv('windows-1251', 'utf-8', '������ �� �������� ������ � ����� '.SITEURL.''), $txt, "From: \"".$fio."\" <".$email.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	 
	
}

echo $res;
?>