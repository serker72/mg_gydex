<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/langgroup.php');
require_once('../classes/groupitem.php');
require_once('../classes/groupsgroup.php');

require_once('../classes/v2/groupsgroup_new.php');

if(!(HAS_PRICE&&HAS_BASKET)){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	die();
}

//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 16)) {}
else{
	header('Location: no_rights.php');
	die();		
}	

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



$gg=new GroupsGroupNew(); 


if(isset($_POST['Update'])||isset($_POST['Update1'])){
	$kind=(int)$_POST['kind'];
	
	
	
	
	
	if($kind==2){
		//Обновляем базу
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				
				//удаляем 
				
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 16)) {
					$lid=(int)$val;
					
					$r=new GroupItem();
				
					$r->Del($lid);
				}else{
					header('Location: no_rights.php');
					die();
				}
				
				
			}
		}
	}
	
	
	
	header('Location: viewgroups.php?from='.$from.'&to_page='.$to_page);
	die();

}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Список групп покупателей - '. SITETITLE.' ');

$smarty->display('page_noleft_top.html');

?>

	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
		<h3>Список групп покупателей</h3>
		<input type="button" name="closer" id="closer" value="Обновить" onclick="window.location.reload(); ">&nbsp;&nbsp;
		
		<input type="button" name="closer" id="closer" value="Закрыть текущее окно" onclick="opener.location.reload(); window.close();"><p>
		
		<?
		//$gg->SetTemplates('clients/groups.html','tpl/groups_cli/itemsrow.html','','tpl/groups_cli/to_page.html');
		echo $gg->GetItems($from,$to_page, 'clients/groups.html');
		?>
		
		</td>
	</tr>
	</table>
	

	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_noleft_bottom.html');
?>