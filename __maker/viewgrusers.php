<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/langgroup.php');
require_once('../classes/groupitem.php');
require_once('../classes/groupsgroup.php');
require_once('../classes/usersgroup.php');

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


//разбивка по группам
if(!isset($_GET['group_id'])){
	if(!isset($_POST['group_id'])){
		$group_id=0;
	}else $group_id = $_POST['group_id'];	
}else $group_id = $_GET['group_id'];	
$group_id=abs((int)$group_id);	


$gg=new GroupsGroup(); 
$ug=new UsersGroup(); 


//обработка переноса пользователей
//добавим пользователя в группу
if(isset($_GET['doIncl'])&&($group_id!=0)){
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
	  $ui=new UserItem(); 
	  $gi=new GroupItem(); 
	  $group=$gi->GetItemById($group_id);
	  if($group!=false){
		  foreach($_GET as $key=>$val){
			  if(eregi("_do_process",$key)){
				  //echo " $key = $val<br>";
				  $lid=abs((int)$val);
				  $user=$ui->GetItemById($lid);
				  
				  if($user!=false){
					  //echo 'qqq';
					  //добавим юзера в группу
					  $ui->AddUserToGroup($lid, $group_id);
				  }
			  }
		  }
	  }
	}else{
		header('Location: no_rights.php');
		die();
	}
	header('Location: viewgrusers.php?group_id='.$group_id);
	die();
}

//удалим пользователя из группы
//проверок на существование юзера и группы не делаем, т.к. удалять- не записывать!:)
if(isset($_GET['doExcl'])&&($group_id!=0)){
	$ui=new UserItem(); 
	foreach($_GET as $key=>$val){
		if(eregi("_do_process",$key)){
			//echo " $key = $val<br>";
			$lid=abs((int)$val);
			$rights_man=new DistrRightsManager;
			if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 16)) {
				$ui->DelUserFromGroup($lid, $group_id);
			}else{
				header('Location: no_rights.php');
				die();
			}
		}
	}
	header('Location: viewgrusers.php?group_id='.$group_id);
	die();
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Список покупателей в группах - '.SITETITLE.' ');

$smarty->display('page_noleft_top.html');

?>

	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
		<h3>Список покупателей  в группах</h3>
		<form action="viewgrusers.php" method="get" name="manag" id="manag">
		
		<input type="button" name="closer" id="closer" value="Обновить" onclick="window.location.reload(); ">&nbsp;&nbsp;
		
		<input type="button" name="closer" id="closer" value="Закрыть текущее окно" onclick="opener.location.reload(); window.close();"><p>
		
		
		
		<table cellspacing="8" cellpadding="0" border="0">
		<tr>
		    <td width="45%">
			<strong>Выберите группу:</strong>
			<select name="group_id" id="group_id" onChange="m=document.getElementById('manag'); m.submit();" style="width: 100px;">
				<?
				echo $gg->GetItemsOpt($group_id);
				?>
			</select>
			
			<input type="button" value="Все группы..." onclick="winop('viewgroups.php',800,600,'allgroups');">
			
			<p>
			
			<strong>Покупатели в выбранной группе:</strong>
			<div style="width: 340px; height: 440px; border: 1px solid Black; overflow: scroll;">
			<?
				$ug->SetTemplates('clients/users_group.html','tpl/groups_cli/manager_itemsrow.html','','');
				
				echo $ug->GetItemsByGroupId($group_id,false);
				
				
			?>
			</div>
			</td>
			<td width="10%" align="center" valign="middle">
				<?if($group_id!=0){?>
				<input type="submit" name="doIncl" value="&lt;&lt;&lt;"><p>
				<input type="submit" name="doExcl" value="&gt;&gt;&gt;">
				<?}else{?>
				<em>выберите <br>
				группу <br>
				для <br>
				управления 
				</em>
				<?}?>
			</td>
		    <td width="45%">
			<strong>Прочие покупатели:</strong>
			<div style="width: 340px; height: 480px; border: 1px solid Black; overflow: scroll;">
			<?
				echo $ug->GetItemsByGroupId($group_id,true);
				
			?>
			</div>
			
			
			</td>
		</tr>
		</table>
		
		
		
		
		</form>
		</td>
	</tr>
	</table>
	

	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_noleft_bottom.html');
?>