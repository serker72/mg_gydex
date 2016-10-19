<?
session_start();
require_once('classes/global.php');
require_once('classes/mmenulist.php');
require_once('classes/langgroup.php');
require_once('classes/langitem.php');
require_once('classes/filetext.php');
require_once('classes/authuser.php');

if((!HAS_BASKET)||(!HAS_PRICE)){
	header("Location: /404.php");
	die();
}

$lg=new LangGroup();
//определим какой язык
require_once('inc/lang_define.php');

$aui=new AuthUser();

if(isset($_GET['tryagain'])) $tryagain=1;
else $tryagain=0;
if($tryagain==1){
	if(isset($_GET['err_code'])) $err_code=abs((int)$_GET['err_code']);
	else $err_code=12;
	$err_message=$aui->ShowError($err_code);
}

$au=new AuthUser();
//проверим авторизацию
$profile=$au->Auth();


//генерация случайных имен полей и запись их в сессию
if($tryagain==0){
	unset($_SESSION['abyr']);
	$_SESSION['abyr']=Array(
		'login'=>Array('name'=>md5('abyr'.'login'.time()),'value'=>''),
		'passw'=>Array('name'=>md5('abyr'.'passw'.time()),'value'=>''),
		'newpassw'=>Array('name'=>md5('abyr'.'newpassw'.time()),'value'=>''),
		'newpassw_c'=>Array('name'=>md5('abyr'.'newpassw_c'.time()),'value'=>''),
		'username'=>Array('name'=>md5('abyr'.'username'.time()),'value'=>''),
		'address'=>Array('name'=>md5('abyr'.'address'.time()),'value'=>''),
		'email'=>Array('name'=>md5('abyr'.'email'.time()),'value'=>''),
		'phone'=>Array('name'=>md5('abyr'.'phone'.time()),'value'=>''),
		'is_mailed'=>Array('name'=>md5('abyr'.'is_mailed'.time()),'value'=>''),
		'doChp'=>Array('name'=>'doChp','value'=>'0'),
		'doChl'=>Array('name'=>'doChl','value'=>'0'),		
		'passw_del'=>Array('name'=>md5('abyr'.'passw_del'.time()),'value'=>'')
	);
}else{
	if(!isset($_SESSION['abyr']['login']['name'])){
		$_SESSION['abyr']['login']['name']=md5('abyr'.'login'.time());
		 $_SESSION['abyr']['login']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['passw']['name'])){
		$_SESSION['abyr']['passw']['name']=md5('abyr'.'passw'.time());
		$_SESSION['abyr']['passw']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['newpassw']['name'])){
		$_SESSION['abyr']['newpassw']['name']=md5('abyr'.'newpassw'.time());
		$_SESSION['abyr']['newpassw']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['newpassw_c']['name'])){
		$_SESSION['abyr']['newpassw_c']['name']=md5('abyr'.'newpassw_c'.time());
		$_SESSION['abyr']['newpassw_c']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['passw_del']['name'])){
		$_SESSION['abyr']['passw_del']['name']=md5('abyr'.'passw_del'.time());
		$_SESSION['abyr']['passw_del']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['username']['name'])){
		$_SESSION['abyr']['username']['name']=md5('abyr'.'username'.time());
		 $_SESSION['abyr']['username']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['email']['name'])){
		$_SESSION['abyr']['email']['name']=md5('abyr'.'email'.time());
		 $_SESSION['abyr']['email']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['address']['name'])){
		$_SESSION['abyr']['address']['name']=md5('abyr'.'address'.time());
		 $_SESSION['abyr']['address']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['phone']['name'])){
		$_SESSION['abyr']['phone']['name']=md5('abyr'.'phone'.time());
		 $_SESSION['abyr']['phone']['value']='';
	}
	
	if(!isset($_SESSION['abyr']['doChp']['name'])){
		$_SESSION['abyr']['doChp']['name']='doChp';
		$_SESSION['abyr']['doChp']['value']='0';
	}
	
	if(!isset($_SESSION['abyr']['doChl']['name'])){
		$_SESSION['abyr']['doChl']['name']='doChl';
		$_SESSION['abyr']['doChl']['value']='0';
	}
}



//вывод из шаблона
require_once('classes/smarty/SmartyAdm.class.php');
require_once('classes/filetext.php');
$fi=new FileText();
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->assign("SITETITLE",SITETITLE);

//ключевые слова
$tmp=$fi->GetItem('parts/razd6-'.$_SESSION['lang'].'.txt');
$smarty->assign('keywords',stripslashes($tmp));


//описание сайта
$tmp=$fi->GetItem('parts/razd7-'.$_SESSION['lang'].'.txt');
$smarty->assign('description',stripslashes($tmp));


$smarty->assign('do_index', 0);
$smarty->assign('do_follow', 0);

