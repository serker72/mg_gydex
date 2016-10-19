<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');

require_once('../classes/alldictsgroup.php');

require_once('../classes/alldictitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/mmenulist.php');

require_once('../classes/dictattdisp.php');


//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {}
else{
	header('Location: no_rights.php');
	die();
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}


$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {}
else{
	header('Location: no_rights.php');
	die();
}


//режим работы
if(!isset($_SESSION['mode'])){
	$mode=1;
}else $mode=abs((int)$_SESSION['mode']);
//echo $mode;
$disp=new DictAttDisp($mode);
$mode=$disp->GetWorkMode();
//echo $mode;
//$disp=new DictAttDisp(1);


if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);

if(!isset($_GET['kind']))
	if(!isset($_POST['kind'])) {
		//header('Location: index.php');
		echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
		die();
	}
	else $kind = $_POST['kind'];		
else $kind = $_GET['kind'];		
$kind=abs((int)$kind);
if(($kind!=1)&&($kind!=2)&&($kind!=3)){
	echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
			//echo $kind;
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



if(isset($_POST['Update'])||isset($_POST['Update1'])){
	$act=(int)$_POST['act'];
	
	
	if($act==1){
		//Обновляем базу
		//получим список всех языков
		$langs=Array();
		$langgr=new LangGroup();
		$langs=$langgr->GetLangsIdList();
		
		
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
				$r=new AllDictItem();
				
				//echo $_POST["act_id_".$lid];
				$params=Array();
				$params["kind_id"]=abs((int)$_POST["kind_id_".$lid]);
				$r->Edit($lid,$params);
				
		//		$lparams=Array();
				//проверим видимости раздела
				foreach($langs as $lk=>$lv){
					if(isset($_POST[$lid.'_'.$lv.'_is_shown'])) $r->ToggleVisibleLang($lid, $lv, 1);
					else $r->ToggleVisibleLang($lid, $lv, 0);
				}
				
				if(isset($_POST[$lid.'_is_attached'])){
					$linked=abs((int)$_POST[$lid.'_is_attached']);
					$disp->ChangeAttach($lid, $id, $linked);
				}
			}
		}
	}
	
	//die();
	
	if($act==2){
		//Обновляем базу
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				
				//удаляем 
				$lid=(int)$val;
				$r=new AllDictItem();
				$r->Del($lid);
			}
		}
	}
	
	if(($act==4)||($act==5)){
		//Обновляем базу
		
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				
				
				$r=new AllDictItem();
				
				if($act==4) $r->ToggleVisible($lid,1);
				if($act==5) $r->ToggleVisible($lid,0);
				
			}
		}
	}
	
	header('Location: alldicts.php?&id='.$id.'&kind='.$kind.'&from='.$from.'&to_page='.$to_page);
	die();

}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->display('page_noleft_top.html');


?>



	
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr align="left" valign="top">
		<td width="*"  class="pole">
		<form action="alldicts.php" name="locr" id="locr">
			<input type="hidden" name="kind" id="kind" value="<?=$kind?>">
			<input type="hidden" name="id" id="id" value="<?=$id?>">
			
			<h4 style="margin: 3px 0 0 0;">
			<?
			if($kind==1){
				$mi=new MmenuItem();
				$mm=$mi->GetItemById($id,LANG_CODE);
				echo 'Раздел <a href="razds.php?id='.$id.'" target="_blank">'.stripslashes($mm['name']).'</a>';
			}else if($kind==3){
				$gd=new PriceItem();
				$good=$gd->GetItemById($id,LANG_CODE);
				echo 'Товар <a href="ed_price.php?id='.$id.'&action=1" target="_blank">'.stripslashes($good['name']).'</a>';
			}
			?>
			</h4>	
			
			<strong>выберите другой <?if($kind==1) echo 'раздел'; else if($kind==3) echo 'товар';?>: <select name="id" id="id" style="width: 350px;" onchange="m=document.getElementById('locr'); m.submit();"><?
			if($kind==1){
				$ml=new MmenuList();
				echo $ml->GetItemsOptByParentIdLangId($id,0,LANG_CODE,'name',false);//GetItemsOptByLang_id($id,'name',LANG_CODE);
			}else if($kind==3){
				$gd=new PriceItem();
				$good=$gd->GetItemById($id,LANG_CODE);
				
				$pg=new PriceGroup();
				$filter_params=Array();
				$filter_params['t.mid']=$good['mid'];
				echo $pg->GetItemsOptByLang_id($id,'name',LANG_CODE,$filter_params);
			}
			?>
</select></strong>
			
			</form>
		
		
		
		<h3>Выберите словарь:</h3>
		
		<input type="button" name="makeNew" id="makeNew" value="Создать новый словарь..." onclick="winop('ed_dict_compact.php?action=0&kind=<?=$kind?>&parent_id=<?=$id?>',800,600,'dictt');">
&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="closer" id="closer" value="Закрыть текущее окно" onclick="opener.location.reload(); window.close();">
		<?
		
		$disp->SetTemplates('alldicts/items_comp.html', 'tpl/dicts_comp/all_itemsrow.html', 'tpl/dicts_comp/all_itemsrow.html', 'tpl/dicts_comp/all_to_page.html','tpl/dicts_comp/all_subitem_name.html','tpl/dicts_comp/all_subitem_lang_vis_check.html');
		
		
		echo $disp->GetItemsForWindow($id,$kind,$from,$to_page);
		?>
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

$smarty->display('page_noleft_bottom.html');
?>