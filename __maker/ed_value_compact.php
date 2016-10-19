<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/alldictsgroup.php');
require_once('../classes/langgroup.php');
require_once('../classes/alldictitem.php');
require_once('../classes/dictattachitem.php');
require_once('../classes/propvalitem.php');
include('../editor/fckeditor.php');
require_once('../classes/dictattdisp.php');
require_once('../classes/dictnvdisp.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}


//административна€ авторизаци€
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
$disp=new DictAttDisp($mode);
$mode=$disp->GetWorkMode();
//echo $mode;

$disp=new DictNVDisp();
$li=new PropValItem();

$na=new PropNameItem();
$di=new AllDictItem();


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



if($action==0){
	//проверить name_id, item_id
	if(!isset($_GET['name_id']))
		if(!isset($_POST['name_id'])) {
			header('Location: index.php');
			die();
		}
		else $name_id = $_POST['name_id'];		
	else $name_id = $_GET['name_id'];		
	$name_id=abs((int)$name_id);
	
	$name=$na->GetItemById($name_id);
	if($name==false){
		header('Location: index.php');
		die();
	}
	
	if(!isset($_GET['item_id']))
		if(!isset($_POST['item_id'])) {
			header('Location: index.php');
			die();
		}
		else $item_id = $_POST['item_id'];		
	else $item_id = $_GET['item_id'];		
	$item_id=abs((int)$item_id);
	
	
	
	$dict=$di->GetItemById($name['dict_id']);
	if($dict==false){
		header('Location: index.php');
		die();
	}
}




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
	$temp_lang=$li->GetItemById($id);
	$item_id=$temp_lang['item_id'];
	$name_id=$temp_lang['name_id'];	
	
	
	//смена €зыка
	if(isset($_POST['doLang'])||isset($_GET['doLang'])){
		//попытаемс€ добавить €зык
		
		$li->AddLanguage($id,$lang_id);
		header('Location: ed_value_compact.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&nonvisual='.$nonvisual.'&from='.$from.'&to_page='.$to_page);
		die();
	}
	
	
	$lang=$li->GetItemById($id, $lang_id);
	if($lang==false){
		header('Location: index.php');
		die();
	}
	
	//найдем Name, dict
	$name=$na->GetItemById($name_id);
	if($name==false){
		header('Location: index.php');
		die();
	}
	
	$dict=$di->GetItemById($name['dict_id']);
	if($dict==false){
		header('Location: index.php');
		die();
	}
}

//найдем товар
$gi=new PriceItem;
$good=$gi->GetItemById($item_id);
if($good!==false) 
	$mid=$good['mid'];
else $mid=0;


if(($action==0)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	
	if($dict['kind_id']==1){
		$lparams['name']=SecStr($_POST['name']);
		if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;

	}else if($dict['kind_id']==2){
		$lparams['name']=SecStr($_POST['name']);
		if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
		$params['ord']=abs((int)$_POST['ord']);
	}else if($dict['kind_id']==3){
		if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
		$params['photo_small']=SecStr($_POST['photo_small']);
		$params['photo_big']=SecStr($_POST['photo_big']);		
	}
	$params['name_id']=$name_id;
	$params['item_id']=$item_id;
	$lparams['lang_id']=$lang_id;	
		
	$code=$disp->AddValue($params,$lparams);
	
	
	header('Location: viewnames.php?dict_id='.$name['dict_id'].'&id='.$item_id.'&from='.$from.'&to_page='.$to_page.'#'.$name_id);
	die();
}


