<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/langgroup.php');
require_once('../classes/groupitem.php');
require_once('../classes/groupsgroup.php');

/*
if(!(HAS_PRICE&&HAS_BASKET)){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();
}
*/


//административная авторизация
require_once('inc/adm_header.php');


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

$gi=new GroupItem();

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
	
		$gr=$gi->GetItemById($id);
	}else{
		header('Location: no_rights.php');
		die();		
	}
	if($gr==false){
		header('Location: index.php');
		die();	
	}
}



if(($action==0)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$params['name']=SecStr($_POST['name']);
	$params['descr']=SecStr($_POST['descr']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 16)) {
	
		$re=$gi->Add($params);
	}else{
		header('Location: no_rights.php');
		die();	
	}
	
	header('Location: viewgroups.php?from='.$from.'&to_page='.$to_page.'#'.$re);
	die();
	
}

if(($action==1)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$params['name']=SecStr($_POST['name']);
	$params['descr']=SecStr($_POST['descr']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
		$gi->Edit($id,$params);
	}else{
		header('Location: no_rights.php');
		die();
	}
	
	header('Location: viewgroups.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	die();
	
}


if($action==2){
//удаление
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 16)) {
		$gi->Del($id);
		unset($li);
	}else{
		header('Location: no_rights.php');
		die();
	}
	header('Location: viewgroups.php?from='.$from.'&to_page='.$to_page);
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Редактирование группы покупателей - '.SITETITLE.'');

$smarty->display('page_noleft_top.html');


?>

	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
		<form action="ed_group.php" method="post" name="inpp" id="inpp">
		
		<h3>Редактирование группы покупателей</h3>
		<input type="hidden" name="to_page" value="<?=$to_page?>">
		<input type="hidden" name="from" value="<?=$from?>">
		<input type="hidden" name="action" value="<?=$action?>">
		<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
		
		<?if($action==1){
	?>
			<input type="button" name="grusers" value="Покупатели в этой группе..." onclick="winop('viewgrusers.php?group_id=<?=$id?>',800,600,'clgroups');"><p>
	<?}?>
		
		<strong>Название группы:</strong><br>
	<input type="text" name="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($gr['name']));?>" size="60" maxlength="255"><p>

		
				<strong>Описание группы:</strong><br>
				<textarea cols="60" rows="10" name="descr"><?if($action==1) echo htmlspecialchars(stripslashes($gr['descr']));?></textarea>
	
		        <p />
		
			<input type="submit" name="doInp" value="Внести изменения">
		
		</form>
		
		<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("name","req","Заполните  поле Название группы!");    
  frmvalidator.addValidation("name","minlen=4","Заполните  поле Название группы!");    
  
</script>  
		
		</td>
	</tr>
	</table>
	

	
<?
//нижний шаблон
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->display('page_noleft_bottom.html');
?>