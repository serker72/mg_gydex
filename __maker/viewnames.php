<?
session_start();
require_once('../classes/langgroup.php');
require_once('../classes/dictattdisp.php');
require_once('../classes/dictnvdisp.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}

//���������������� �����������
require_once('inc/adm_header.php');


//����� ������
if(!isset($_SESSION['mode'])){
	$mode=1;
}else $mode=abs((int)$_SESSION['mode']);
$disp=new DictAttDisp($mode);
$mode=$disp->GetWorkMode();

$dd=new DictNVDisp();

if(!isset($_GET['dict_id']))
	if(!isset($_POST['dict_id'])) {
		$dict_id=0;
	}
	else $dict_id = $_POST['dict_id'];		
else $dict_id = $_GET['dict_id'];		
$dict_id=abs((int)$dict_id);

//���� �����, � �������� ���������� ������
if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		$id=0;
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);


if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		$from=0;
	}else $from = $_POST['from'];	
}else $from = $_GET['from'];	
$from=abs((int)$from);	


if(!isset($_GET['to_page'])){
	if(!isset($_POST['to_page'])){
		$to_page=PROPS_PER_PAGE;
	}else $to_page = abs((int)$_POST['to_page']);	
}else $to_page = abs((int)$_GET['to_page']);	
//$to_page=abs((int)$to_page);	


//������� ������ ��������
if(isset($_POST['doName'])&&($dict_id!=0)){
	$params=Array();
	$lparams=Array();
	
	$lparams['name']=SecStr($_POST['name']);
	$lparams['default_val']=SecStr($_POST['default_val']);
	$params['ord']=abs((int)$_POST['ord']);
	$params['dict_id']=$dict_id;
	if(isset($_POST['is_criteria'])) $params['is_criteria']=1; else $params['is_criteria']=0;
	
	$lparams['lang_id']=LANG_CODE;	
	$lparams['is_shown']=1;	
	
	$dd->AddName($params,$lparams);
}

if((isset($_POST['Update'])||isset($_POST['Update1']))&&(($mode==1)||($mode==2))){
	$act=(int)$_POST['act'];
	
	if($act==1){
		//��������� ����
		//������� ������ ���� ������
		$langs=Array();
		$langgr=new LangGroup();
		$langs=$langgr->GetLangsIdList();
		
		
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				 $lparams=Array();
				//�������� ��������� �������
				foreach($langs as $lk=>$lv){
					if(isset($_POST[$lid.'_'.$lv.'_is_shown'])) $dd->ToggleVisibleLangName($lid, $lv, 1);
					else $dd->ToggleVisibleLangName($lid, $lv, 0);
				}
				
			}
		}
	}
	
	if($act==2){
		//��������� ����
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//������� 
				$lid=(int)$val;
				$dd->DelName($lid);
			}
		}
	}
	
	
	if(($act==4)||($act==5)){
		//��������� ����
		
		
		foreach($_POST as $key=>$val){
			if(eregi("_do_process",$key)){
				//echo $key; echo $val;
				$lid=(int)$val;
				if($act==4) $dd->ToggleVisibleName($lid,1);
				if($act==5) $dd->ToggleVisibleName($lid,0);
				
			}
		}
	}
	
	header('Location: viewnames.php?dict_id='.$dict_id.'&id='.$id.'&from='.$from.'&to_page='.$to_page);
	die();

}



require_once('../classes/smarty/SmartyAdm.class.php');
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",SITETITLE.' ');

$smarty->assign('no_header', true);
$smarty->display('page_noleft_top.html');

?>



<?
/*echo $mode;

foreach($_GET as $k => $v){
	echo "$k = $v<br>";
}
*/



if($dict_id==0){
	echo '<h3>�������� ������� ��� ������!</h3>';
}else{
	//�������� � ��������� ��������
	?>
	<input type="button" name="toscreen" id="toscreen" value="�������� � ��������� ����..." onclick="winop('viewnames.php?<?=getenv('QUERY_STRING')?>','800','600','props');"><br>
	<?
	$dict=new AllDictItem();
	$di=$dict->GetItemById($dict_id);
	if($di!=false){
	
		echo '<strong>�������: '.stripslashes($di['name']).'</strong><br>';
		if($mode==3){
			//������ ������� � �������� (��� ������)
			//$dd->SetNamesTemplates('alldicts/items_names_vals.html', 'tpl/dict_vals/itemsrow.html', 'tpl/dict_vals/itemsrow.html', 'tpl/dict_vals/to_page.html','tpl/dict_vals/subitem_name.html','tpl/dict_vals/subitem_lang_vis_check.html');
			$dd->SetNamesTemplates('alldicts/items_names_vals.html', '', '', '','','');
			echo $dd->GetNamesVals($dict_id, $id, $from,$to_page);
			
			
		}else if(($mode==1)||($mode==3)){
			//������ ������ �������
			//$dd->SetNamesTemplates('alldicts/items_names.html', 'tpl/dicts_names/itemsrow.html', 'tpl/dicts_names/itemsrow.html', 'tpl/dicts_names/to_page.html','tpl/dicts_names/subitem_name.html','tpl/dicts_names/subitem_lang_vis_check.html');
			$dd->SetNamesTemplates('alldicts/items_names.html', '', '', '','','');
			echo $dd->GetNamesById($dict_id, $id, 0,$from,$to_page);
		}
	}else{
		echo '<h3>�������� ������� ��� ������!</h3>';
	}
	?>
	<form action="viewnames.php" method="post" class="pole" name="addname" id="addname">
	<strong>�������� ��������:</strong><br>
	<input type="hidden" name="dict_id" value="<?=$dict_id?>">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	��������: <input type="text" name="name" size="35" maxlength="255">&nbsp;&nbsp;<br>
	�������� �� ���������: <input type="text" name="default_val" size="30" maxlength="255">&nbsp;&nbsp;<br>
	�������� ��������� ������ ������: <input name="is_criteria" type="checkbox" /><p />	
	
	������� ������: <input type="text" name="ord" size="3" maxlength="3" value="1">&nbsp;&nbsp;
	<input type="submit" name="doName" value="��������">
	</form>	
	<script  language="JavaScript">  
		  var  frmvalidator    =  new  Validator("addname");  
  		frmvalidator.addValidation("name","req","���������  ����  ��������!");    
		frmvalidator.addValidation("ord","num","� ���� ������� ������ ����� ������� ������ �����!");
	</script>  
	<?
}


?>

<?
//������ ������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

$smarty->assign('no_footer', true);

$smarty->display('page_noleft_bottom.html');
?>