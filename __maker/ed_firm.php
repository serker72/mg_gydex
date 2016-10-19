<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/firmitem.php');
include('../editor/fckeditor.php');

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}


//административная авторизация
require_once('inc/adm_header.php');


$li=new FirmItem();
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
	
	
	//смена языка
	if(isset($_POST['doLang'])||isset($_GET['doLang'])){
		//попытаемся добавить язык
		
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 17)) {
		
			$li->AddLanguage($id,$lang_id);
		}else{
			header('Location: no_rights.php');
    	    die();	
		}
		header('Location: ed_firm.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
		die();
	}
	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 17)) {
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


//смена порядка показа 
if(($action==1)&&isset($_GET['changeOrd'])){
	$params=Array(); $lparams=Array();
	if($_GET['changeOrd']=='0') {
		if((int)$lang['ord']<245)	$params['ord']=(int)$lang['ord']+10;
	}
	
	if($_GET['changeOrd']=='1') {
		if((int)$lang['ord']>10) $params['ord']=(int)$lang['ord']-10;
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 17)) {
		$li->Edit($id, $params, $lparams);
	}else{
		header('Location: no_rights.php');
   	    die();
	}
	
	header('Location: viewfirms.php?from='.$from.'&to_page='.$to_page);
	die();
}


if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$params['photo_small']=SecStr($_POST['photo_small']);
	$params['photo_big']=SecStr($_POST['photo_big']);	
	
	$lparams['info']=SecStr($_POST['info']);
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	$lparams['lang_id']=$lang_id;	
	
	$params['ord']=abs((int)$_POST['ord']);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 17)) {
		$r_code=$li->Add($params,$lparams);
	}else{
		header('Location: no_rights.php');
   	    die();
	}
	
	if(isset($_POST['doInp']))
		header('Location: viewfirms.php?from='.$from.'&to_page='.$to_page);
	else if(isset($_POST['doApply']))
		header('Location: ed_firm.php?action=1&id='.$r_code.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	die();
	
}

if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	//
	$params=Array();
	$lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$params['photo_small']=SecStr($_POST['photo_small']);
	$params['photo_big']=SecStr($_POST['photo_big']);	
	
	$lparams['info']=SecStr($_POST['info']);
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	$lparams['lang_id']=$lang_id;	
	
	$params['ord']=abs((int)$_POST['ord']);
	
	if(isset($_POST['doNew'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 17)) {
			$id=$li->Add($params,$lparams);
		}else{
			header('Location: no_rights.php');
   	 	   die();
		}
	}else	{
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 17)) {
			$li->Edit($id,$params,$lparams);
		}else{
			header('Location: no_rights.php');
	   	    die();
		}
	}
	
	if(isset($_POST['doInp']))
		header('Location: viewfirms.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: ed_firm.php?action=1&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	die();
	
}


if($action==2){
//удаление
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 17)) {
		$li->Del($id);
		unset($li);
	}else{
		header('Location: no_rights.php');
   	    die();
	}
	header('Location: viewfirms.php?from='.$from.'&to_page='.$to_page);
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Правка фирмы - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=26;
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


 
 
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "ed_firm.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
 $_bc->AddContext(new BcItem('Фирмы-производители', 'viewfirms.php'));