if(HAS_NEWS) $rss_lnk='<link rel="alternate" type="application/rss+xml" title="RSS" href="'.SITEURL.'/rss-feed.php">';
else $rss_lnk='';
$smarty->assign('metalang',$rss_lnk.$l['lang_meta']);

//работа с хедером
require_once('inc/header.php');
if(isset($header_res)){
	$smarty->assign('header',$header_res);
}else $smarty->assign('header','');



//работа с гориз меню
require_once('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu1',$hmenu1_res);
}else $smarty->assign('hmenu1','');


//левая колонка
require_once('inc/left.php');
if(isset($left_res)){
	$smarty->assign('left',$left_res);
}else $smarty->assign('left','');



//навигация
$smarty->assign('navi','');

$smarty->display('common_top.html');
unset($smarty);

$rf=new ResFile(ABSPATH.'cnf/resources.txt');

if($profile===NULL){
	//не авторизованы, надо показать форму начальной регистрации
?>
	<h2><?=$rf->GetValue('profile.php','reg_title',$lang);?></h2>
	
	<form action="/usereffects.php" method="post" name="inpp" id="inpp">
	
		<?
			if(isset($err_message)) echo '<strong style="color: Red;">'.$err_message.'</strong><p>';
			?>
				
				<strong><?=$rf->GetValue('profile.php','log_prompt',$lang);?>&nbsp;</strong><br>
				
				<input type="text" name="<?=$_SESSION['abyr']['login']['name']?>" id="<?=$_SESSION['abyr']['login']['name']?>" size="40" maxlength="40" value="<?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['login']['value']));?>">
				<p>
				
				<strong><?=$rf->GetValue('profile.php','pass_prompt',$lang);?></strong>&nbsp;<br>
				<em><?=$rf->GetValue('profile.php','pass_len',$lang);?></em><br>
				
				<input type="password" name="<?=$_SESSION['abyr']['passw']['name']?>" id="<?=$_SESSION['abyr']['passw']['name']?>" size="40" maxlength="40" value="">
				<p>	
				
				<strong><?=$rf->GetValue('profile.php','name_prompt',$lang);?>&nbsp;<br></strong>
				<input type="text" name="<?=$_SESSION['abyr']['username']['name']?>" id="<?=$_SESSION['abyr']['username']['name']?>" size="40" maxlength="128" value="<?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['username']['value']));?>"><p>
				
				
				<strong><?=$rf->GetValue('profile.php','phone_prompt',$lang);?>&nbsp;<br></strong>
				<input type="text" name="<?=$_SESSION['abyr']['phone']['name']?>" id="<?=$_SESSION['abyr']['phone']['name']?>" size="40" maxlength="40" value="<?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['phone']['value']));?>" >
				<p>

				<strong><?=$rf->GetValue('profile.php','email_prompt',$lang);?>&nbsp;<br></strong>
				<input type="text" name="<?=$_SESSION['abyr']['email']['name']?>" id="<?=$_SESSION['abyr']['email']['name']?>" size="40" maxlength="40" value="<?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['email']['value']));?>">
				<p>
				
						
				
				<strong><?=$rf->GetValue('profile.php','addr_prompt',$lang);?><br></strong>
				
				<textarea cols="40" rows="15" name="<?=$_SESSION['abyr']['address']['name']?>" id="<?=$_SESSION['abyr']['address']['name']?>"><?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['address']['value']));?></textarea>
				<p>
				
				<input type="checkbox" name="<?=$_SESSION['abyr']['is_mailed']['name']?>" id="<?=$_SESSION['abyr']['is_mailed']['name']?>" value="" <?if((isset($tryagain))&&($tryagain==1)) {if($_SESSION['abyr']['is_mailed']['value']=='1') echo 'checked';} else echo 'checked';?>><?=$rf->GetValue('profile.php','rass_prompt',$lang);?><p>
				
				<input type="submit" name="doNew" value="<?=$rf->GetValue('profile.php','reg_caption',$lang);?>" onClick="">
	
	</form>
	
		<script  language="JavaScript">  
		  var  frmvalidator    =  new  Validator("inpp");  
		 
		  frmvalidator.addValidation("<?=$_SESSION['abyr']['login']['name']?>","req","<?=$rf->GetValue('profile.php','log_error',$lang);?>");     frmvalidator.addValidation("<?=$_SESSION['abyr']['passw']['name']?>","req","<?=$rf->GetValue('profile.php','pass_error',$lang);?>");
frmvalidator.addValidation("<?=$_SESSION['abyr']['passw']['name']?>","minlen=4","<?=$rf->GetValue('profile.php','pass_error',$lang);?>");
frmvalidator.addValidation("<?=$_SESSION['abyr']['email']['name']?>","req","<?=$rf->GetValue('profile.php','email_error',$lang);?>");
frmvalidator.addValidation("<?=$_SESSION['abyr']['email']['name']?>","email","<?=$rf->GetValue('profile.php','email_error',$lang);?>");
		  
		</script>  

