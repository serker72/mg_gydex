<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/recaptcha-php/recaptchalib.php');
require_once('../classes/claim_creator.php');
require_once('../classes/claimitem.php');

define("OUR_FEEDBACK_EMAIL", FEEDBACK_EMAIL); //'help@gydex.ru');

$res='';
if(isset($_POST['action'])&&($_POST['action']=="send_message")){
	
	$fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['fio']));
	$company=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['company']));
	$phone=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['phone']));
	$email=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['email']));
	$area=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['area']));
	$kind=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['kind']));
	
	$phone=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['phone']));
	$city=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['city']));
	 
	
	/*
	
	$_cr=new ClaimCreator;
	$_cr->ses->ClearOldSessions();
	$code=$_cr->GenLogin();
	
	$txt_user.='Номер Вашей заявки: '.$code;
	
	$_ci=new ClaimItem;
	$params['code']=$code;
	$params['pdate']=time();
	$params['name']=$fio;
	$params['email']=$email;
	
	$params['city']=$city;
	
	$params['phone']=$phone;
	
	$params['txt']=SecStr(iconv('utf-8', 'windows-1251', $_POST['message']),9);
	$_ci->Add($params);
	$_cr->ses->DelSession(session_id());*/
	
	
	$txt='Отправлена заявка с сайта '.SITEURL;
	//$txt.='<br /><br />Номер заявки: '.$code.'<br /><br />Контактное лицо:<br />'.$fio;
	
	 
	 
	$txt.='<br /><br />E-mail:<br />'.$email;
	 
	
	$txt.='<br /><br />Электронная почта:<br />'.$email;
	
	$txt.='<br /><br />Город:<br />'.$city;
	
	$txt.='<br /><br />Телефон:<br />'.$phone;
	
	
	$txt.='<br /><br />Текст заявки:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	
	
	//@mail('support_gn@gydex.ru', $code.': заявка с сайта '.SITEURL, $txt, "From: \"".$fio."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	@mail(OUR_FEEDBACK_EMAIL, iconv('windows-1251', 'utf-8', 'заявка с сайта '.SITEURL.''), $txt, "From: \"".$fio."\" <".$email.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	/*
	
	$txt_user='Спасибо, что обратились в службу поддержки. Gydex.знает и ответит в течение 24 часов!<br /><br />';
	
	$txt_user.='<br /><br />Текст Вашей заявки:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	
	
	@mail($email, $code.': заявка с сайта '.SITEURL, $txt_user, "From: \"".SITEURL."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: ".FEEDBACK_EMAIL."\n"."Content-Type: text/html; charset=\"windows-1251\"\n");*/
	
	
	
}elseif(isset($_POST['action'])&&($_POST['action']=="send_callback")){
	
	$fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['fio']));
 	$phone=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['phone']));
	$email=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['email']));
	$city=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['city']));
	 
	
	$txt='Отправлена заявка на обратный звонок с сайта '.SITEURL.':';
	 
	$txt.='<br /><br />Контактное лицо:<br />'.$fio;
	 
	$txt.='<br /><br />Телефон:<br />'.$phone;
	
	$txt.='<br /><br />Электронная почта:<br />'.$email;
	
	$txt.='<br /><br />Город:<br />'.$city;
	 
	
	
	$txt.='<br /><br />Текст заявки:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	 
	
	
	@mail(OUR_FEEDBACK_EMAIL, iconv('windows-1251', 'utf-8', 'заявка на обратный звонок с сайта '.SITEURL.''), $txt, "From: \"".$fio."\" <".$email.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	 
	
}
elseif(isset($_POST['action'])&&($_POST['action']=="test_captcha")){
	 $privatekey = "6LeMA_oSAAAAAES8rIVtRzRSmQuUTa_tHvJw8w4b";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    $res= ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
         "(reCAPTCHA said: " . $resp->error . ")");
  } else {
    // Your code here to handle a successful verification
	$res="0";
  }
}
elseif(isset($_POST['action'])&&($_POST['action']=="test_captcha_simple")){
	 
  $var_name='captcha';
  if(isset($_POST['seed'])) $var_name.=$_POST['seed'];
  

  if ($_SESSION[$var_name]!=$_POST['captcha']) {
    // What happens when the CAPTCHA was entered incorrectly
    $res= "Введен неверный защитный код!";
  } else {
    // Your code here to handle a successful verification
	$res="0";
  }
}

