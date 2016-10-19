<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
//require_once('../classes/alldictsgroup.php');
require_once('../classes/langgroup.php');
require_once('../classes/statusitem.php');
include('../editor/fckeditor.php');

if(!(HAS_PRICE&&HAS_BASKET)){
	header("Location: index.php");
	die();
}


//административна€ авторизаци€
require_once('inc/adm_header.php');

$li=new StatusItem();
if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	


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


if(!isset($_GET['lang_id']))
	if(!isset($_POST['lang_id'])) {
		//header('Location: index.php');
		//die();
		$lang_id=LANG_CODE;
	}
	else $lang_id = $_POST['lang_id'];		
else $lang_id = $_GET['lang_id'];		
$lang_id=abs((int)$lang_id);

if(!isset($_GET['nonvisual']))
	if(!isset($_POST['nonvisual'])) $nonvisual = 0;
	else $nonvisual = $_POST['nonvisual'];		
else $nonvisual = $_GET['nonvisual'];		
$nonvisual=abs((int)$nonvisual);	

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
	
	
	//смена €зыка
	if(isset($_POST['doLang'])||isset($_GET['doLang'])){
		//попытаемс€ добавить €зык
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 14)) {
				$li->AddLanguage($id,$lang_id);
		}else{
			header('Location: no_rights.php');
			die();
		}
		header('Location: ed_status.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
		die();
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 14)) {
	
		$lang=$li->GetItemById($id, $lang_id);
	}else{
		header('Location: no_rights.php');
		die();	
	}
	if($lang==false){
		header('Location: index.php');
		die();	
	}
}


//смена пор€дка показа 
if(($action==1)&&isset($_GET['changeOrd'])){
	$params=Array(); $lparams=Array();
	if($_GET['changeOrd']=='0') {
		if((int)$lang['ord']<245)	$params['ord']=(int)$lang['ord']+10;
	}
	
	if($_GET['changeOrd']=='1') {
		if((int)$lang['ord']>10) $params['ord']=(int)$lang['ord']-10;
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 14)) {
		$li->Edit($id, $params, $lparams);
	}else{
		header('Location: no_rights.php');
		die();	
	}
	
	header('Location: viewstatus.php?from='.$from.'&to_page='.$to_page);
	die();
}


if(($action==0)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	
	$lparams['descr']=SecStr($_POST['descr']);
	if(isset($_POST['is_shown'])) $params['is_changeable']=1; else $params['is_changeable']=0;
	$lparams['lang_id']=$lang_id;	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 14)) {
	
		$li->Add($params,$lparams);
	}else{
		header('Location: no_rights.php');
		die();	
	}
	
	header('Location: viewstatus.php?from='.$from.'&to_page='.$to_page);
	die();
	
}

if(($action==1)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	
	$lparams['descr']=SecStr($_POST['descr']);
	if(isset($_POST['is_shown'])) $params['is_changeable']=1; else $params['is_changeable']=0;
	$lparams['lang_id']=$lang_id;	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 14)) {
		$li->Edit($id,$params,$lparams);
	}else{
		header('Location: no_rights.php');
		die();
	}
	
	header('Location: viewstatus.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	die();
	
}


if($action==2){
//удаление
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 14)) {
		$li->Del($id);
		unset($li);
	}else{
		header('Location: no_rights.php');
		die();
	}
	header('Location: viewstatus.php?from='.$from.'&to_page='.$to_page);
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличи€ модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//логин-им€
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);


$smarty->display('page_top.html');

?>

	
	<form action="ed_status.php" method="post" name="inpp" id="inpp">
	<h1>–едактирование статуса заказа</h1>
	
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	
	<?
	//много€зычность
	if($action==1){
	?>
		<strong>¬ыберите €зык:</strong>
		<select name="lang_id" id="lang_id">
		<?
			$lg=new LangGroup();
			echo $lg->GetItemsOpt($lang_id,'lang_name');
		?>
		</select>
		
		<input type="submit" name="doLang" value="ѕерейти">
		<p>
	<?
	}
	?>
	
	
	<strong>Ќазвание статуса:</strong><br>
	<input type="text" name="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name']));?>" size="100" maxlength="255"><p>


	ќписание статуса:<br />
		        <?php
				if($nonvisual==1){
				?>
				<textarea cols="80" rows="20" name="descr"><?if($action==1) echo htmlspecialchars(stripslashes($lang['descr']));?></textarea>
				<?
				}else{
					$sBasePath = '../editor/';
					
					$oFCKeditor = new FCKeditor('descr') ;
					$oFCKeditor->Config['CustomConfigurationsPath'] = '/editor/myconfig.js' ;
					$oFCKeditor->ToolbarSet = 'mybar';
					$oFCKeditor->Width = '100%';
					$oFCKeditor->Height = 350;
					
					$oFCKeditor->BasePath	= $sBasePath ;
					if($action==1) $oFCKeditor->Value= stripslashes($lang['descr']); else $oFCKeditor->Value = '';
					
					$oFCKeditor->Create() ;
				}
				?>
		        <p />
				
				
				
				
				
				
		<strong>¬озможно изменение параметров заказа покупателем: </strong><input class="unbord" name="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_changeable']==1)) echo 'checked'; ?> /><p />
		
		
		
	

	<input type="submit" name="doInp" value="¬нести изменени€">
	
	</form>
	
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("name","req","«аполните  поле  Ќазвание статуса!");    
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