<?
}else{
//авторизованы, редактируем профиль
?>

<h2><?=$rf->GetValue('profile.php','ed_title',$lang);?></h2>
	
	<form action="/usereffects.php" method="post" name="inpp" id="inpp">
	
		<?
			if(isset($err_message)) echo '<strong style="color: Red;">'.$err_message.'</strong><p>';
			?>
				
				<strong><?=$rf->GetValue('profile.php','curr_log_prompt',$lang);?>&nbsp;</strong><br>
				
				<em><?=stripslashes($profile['login'])?></em><p>
				
				
				<strong><?=$rf->GetValue('profile.php','ed_pass_prompt',$lang);?></strong>&nbsp;<br>
				<em><?=$rf->GetValue('profile.php','pass_len',$lang);?></em><br>
				
				<input type="password" name="<?=$_SESSION['abyr']['passw']['name']?>" id="<?=$_SESSION['abyr']['passw']['name']?>" size="40" maxlength="40" value="">
				<p>	
				
				
				
				<input type="checkbox" name="doChl" id="doChl" value="" onchange="{mm=document.getElementById('chl'); if(this.checked){mm.style.display='block';}else{mm.style.display='none';}}" <?if($_SESSION['abyr']['doChl']['value']=='1') echo 'checked';?>><strong><?=$rf->GetValue('profile.php','ch_log_prompt',$lang);?>&nbsp;</strong>
				<div id="chl" style="display: <?if($_SESSION['abyr']['doChl']['value']=='1') echo 'block'; else echo 'none';?>;">
				<strong><?=$rf->GetValue('profile.php','ed_log_prompt',$lang);?>&nbsp;</strong><br>
				
				<input type="text" name="<?=$_SESSION['abyr']['login']['name']?>" id="<?=$_SESSION['abyr']['login']['name']?>" size="40" maxlength="40" value="<?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['login']['value'])); else echo htmlspecialchars(stripslashes($profile['login']));?>"><p>
				</div>
							
				
				
				<input type="checkbox" name="doChp" id="doChp" value="" onchange="{mm=document.getElementById('chp'); if(this.checked){mm.style.display='block';}else{mm.style.display='none';}}" <?if($_SESSION['abyr']['doChp']['value']=='1') echo 'checked';?>><strong><?=$rf->GetValue('profile.php','ch_p_prompt',$lang);?>&nbsp;</strong>
				<div id="chp" style="display: <?if($_SESSION['abyr']['doChp']['value']=='1') echo 'block'; else echo 'none';?>;">
				<strong><?=$rf->GetValue('profile.php','ed_new_pass_prompt',$lang);?></strong>&nbsp;<br>
				<em><?=$rf->GetValue('profile.php','pass_len',$lang);?></em><br>
				<input type="password" name="<?=$_SESSION['abyr']['newpassw']['name']?>" id="<?=$_SESSION['abyr']['newpassw']['name']?>" size="40" maxlength="40" value=""><br>
				
				<strong><?=$rf->GetValue('profile.php','ed_new_pass_confirm',$lang);?></strong>&nbsp;<br>
				<input type="password" name="<?=$_SESSION['abyr']['newpassw_c']['name']?>" id="<?=$_SESSION['abyr']['newpassw_c']['name']?>" size="40" maxlength="40" value="">
				</div>
				<p>
				
				
				<strong><?=$rf->GetValue('profile.php','name_prompt',$lang);?>&nbsp;<br></strong>
				<input type="text" name="<?=$_SESSION['abyr']['username']['name']?>" id="<?=$_SESSION['abyr']['username']['name']?>" size="40" maxlength="128" value="<?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['username']['value'])); else echo htmlspecialchars(stripslashes($profile['username']));?>"><p>
				
				
				<strong><?=$rf->GetValue('profile.php','phone_prompt',$lang);?>&nbsp;<br></strong>
				<input type="text" name="<?=$_SESSION['abyr']['phone']['name']?>" id="<?=$_SESSION['abyr']['phone']['name']?>" size="40" maxlength="40" value="<?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['phone']['value'])); else echo htmlspecialchars(stripslashes($profile['phone']));?>" >
				<p>

				<strong><?=$rf->GetValue('profile.php','email_prompt',$lang);?>&nbsp;<br></strong>
				<input type="text" name="<?=$_SESSION['abyr']['email']['name']?>" id="<?=$_SESSION['abyr']['email']['name']?>" size="40" maxlength="40" value="<?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['email']['value'])); else echo htmlspecialchars(stripslashes($profile['email']));?>">
				<p>
				
						
				
				<strong><?=$rf->GetValue('profile.php','addr_prompt',$lang);?><br></strong>
				
				<textarea cols="40" rows="15" name="<?=$_SESSION['abyr']['address']['name']?>" id="<?=$_SESSION['abyr']['address']['name']?>"><?if((isset($tryagain))&&($tryagain==1)) echo htmlspecialchars(stripslashes($_SESSION['abyr']['address']['value'])); else echo htmlspecialchars(stripslashes($profile['address']));?></textarea>
				<p>
				
				<input type="checkbox" name="<?=$_SESSION['abyr']['is_mailed']['name']?>" id="<?=$_SESSION['abyr']['is_mailed']['name']?>" value="" <?if((isset($tryagain))&&($tryagain==1)) {if($_SESSION['abyr']['is_mailed']['value']=='1') echo 'checked';} else if($profile['is_mailed']==1) echo 'checked';?>><?=$rf->GetValue('profile.php','rass_prompt',$lang);?><p>
				
				<input type="submit" name="doEdit" value="<?=$rf->GetValue('profile.php','ed_caption',$lang);?>" onClick="">
				
				<hr>
		<!-- форма удаления аккаунта -->
		<h3><?=$rf->GetValue('profile.php','del_title',$lang);?></h3>
		
		<input type="submit" name="doDel" value="<?=$rf->GetValue('profile.php','del_caption',$lang);?>" onClick="return window.confirm('<?=$rf->GetValue('profile.php','del_question',$lang);?>');">
				
	</form>
	
		<script  language="JavaScript">  
		  
		   function DoLen(){
		      var m1=document.getElementById("<?=$_SESSION['abyr']['newpassw']['name']?>");
			  var chp=document.getElementById("doChp");			  
			  
			  if((chp.checked)&&((m1.value).length<4))
			  {
			    alert('<?=$rf->GetValue('profile.php','pass_new_error',$lang);?>');
			    return false;
			  }
			  else
			  {
			    return true;
			  }
		   }
		   
		     //валидация по логину
		   function DoLenLogin(){
		      var m1=document.getElementById("<?=$_SESSION['abyr']['login']['name']?>");
			  var chp=document.getElementById("doChl");			  
			  
			  if((chp.checked)&&((m1.value).length<1))
			  {
			    alert('<?=$rf->GetValue('profile.php','log_error',$lang);?>');
			    return false;
			  }
			  else
			  {
			    return true;
			  }
		   }
		  
		  function DoEqual()
			{
			//  var frm = document.forms["inpp"];
			  var m1=document.getElementById("<?=$_SESSION['abyr']['newpassw']['name']?>");
			  var m2=document.getElementById("<?=$_SESSION['abyr']['newpassw_c']['name']?>");			  
			  var chp=document.getElementById("doChp");			  
			  
			  if((chp.checked)&&(m1.value != m2.value))
			  {
			    alert('<?=$rf->GetValue('profile.php','pass_not_confirm_error',$lang);?>');
			    return false;
			  }
			  else
			  {
			    return true;
			  }
			}

		  //валидация по смене пароля
		  function DoNewPassValid(){
		  	r1=DoLen();
			r2=DoEqual();
			if(r1&&r2) return true;
			else return false;
		  }
		  
		  //валидация общая
		  function DoCommonValid(){
		  	r1=DoNewPassValid();
			r2=DoLenLogin();
			if(r1&&r2) return true;
			else return false;
		  }
		  
		  
		  var  frmvalidator    =  new  Validator("inpp");  
			frmvalidator.setAddnlValidationFunction("DoCommonValid"); 		 
		       frmvalidator.addValidation("<?=$_SESSION['abyr']['passw']['name']?>","req","<?=$rf->GetValue('profile.php','pass_error',$lang);?>");
frmvalidator.addValidation("<?=$_SESSION['abyr']['passw']['name']?>","minlen=4","<?=$rf->GetValue('profile.php','pass_error',$lang);?>");
frmvalidator.addValidation("<?=$_SESSION['abyr']['email']['name']?>","req","<?=$rf->GetValue('profile.php','email_error',$lang);?>");
frmvalidator.addValidation("<?=$_SESSION['abyr']['email']['name']?>","email","<?=$rf->GetValue('profile.php','email_error',$lang);?>");
		  
		</script>  
		
			
		

		
<?
}
?>


<?
//нижний код
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

//работа с футером
require_once('inc/footer.php');
if(isset($footer_res)){
	$smarty->assign('footer',$footer_res);
}else $smarty->assign('footer','');

//работа с правой колонкой
require_once('inc/right.php');
if(isset($right_res)){
	$smarty->assign('right',$right_res);
}else $smarty->assign('right','');

//работа с гориз меню
require('inc/hmenu1.php');
if(isset($hmenu1_res)){
	$smarty->assign('hmenu2',$hmenu1_res);
}else $smarty->assign('hmenu2','');


$smarty->display('common_bottom.html');
unset($smarty);
?>
