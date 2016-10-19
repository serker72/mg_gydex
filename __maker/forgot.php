<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');

 require_once('../classes/discr_authuser.php');


//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",'Восстановление пароля - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=20;
$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличия модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);
 


$smarty->display('page_mini_top.html');
unset($smarty);

?>
 

<?
function GenSql($params,$email=NULL){
	$_sql=array();
		foreach($params as $k=>$v){
			if(!is_array($v)) $_sql[]='('.$k.'="'.$v.'")';
			else{
				 $_tsql=array();
				 foreach($v as $kk=>$vv){
					 $_tsql[]=''.$kk.'="'.$vv.'"'; 
				 }
				  $_sql[]='('.implode(' or ',$_tsql).')';
			}
		}
		$sql1=implode(' AND ',$_sql);
		$sql=' select * from discr_users where '.$sql1;
		/*if($email!==NULL){
			
			$sql.=' and( (id in (select user_id from user_contact_data where kind_id=5 and value="'.$email.'")) or (id in (select supplier_id from supplier_contact where id in(select contact_id from supplier_contact_data where kind_id=5 and value="'.$email.'"))))'; 
		}*/
		
		//echo $sql;
		return $sql;	
}


$content='';
if(!isset($_POST['doFind'])&&!isset($_POST['doRestore'])){
	//форма восстановления пароля
	//ввод логина или имейла	
	
	
	$sm1=new SmartyAdm;
	
	
	$content=$sm1->fetch('chp/restore1.html');
}elseif(isset($_POST['doFind'])&&isset($_POST['login'])&&isset($_POST['email'])){
	
	if((strlen($_POST['login'])==0)&&(strlen($_POST['email'])==0)){
		$content='<h1>Для восстановления пароля укажите логин или email!</h1>';	
	}else{
	
		$params=array();
		if(strlen($_POST['login'])>0){
			$params['login']=SecStr($_POST['login']);	
		}
		$email=NULL;
		if(strlen($_POST['email'])>0){
			$params['email']=SecStr($_POST['email']);
			
		}
		
		$params['is_blocked']=0;
		
		//echo  GenSql($params);
		$ts=new mysqlSet( GenSql($params,$email));
		$tc=$ts->GetResultNumRows();
		if($tc==0){
			$sm1=new SmartyAdm;
			$content=$sm1->fetch('chp/restore1_error.html');	
		}else{
			
			 
			$tu=$ts->GetResult();
			$g=mysqli_fetch_array($tu);
			
//			 
			 
			
			  $sm1=new SmartyAdm;
			  $sm1->assign('login',$_POST['login']);
			  $sm1->assign('email',$_POST['email']);
			  $content=$sm1->fetch('chp/restore2.html');
			 
		}
	}
}elseif(isset($_POST['doRestore'])){
	
	if((strlen($_POST['login'])==0)&&(strlen($_POST['email'])==0)){
		$content='<h1>Для восстановления пароля укажите логин или email!</h1>';	
	}elseif(strlen($_POST['password'])<4){
		$content='<h1>Заполните поле Пароль!</h1>';	

	}else{
		$params=array();
		if(strlen($_POST['login'])>0){
			$params['login']=SecStr($_POST['login']);	
		}
		$email=NULL;
		if(strlen($_POST['email'])>0){
			/*$params['email']=array('email_s'=>SecStr($_POST['email']),
								   'email_d'=>	SecStr($_POST['email']));*/
			$params['email']=SecStr($_POST['email']);					   
		}
		$params['is_blocked']=0;
		
		//echo  GenSql($params);
		$ts=new mysqlSet( GenSql($params,$email));
		$tc=$ts->GetResultNumRows();
		if($tc==0){
			$sm1=new SmartyAdm;
			$content=$sm1->fetch('chp/restore1_error.html');	
		}else{
			$rts=$ts->GetResult();
			
			for($i=0; $i<$tc; $i++){
				$f=mysqli_fetch_array($rts);
				//var_dump($f);
				
				
				$new_password=$_POST['password'];
				$new_password_md5=md5($new_password);
				$change_password_confirm=md5( time().$new_password.time() );
				
				
				$params=array();
				$params['new_password']=$new_password_md5;
				$params['change_password_confirm']=$change_password_confirm;
				$params['change_wait']=1;
				
				
				$_ui=new DistrUserItem;
				$_ui->Edit($f['id'], $params);
				
				
			 	
				
				 
				//найти имейл
			 
					 
					  $tsq='select email from discr_users where id="'.$f['id'].'"';
					 
					 
				// echo $tsq;
				$ts1=new mysqlset($tsq);
				$rts=$ts1->GetResult();
				$rtsc=$ts1->GetResultNumRows();
				
				if($rtsc>0){
					$rf=mysqli_fetch_array($rts);
					$email=$rf[0];
				  
				  //echo $email;
				  
				  $mail_txt="<html><body>
				  <p>Вы заказали смену Вашего пароля в системе ".SITETITLE."</p>
				  
				  <p>Ваш новый пароль: ".$new_password."</p>
				  
				  <p>Для подтвеждения смены пароля пройдите по ссылке: <a href=\"".SITEURL.'/__maker/confirm.php?confirm='.$change_password_confirm."\">".SITEURL.'/__maker/confirm.php?confirm='.$change_password_confirm."</a></p>
				  
				  <p>Если Вы не хотите менять пароль, то проигнорируйте данное сообщение.</p>
				  <p>Спасибо.</p>
				  </body></html>";
				  
				 // echo $email;
				 // print_r($mail_txt);
				  
				  
				  
				  
				  @mail($email,'подтверждение смены пароля',$mail_txt,"From: \"".FEEDBACK_EMAIL."\" <".FEEDBACK_EMAIL.">\n"."Reply-To: ".FEEDBACK_EMAIL."\n"."Content-Type: text/html; charset=\"windows-1251\"\n");
				  
				  @usleep(50);
				  
				  $sm1=new SmartyAdm;
			
					$content=$sm1->fetch('chp/restore3.html');
				}else{
					//сообщения ,что емайл не найден
					$sm1=new SmartyAdm;
			
					$content=$sm1->fetch('chp/restore2_noemail.html');	
					
				}
				
			}
			
			
			
		}
	}
	
}

echo $content;


?>	

	
<?
//нижний шаблон

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

echo $smarty->fetch('page_mini_bottom.html');
unset($smarty);
?>