$_bc->AddContext(new BcItem('Правка фирмы', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);





$smarty->display('page_top.html');

?>

	<script src="/uploadifive/jquery.uploadifive.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/uploadifive/uploadifive.css">
    
 	<script type="text/javascript">
	$(function(){	
			//определим видимость блоков 
			 
			  $("#quick_upload").css("display","block");
			  $("#full_upload").css("display","none");
			 
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
    
    
    
    
    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
    
	<form action="ed_firm.php" method="post" name="inpp" id="inpp">
	<h1>Редактирование фирмы-производителя</h1>
	
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	
	<?
	//многоязычность
	if($action==1){
	?>
		<label for="lang_id">Выберите язык:</label>
		<select name="lang_id" id="lang_id">
		<?
			$lg=new LangGroup();
			echo $lg->GetItemsOpt($lang_id,'lang_name');
		?>
		</select>
		
		<input type="submit" name="doLang" value="Перейти">
		<p>
	<?
	}
	?>
	
	
	<label for="name">Название фирмы:</label><br>
	<input type="text" name="name" id="name" value="<?if($action==1) echo htmlspecialchars(stripslashes($lang['name']));?>" size="100" maxlength="255"><p>


	<label for="info">Описание фирмы:<br /></label>
    
    <textarea cols="80" rows="20" name="info"  id="info"><?if($action==1) echo htmlspecialchars(stripslashes($lang['info']));?></textarea>
		        <?php
				if($nonvisual==0){
				?>
				 
                    <script type="text/javascript">
	CKEDITOR.replace( 'info',
					 
					 {
						 customConfig : '/ckeditor/config_custom_photo.js',
						 filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
						filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
						filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?Type=Flash',
						filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=/userfiles/file',
						filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=/userfiles/image',
						filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=/userfiles/flash'
    				  }
					 );
	</script>
				<?
				}else{
					 
				}
				?>
		        <p />
				
				
		
          <div id="quick_upload">
    <h3>Загрузите фото:</h3>
	
			  <input type="hidden"  id="photo_small_" value="" />
                    
                    
                    
           <div id="queue_photo_small"></div>

		   <script type="text/javascript">
					$(function(){
							 
							
							
							$('#photo_small_').uploadifive({
									'auto'             : true,
									'buttonText' : 'Выберите и загрузите файл...',
									 
									'fileType'     : 'image/*',
									'fileSizeLimit' : '100 MB',
									'uploadLimit' : 0, 
									'queueSizeLimit' : 1, 
									'multi'          : false,
									'width'           : 200,
									'formData'         : {
														   
														  "PHPSESSID" : "<?php echo session_id(); ?>",
														  "mid" : "<?=$mid?>"
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
        
        		
				<label for="photo_small">
                  <a href="#" onclick=" pic_url=$('#photo_small').val(); if(!pic_url.match(/^http:/)) pic_url='/'+pic_url;  window.open(pic_url,'photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); return false;"><img src="/img/newdis/modules/eview16.png" width="18" height="18" alt="предварительный просмотр" title="предварительный просмотр" /></a>
                
                Фото малое<br /></label>
		<input name="photo_small" id="photo_small" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['photo_small'])); else echo 'img/no.gif';?>" /> 
		<!--<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=2','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_small.value=''; document.forms.inpp.photo_small.focus();">выбрать фото</a>-->
		<p />		


		<label for="photo_big">
          <a href="#" onclick=" pic_url=$('#photo_big').val(); if(!pic_url.match(/^http:/)) pic_url='/'+pic_url;  window.open(pic_url,'photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); return false;"><img src="/img/newdis/modules/eview16.png" width="18" height="18" alt="предварительный просмотр" title="предварительный просмотр" /></a>
        
        Фото большое<br /></label>
		<input name="photo_big" id="photo_big" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['photo_big'])); else echo 'img/no.gif';?>" /> 
		<!--<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=3','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_big.value=''; document.forms.inpp.photo_big.focus();">выбрать фото</a>-->
		  
         <p><a href="#" id="quick_uploader_select">...или перейдите к быстрой загрузке.</a></p>
        </div>
        
        <p />
				
		<label>Отображать на сайте: </label><input class="unbord" name="is_shown"  id="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_shown']==1)) echo 'checked'; ?> /><p />
		
		
		<label>Порядок показа:</label> <input name="ord" id="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $lang['ord']; else echo '1'; ?>" /><p />

	

	<input type="submit" name="doInp" value="Внести изменения">
	<input type="submit" name="doApply" value="Применить изменения">
	
	<?if($action==1){?>
		<input name="doNew" type="submit" value="Сохранить как новый элемент" />
		<?}?>
	
	</form>
	
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  frmvalidator.addValidation("name","req","Заполните  поле  Название фирмы!");    
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