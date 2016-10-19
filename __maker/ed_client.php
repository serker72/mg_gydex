<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/useritem.php');
require_once('../classes/regkinditem.php');
require_once('../classes/groupsgroup.php');
//include('../editor/fckeditor.php');

if(!(HAS_PRICE&&HAS_BASKET)){
	header("Location: index.php");
	die();
}

//административная авторизация
require_once('inc/adm_header.php');


$li=new UserItem();
if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	

if(!isset($_GET['group_id'])){
	if(!isset($_POST['group_id'])){
		$group_id=0;
	}else $group_id = $_POST['group_id'];	
}else $group_id = $_GET['group_id'];	
$group_id=abs((int)$group_id);	


if(!isset($_GET['to_page'])){
	if(!isset($_POST['to_page'])){
		$to_page=ITEMS_PER_PAGE;
	}else $to_page = $_POST['to_page'];	
}else $to_page = $_GET['to_page'];	
$to_page=abs((int)$to_page);	


if(!isset($_GET['action']))
	if(!isset($_POST['action'])) $action = 0;
	else $action = $_POST['action'];		
else $action = $_GET['action'];		
$action=abs((int)$action);
if(($action!=0)&&($action!=1)&&($action!=2)) $action=0;




if(($action==1)||($action==2)){
	//проверим id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			header('Location: index.php');
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 16)) {
	  $lang=$li->GetItemById($id);
	  if($lang==false){
		  header('Location: index.php');
		  die();	
	  }
	}
	else{
		header('Location: no_rights.php');
		die();		
	}	
	
	
}

//udalenue uzera iz gruppy
if(isset($_GET['doDelGr'])){
	$gr_id=abs((int)$_GET['gr_id']);	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
		$li->DelUserFromGroup($id,$gr_id);
	}else{
		header('Location: no_rights.php');
		die();	
	}
	
	header('Location: ed_client.php?action=1&id='.$id.'&from='.$from.'&to_page='.$to_page.'&group_id='.$group_id);
	die();	
}



if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//
	$params=Array();
	$params['username']=SecStr($_POST['username']);
	$params['login']=SecStr($_POST['login'],10);
	$params['email']=SecStr($_POST['email']);	
	$params['phone']=SecStr($_POST['phone']);		
	
	$params['address']=SecStr($_POST['address']);
	if(isset($_POST['is_mailed'])) $params['is_mailed']=1; else $params['is_mailed']=0;
	if(isset($_POST['is_blocked'])) $params['is_blocked']=1; else $params['is_blocked']=0;
	$params['reg_id']=1;
	$params['lang_id']=LANG_CODE;
	
	$params['skidka']=abs((float)$_POST['skidka']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 16)) {
	
		$re=$li->AddAdmin($params);
	}else{
		header('Location: no_rights.php');
		die();
	}
	if(isset($_POST['doInp']))
		header('Location: viewclients.php?from='.$from.'&to_page='.$to_page.'&group_id='.$group_id.'#'.$re);
	else if(isset($_POST['doApply']))
		header('Location: ed_client.php?action=1&id='.$re.'&from='.$from.'&to_page='.$to_page.'&group_id='.$group_id);
	die();
	
}

if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//
	$params=Array();
	$params['username']=SecStr($_POST['username']);
	$params['login']=SecStr($_POST['login'],10);
	$params['email']=SecStr($_POST['email']);	
	$params['phone']=SecStr($_POST['phone']);		
	
	$params['address']=SecStr($_POST['address']);
	if(isset($_POST['is_mailed'])) $params['is_mailed']=1; else $params['is_mailed']=0;
	if(isset($_POST['is_blocked'])) $params['is_blocked']=1; else $params['is_blocked']=0;
	//$params['reg_id']=1;
	
	$params['skidka']=abs((float)$_POST['skidka']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
		$li->EditAdmin($id,$params);
	}else{
		header('Location: no_rights.php');
		die();
	}
	if(isset($_POST['doInp']))
		header('Location: viewclients.php?from='.$from.'&to_page='.$to_page.'&group_id='.$group_id.'#'.$id);
	else if(isset($_POST['doApply']))
		header('Location: ed_client.php?action=1&id='.$id.'&from='.$from.'&to_page='.$to_page.'&group_id='.$group_id);
	die();
	
}


