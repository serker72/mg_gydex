<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/langgroup.php');
require_once('../classes/useritem.php');

if(!(HAS_PRICE&&HAS_BASKET)){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();
}


//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {}
else{
	header('Location: no_rights.php');
	die();		
}	

if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);

$ui=new UserItem();
$user=$ui->GetItemById($id);
if($user==false){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();
}

if(isset($_POST['doChp'])){
	//smena parolya
	$params=Array(); $params['passw']=md5($_POST['pass']);
	$ui->EditAdmin($id,$params);
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	die();
}



require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->display('page_mini_top.html');

?>

	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
		<h3>Смена пароля:</h3>
		
		<form action="change_pass.php" method="post" name="inpp" id="inpp">
		
		<input type="hidden" name="id" value="<?=$id?>">
		
		<strong>Введите новый пароль:</strong><br>
		<em>(минимальная длина 4 символа)</em><br>
		
		<input type="text" name="pass" id="pass" size="40" maxlength="40"><p>
		
		<input type="submit" name="doChp" value="Внести изменения">
		
		<input type="button" name="closer" id="closer" value="Отмена" onclick="opener.location.reload(); window.close();">
		</form>
		
		<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("pass","req","Заполните  поле Новый пароль!");    
  frmvalidator.addValidation("pass","minlen=4","Пароль должен быть не короче 4 символов!");    
  
</script>  
		</td>
	</tr>
	</table>
	

	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_mini_bottom.html');
?>