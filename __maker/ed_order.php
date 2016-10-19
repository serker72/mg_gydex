<?
session_start();
require_once('../classes/global.php');
require_once('../classes/orderitem.php');
require_once('../classes/useritem.php');
require_once('../classes/statusgroup.php');
require_once('../classes/langitem.php');
if(!(HAS_PRICE&&HAS_BASKET)){
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
}


//административная авторизация
require_once('inc/adm_header.php');


$li=new OrderItem();

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
			//header('Location: index.php');
			echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 14)) {
		$lang=$li->GetItemById($id);	
	}
	else{
		header('Location: no_rights.php');
		die();		
	}
	
	
	if($lang==false){
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();	
	}
}



if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//
	$params=Array();
	$params['pdate']=SecStr($_POST['pdate']);
	$params['email']=SecStr($_POST['email']);
	$params['phone']=SecStr($_POST['phone']);	
	$params['address']=SecStr($_POST['address']);
	$params['status_id']=abs((int)$_POST['status_id']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 14)) {	
	
		$li->Edit($id,$params);
	
	}else{
		header('Location: no_rights.php');
		die();		
	}
	if(isset($_POST['doInp']))
		echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	else if(isset($_POST['doApply']))
		Header('Location: ed_order.php?action=1&id='.$id);
		
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
	echo '<script language="JavaScript" type="text/javascript">opener.location.reload(); window.close();</script>';
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' Редактирование заказа');

$smarty->display('page_noleft_top.html');



?>

	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
		<a href="vieworderitems.php?id=<?=$id?>">к позициям заказа</a>
		<form action="ed_order.php" method="post" name="inpp" id="inpp">
		<h3>Редактирование заказа №<?=$id?> 
		<?//язык заказа
			$lio=new LangItem();
			$lango=$lio->GetItemById($lang['lang_id']);
			if($lango!=false){
				echo '<img src="/'.stripslashes($lango['lang_flag']).'" alt="'.stripslashes($lango['lang_name']).'" border="0">';
			}?>
		
		</h3>
		<input type="hidden" name="action" value="<?=$action?>">
		<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
		
		<strong>Статус заказа:</strong><br>
		<select name="status_id" id="status_id">
			<?
			$sg=new StatusGroup();
			echo $sg->GetItemsOptByLang_id($lang['status_id'],'name',LANG_CODE);
			?>
		</select><p>
		
		<strong>Дата заказа:</strong><br>
		<input type="text" name="pdate" value="<?=htmlspecialchars(stripslashes($lang['pdate']))?>" size="10" maxlength="10"><p>
		
		<b>Заказчик:</b><br>
		
		<?
		$ui=new UserItem();
		$user=$ui->GetItemById($lang['clid']);
		?>
		<a href="ed_client.php?action=1&id=<?=$lang['clid']?>" target="_blank"><?=stripslashes($user['username'])?></a><br>
		<?
		//язык пользователя
			$liu=new LangItem();
			$langu=$liu->GetItemById($user['lang_id']);
			if($langu!=false){
				echo '<img src="/'.stripslashes($langu['lang_flag']).'" alt="'.stripslashes($langu['lang_name']).'" border="0">';
			}
		?><p>
		
		<strong>e-mail:</strong><br>
		<input type="text" name="email" value="<?=htmlspecialchars(stripslashes($lang['email']))?>" size="40" maxlength="40"><p>
		
		<strong>Телефон:</strong><br>
		<input type="text" name="phone" value="<?=htmlspecialchars(stripslashes($lang['phone']))?>" size="40" maxlength="40"><p>
		
		
		
		<strong>Адрес:</strong><br>
		<textarea cols="40" rows="15" name="address"><?if($action==1) echo htmlspecialchars(stripslashes($lang['address']));?></textarea><p>
		
		
		<input type="submit" name="doInp" value="Внести изменения">
		<input type="submit" name="doApply" value="Применить изменения">
		</form>
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