if($action==2){
//удаление
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 16)) {
		$li->Del($id);
		unset($li);
	}else{
		header('Location: no_rights.php');
		die();
	}
	header('Location: viewclients.php?from='.$from.'&to_page='.$to_page.'&group_id='.$group_id);
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Редактирование покупателя - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=25;
$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличия модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//логин-имя
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);



//контекстные команды
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;



$_context->AddContext(new ContextItem( 16, 'a', "", "Создать покупателя", "ed_client.php?action=0", false , $global_profile  ));
 
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "ed_client.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
 

$_bc->AddContext(new BcItem('Список покупателей', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);

	

$smarty->display('page_top.html');
?>

	
	<form action="ed_client.php" method="post" name="inpp" id="inpp">
	<h1>Редактирование покупателя</h1>
	
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="group_id" value="<?=$group_id?>">
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	<? if($action==1){
	?>
			<a href="vieworders.php?clid=<?=$id?>&sortmode=4" target="_blank">заказы покупателя (<?=$li->CalcItemsByClid($id)?>)</a><p>
	<? }?>
	
	
	<label for="login">Логин:</label><br>
	<input type="text" id="login" name="login" value="<?if($action==1) echo stripslashes($lang['login']);?>" size="40" maxlength="40"><p>

	<label for="username">Имя:</label><br>
	<input type="text" id="username" name="username" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['username']));?>" size="40" maxlength="40">
	<? if($action==1){
	echo '<br>';
	//язык
	$ll=new LangItem(); $lll=$ll->GetItemById($lang['lang_id']);
	if($lll!=false)
		echo '<img src="/'.stripslashes($lll['lang_flag']).'" alt="'.stripslashes($lll['lang_name']).'" border="0" hspace="4">';
	
	//откуда произошел (вид регистрации)
	$ri=new RegKindItem();
	$rri=$ri->GetItemById($lang['reg_id']);
	if($rri!=false)
		echo '<em>'.stripslashes($rri['name']).'</em><br>';
	//в каких группах состоит
	$gg=new GroupsGroup();
	echo '<strong><em>Состоит в группах:</em></strong><br>'.$gg->GetItemsByClid($id,$from,$to_page,$group_id,'clients/in_group_fullpage.html');
	echo '<a href="#" '."onClick=\"winop('viewgrusers.php?group_id=$group_id',800,600,'clgroups');\"".'>покупатели по группам...</a>';
	}?>
	<p>

	<label for="email">E-mail:</label><br>
	<input type="text" name="email" id="email" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['email']));?>" size="40" maxlength="40"><p>

	<label for="is_mailed">Подписан на рассылку: </label><input class="unbord" name="is_mailed"  id="is_mailed"  type="checkbox" <? if(($action==1)&&($lang['is_mailed']==1)) echo 'checked'; ?> /><p />


	<label for="phone">Телефон:</label><br>
	<input type="text" name="phone" id="phone" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['phone']));?>" size="40" maxlength="40"><p>
	
				
				<label for="address">Адрес:</label><br>
				<textarea cols="60" rows="10" name="address" id="address"><?if($action==1) echo htmlspecialchars(stripslashes($lang['address']));?></textarea>
	
		        <p />
				
				
				<label for="skidka">Индивидуальная скидка,%:</label>
	<input type="text" name="skidka" id="skidka"  value="<?if($action==1) echo sprintf("%.0f",$lang['skidka']); else echo '0';?>" size="3" maxlength="2"><p>
				
				
				
		<label for="is_blocked">Блокирован: </label><input class="unbord" name="is_blocked"  id="is_blocked" type="checkbox" <? if(($action==1)&&($lang['is_blocked']==1)) echo 'checked'; ?> /><p />
		

	<input type="submit" name="doInp" value="Внести изменения">
	<input type="submit" name="doApply" value="Применить изменения">
	
	</form>
	
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("login","req","Заполните  поле Логин!");    
  frmvalidator.addValidation("login","minlen=4","Заполните  поле Логин!");    
  
  frmvalidator.addValidation("email","req","Заполните  поле E-mail!");    
  frmvalidator.addValidation("email","email","Заполните  поле E-mail!");    
  
  frmvalidator.addValidation("skidka","dec","В поле Скидка допустимы только числа!");    
</script>  
	
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_bottom.html');
?>