if(($action==1)&&isset($_POST['doInp'])){
	//
	$params=Array();
	$lparams=Array();
	
	
	if($dict['kind_id']==1){
		$lparams['name']=SecStr($_POST['name']);
		if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;

	}else if($dict['kind_id']==2){
		$lparams['name']=SecStr($_POST['name']);
		if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
		$params['ord']=abs((int)$_POST['ord']);
	}else if($dict['kind_id']==3){
		if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
		$params['photo_small']=SecStr($_POST['photo_small']);
		$params['photo_big']=SecStr($_POST['photo_big']);		
	}
	$params['name_id']=$name_id;
	$params['item_id']=$item_id;
	$lparams['lang_id']=$lang_id;	
	
	
	$disp->EditValue($id,$params,$lparams);
	
	header('Location: viewnames.php?dict_id='.$name['dict_id'].'&id='.$item_id.'&from='.$from.'&to_page='.$to_page.'#'.$name_id);
	die();
	
}

//копирование данных на другой €зык
if(($action==1)&&isset($_POST['doCopy'])){
	/*
	1. получить данные по заданному €зыку
	2. внести данные по lang_id:
		2.2. если уже существует - то через EditValue
		2.3. »наче - через AddValue
	*/
	
	$copy_lang_id=abs((int)$_POST['copy_lang_id']);
	
	$ethalon=$li->GetItemById($id, $copy_lang_id, 0);
	if($ethalon!==false){
		
		$params=Array();
		$lparams=Array();
		
		if($dict['kind_id']==1){
			$lparams['name']=$ethalon['name'];
			$lparams['is_shown']=$ethalon['is_shown'];
		}else if($dict['kind_id']==2){
			$lparams['name']=$ethalon['name'];
			$lparams['is_shown']=$ethalon['is_shown'];
			$params['ord']=$ethalon['ord'];
		}else if($dict['kind_id']==3){
			$lparams['is_shown']=$ethalon['is_shown'];
			$params['photo_small']=$ethalon['photo_small'];
			$params['photo_big']=$ethalon['photo_big'];		
		}
		$params['name_id']=$name_id;
		$params['item_id']=$item_id;
		$lparams['lang_id']=$lang_id;	
		
		$target=$li->GetItemById($id, $lang_id, 0);
		if($target===false){
			
			$code=$disp->AddValue($params,$lparams);
			header('Location: viewnames.php?dict_id='.$name['dict_id'].'&id='.$item_id.'&from='.$from.'&to_page='.$to_page.'#'.$name_id);
			die();
		}else{
			$disp->EditValue($id,$params,$lparams);
	
			header('Location: viewnames.php?dict_id='.$name['dict_id'].'&id='.$item_id.'&from='.$from.'&to_page='.$to_page.'#'.$name_id);
			die();
		}
		
	}else{
		//эталона не существует
		header('Location: viewnames.php?dict_id='.$name['dict_id'].'&id='.$item_id.'&from='.$from.'&to_page='.$to_page.'#'.$name_id);
		die();
	}
	
}