elseif(isset($_POST['action'])&&($_POST['action']=="re_captcha")){
	 $publickey = "6LeMA_oSAAAAAMv28ET_uQw42W2t4VXl0h74uWWp";
  	$res=recaptcha_get_html($publickey);
}
elseif(isset($_POST['action'])&&($_POST['action']=="send_register")){
	
	$org=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['org']));
		
	$org_fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['org_fio']));	
	$fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['fio']));
 	$phone=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['phone']));
	$email=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['email']));
	//$city=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['city']));
	 
	
	$txt='Отправлена заявка на тестовый доступ с сайта '.SITEURL.':';
	 
	$txt.='<br /><br />Организация:<br />'.$org;
	
	$txt.='<br /><br />Ответственное лицо за программу:<br />'.$org_fio;
	
	
	$txt.='<br /><br />ФИО:<br />'.$fio;
	
	$txt.='<br /><br />Город:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['city']));
	 
	$txt.='<br /><br />Телефон:<br />'.$phone;
	
	$txt.='<br /><br />Электронная почта:<br />'.$email;
	
	$txt.='<br /><br />Skype:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['skype']));
	
	$txt.='<br /><br />ICQ:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['icq']));
	  
	 
	
	
	$txt.='<br /><br />Область(и) деятельности вашей компании:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['area']));
	 
	
	$txt.='<br /><br />Как Вы считаете, какие общие возможности автоматизированной системы управления актуальны для вашей организации:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['abilities']));
	 
	$txt.='<br /><br />Каких главных результатов Вы ждете от внедрения системы управления:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['results']));
	 
	 
	$txt.='<br /><br />Опишите одну-три самых важных для Вас текущих проблем управления, которые побудили Вас искать решение в виде автоматизированной системы управления:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['problems']));
	 
	 
	$txt.='<br /><br />Представьте желаемый вами порядок действий (бизнес-процесс) обычного сотрудника, что он должен делать в течение работы над одной сделкой (клиентом):<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['flow']));
	 
	$txt.='<br /><br />Ориентировочное количество пользователей CRM:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['number']));
	 
	$txt.='<br /><br />Необходима ли интеграция CRM c сайтом (сайтами) Вашей компании:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['integration']));
	 
	$txt.='<br /><br />Необходим ли перенос данных из другой CRM:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['transfer']));
	 
	      
	 
	
	
	
	$txt.='<br /><br />Дополнительная информация:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	 
	
	
	@mail(OUR_FEEDBACK_EMAIL, iconv('windows-1251', 'utf-8', 'заявка на тестовый доступ к CRM с сайта '.SITEURL.''), $txt, "From: \"".$fio."\" <".$email.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	 
	
}

elseif(isset($_POST['action'])&&($_POST['action']=="send_register_crm")){
	
	$org=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['org']));
		
	$org_fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['org_fio']));	
	$fio=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['fio']));
 	$phone=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['phone']));
	$email=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['email']));
	//$city=htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['city']));
	 
	
	$txt='Отправлена заявка на регистрацию с сайта '.SITEURL.':';
	 
	$txt.='<br /><br />Организация:<br />'.$org;
	
	$txt.='<br /><br />Ответственное лицо за программу:<br />'.$org_fio;
	
	
	$txt.='<br /><br />ФИО:<br />'.$fio;
	
	$txt.='<br /><br />Город:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['city']));
	
	
	$txt.='<br /><br />Должность:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['position']));
	
	$txt.='<br /><br />Отдел:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['dep']));
	
	
	$txt.='<br /><br />Дата рождения:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['birthday']));
	
	
	
	 
	$txt.='<br /><br />Мобильный телефон:<br />'.$phone;
	
	
	$txt.='<br /><br />Рабочий телефон с добавочным:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['work_phone']));
	
	$txt.='<br /><br />Электронная почта:<br />'.$email;
	
	 
	 
	$txt.='<br /><br />Часы работы:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['hours']));
	
	
	$txt.='<br /><br />Дополнительная информация:<br />'.htmlspecialchars(iconv('utf-8', 'windows-1251', $_POST['message']));
	 
	
	
	@mail(OUR_FEEDBACK_EMAIL, iconv('windows-1251', 'utf-8', 'заявка на регистрацию с сайта '.SITEURL.''), $txt, "From: \"".$fio."\" <".$email.">\n"."Reply-To: \"".$fio."\" <".$email.">\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
	
	 
	
}



echo $res;
?>