if($action==2){
//удаление
	$disp->DelValue($id);
	unset($li);
	header('Location: viewnames.php?dict_id='.$name['dict_id'].'&id='.$item_id.'#'.$name_id);
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
	<form action="ed_value_compact.php" method="post" name="inpp" id="inpp">
	<h3>–едактирование значени€ свойства  &quot;<?=stripslashes($name['name'])?>&quot;</h3>
	
	
	<input type="hidden" name="name_id" value="<?if($action==0) echo $name_id;?>">
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	<input type="hidden" name="item_id" value="<?if($action==0) echo $item_id;?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	
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
	
	
	<?if($dict['kind_id']==1){
	?>
		<strong>«начение:</strong><br>
	<input type="text" name="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name']));  if($action==0) echo stripslashes($name['default_val']);?>" size="50" maxlength="255"><p>	

		<strong>ќтображать на сайте: </strong><input class="unbord" name="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_shown']==1)) echo 'checked'; else if($action==0) echo 'checked'; ?> /><p />
	<?
	}?>
	
	
	<?if($dict['kind_id']==2){
	?>
		<strong>«начение:</strong><br>
	<input type="text" name="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name'])); if($action==0) echo stripslashes($name['default_val']);?>" size="50" maxlength="255"><p>	

		<strong>ќтображать на сайте: </strong><input class="unbord" name="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_shown']==1)) echo 'checked'; else if($action==0) echo 'checked';?> /><p />
		
		<strong>ѕор€док показа:</strong> <input name="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $lang['ord']; else echo '1'; ?>" /><p />
	<?
	}?>
	
	
	<?if($dict['kind_id']==3){
	?>
	
     <script src="/uploadifive/jquery.uploadifive.js" type="text/javascript"></script>
                    <link rel="stylesheet" type="text/css" href="/uploadifive/uploadifive.css">
    
    
    	 
     
	<script type="text/javascript">
	$(function(){ 

	 
			//определим видимость блоков 
			<?if($action==0){?>
			  $("#quick_upload").css("display","block");
			  $("#full_upload").css("display","none");
			<?}else if($action==1){?>
			  $("#quick_upload").css("display","none");
			  $("#full_upload").css("display","block");
			<?}?>
			
			//выбор полной загрузки, сокрытие краткой
			$("#full_uploader_select").bind("click",function(){
				//alert("1");	
				 $("#quick_upload").css("display","none");
				 $("#full_upload").css("display","block");
				 				 return false;
			});
			
			$("#quick_uploader_select").bind("click",function(){
				//alert("2");	
			  $("#quick_upload").css("display","block");
			  $("#full_upload").css("display","none");
			  				 return false;
			});
			
	});
	</script>
        
     <div id="quick_upload">
    <h3>«агрузите фото:</h3>
    
    
    
     <input type="hidden"  id="photo_small_" value="" />
                    
                    
                    
                   <div id="queue_photo_small"></div>

   				   <script type="text/javascript">
					$(function(){
							 
							
							
							$('#photo_small_').uploadifive({
									'auto'             : true,
									'buttonText' : '¬ыберите и загрузите файл...',
									 
									'fileType'     : 'image/*',
									'fileSizeLimit' : '100 MB',
									'uploadLimit' : 0, 
									'queueSizeLimit' : 1, 
									'multi'          : false,
									'width'           : 200,
									'formData'         : {
														   
														  "PHPSESSID" : "<?php echo session_id(); ?>",
														  "mid" : "<? echo $mid;?>"
														 },
									'queueID'          : 'queue_photo_small',
									'uploadScript'     : 'swfupl-js/upload.php',
									'onUploadComplete' : function(file, data) { 
											eval(data)
									
									}
								});
							
							 
					 
					});
					 </script>
    
    
    
    
    
    
    
    
    
	
		 
            
            <p><a href="#" id="full_uploader_select">...или выберите уже загруженное фото.</a></p>
    </div>    
        
        
    <div id="full_upload">    
        
        <strong>‘ото малое</strong><br />
		<input name="photo_small" id="photo_small" type="text" size="50" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['photo_small'])); else echo 'img/no.gif';?>" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=2','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_small.value=''; document.forms.inpp.photo_small.focus();">выбрать фото</a>
		<p />		


		<strong>‘ото большое</strong><br />
		<input name="photo_big" id="photo_big" type="text" size="50" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['photo_big'])); else echo 'img/no.gif';?>" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=3','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_big.value=''; document.forms.inpp.photo_big.focus();">выбрать фото</a>
		  <p><a href="#" id="quick_uploader_select">...или перейдите к быстрой загрузке.</a></p>
        </div>
        
        
        <p />	
		
		<strong>ќтображать на сайте: </strong><input class="unbord" name="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_shown']==1)) echo 'checked'; else if($action==0) echo 'checked';?> /><p />
	<?
	}?>
	

	<input type="submit" name="doInp" value="¬нести изменени€">
	
	
	<?if($action==1){?>
		<p><strong>—копировать данные из зеркала на €зыке:</strong>
		<select name="copy_lang_id" id="copy_lang_id">
		<?
			echo $disp->ShowValLangsOpt($id,$lang_id);
		?>
		</select>
		
		<input type="submit" name="doCopy" value="¬ыполнить"> </p>
	<?}?>
	
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