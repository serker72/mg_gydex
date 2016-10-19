<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/banitem.php');
//include('../editor/fckeditor.php');


//административная авторизация
require_once('inc/adm_header.php');

$li=new BanItem();
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
		
		$li->AddLanguage($id,$lang_id);
		header('Location: ed_ban.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
		die();
	}
	
	
	$lang=$li->GetItemById($id, $lang_id);
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
	
	$li->Edit($id, $params, $lparams);
	
	header('Location: viewads.php?from='.$from.'&to_page='.$to_page);
	die();
}


if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//
	$params=Array();
	$lparams=Array();
	$lparams['small_txt']=SecStr($_POST['small_txt']);
	$lparams['photo_small']=SecStr($_POST['photo_small']);
	$params['url']=SecStr($_POST['url']);
	
	$params['kind']=abs((int)$_POST['kind']);
	$params['align_mode']=abs((int)$_POST['align_mode']);
	if(isset($_POST['break_after'])) $params['break_after']=1; else $params['break_after']=0;
	
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	$lparams['lang_id']=$lang_id;	
	
	$lparams['is_flash']=abs((int)$_POST['is_flash']);
	$lparams['flash_width']=abs((int)$_POST['flash_width']);
	$lparams['flash_height']=abs((int)$_POST['flash_height']);
	
	
	
	$params['ord']=abs((int)$_POST['ord']);
	
	$r_code=$li->Add($params,$lparams);
	
	
	if(isset($_POST['doInp']))
		header('Location: viewads.php?from='.$from.'&to_page='.$to_page);
	else if(isset($_POST['doApply']))
		header('Location: ed_ban.php?action=1&id='.$r_code.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	die();
	
}

if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	//
	$params=Array();
	$lparams=Array();
	$lparams['small_txt']=SecStr($_POST['small_txt']);
	$lparams['photo_small']=SecStr($_POST['photo_small']);
		$params['url']=SecStr($_POST['url']);
		
		$params['kind']=abs((int)$_POST['kind']);
		$params['align_mode']=abs((int)$_POST['align_mode']);
		if(isset($_POST['break_after'])) $params['break_after']=1; else $params['break_after']=0;
	
	
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	
	$lparams['lang_id']=$lang_id;	
	
	$lparams['is_flash']=abs((int)$_POST['is_flash']);
	$lparams['flash_width']=abs((int)$_POST['flash_width']);
	$lparams['flash_height']=abs((int)$_POST['flash_height']);
	
	
	
	$params['ord']=abs((int)$_POST['ord']);
	
	
	if(isset($_POST['doNew']))
		$id=$li->Add($params,$lparams);
	else	
		$li->Edit($id,$params,$lparams);
	
	if(isset($_POST['doInp']))
		header('Location: viewads.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: ed_ban.php?action=1&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	
	die();
	
}


if($action==2){
//удаление
	$li->Del($id);
	unset($li);
	header('Location: viewads.php?from='.$from.'&to_page='.$to_page);
	die();	
}


require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Редактирование баннера - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=23;
$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличия модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);







//контекстные команды
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;


 
 
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "ed_ban.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);




//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
 
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
 $_bc->AddContext(new BcItem('Список баннеров', 'viewads.php'));

$_bc->AddContext(new BcItem('Правка баннера', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);







$smarty->display('page_top.html');

?>

	
	<form action="ed_ban.php" method="post" name="inpp" id="inpp">
	<h1>Редактирование баннера</h1>
	
	
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
    
    
    <div id="quick_upload">
    <h3>Загрузите баннер:</h3>
	
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
														   
														  "PHPSESSID" : "<?php echo session_id(); ?>"
														   
														 },
									'queueID'          : 'queue_photo_small',
									'uploadScript'     : 'swfupl-js/upload_banner.php',
									'onUploadComplete' : function(file, data) { 
											eval(data)
									
									}
								});
							
							 
					 
					});
					 </script>
    
            <p><a href="#" id="full_uploader_select">...или выберите уже загруженный баннер.</a></p>
    </div>
    
    
    
	<div id="full_upload">
    <h3>Выберите баннер:</h3>
    
    
    
		<label for="photo_small">
         <a href="#" onclick=" pic_url=$('#photo_small').val(); if(!pic_url.match(/^http:/)) pic_url='/'+pic_url;  window.open(pic_url,'photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); return false;"><img src="/img/newdis/modules/eview16.png" width="18" height="18" alt="предварительный просмотр" title="предварительный просмотр" /></a>
        
        Баннер:<br /></label>
         
		<input name="photo_small" id="photo_small" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['photo_small'])); else echo 'img/no.gif';?>" /> 
		<!--<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=2','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_small.value=''; document.forms.inpp.photo_small.focus();">выбрать фото</a>-->
        
        
        <p><a href="#" id="quick_uploader_select">...или перейдите к быстрой загрузке.</a></p>
        </div>
        
        <p />	
        
        
        
        <strong>Местоположение баннера:</strong><br />
		
        <label for="kind1">Шапка сайта: </label>
        <input type="radio" name="kind" id="kind1" value="1" <? if(($action==1)&&($lang['kind']==1)) echo 'checked="checked"';?>  />
        <br />

       <label for="kind0">Подвал сайта:</label>
        <input type="radio" name="kind"  id="kind0" value="0" <? if(($action==0)||(($action==1)&&($lang['kind']==0))) echo 'checked="checked"';?> /><br />
		<div style="margin-left:30px; background-color:silver; border:1px solid gray; width:300px;">
             <label for="align_mode">Выравнивать (для баннеров в подвале сайта)</label><br />
            <select name="align_mode" id="align_mode">
                <option value="0"  <? if(($action==0)||(($action==1)&&($lang['align_mode']==0))) echo 'selected="selected"';?>>по левому краю</option>
                <option value="1"  <? if((($action==1)&&($lang['align_mode']==1))) echo 'selected="selected"';?>>по правому краю</option>
            </select>
            <br />
            
            
            <label for="break_after">Разрыв строки после баннера: </label><input class="unbord" name="break_after" id="break_after" type="checkbox" <? if(($action==1)&&($lang['break_after']==1)) echo 'checked'; ?> />
            <br />
        </div>
        
       <p />
        
        
       

        
        
        <strong>Вид баннера:</strong><br />
		<label for="is_flash0">Изображение (*.jpg;*.jpe;*.jpeg;*.gif;*.png):</label>
        <input type="radio" name="is_flash" id="is_flash0"  value="0" <? if(($action==0)||(($action==1)&&($lang['is_flash']==0))) echo 'checked="checked"';?> /><br />
		
        <label for="is_flash1">Flash (*.swf;*.flv): </label>
        <input type="radio" name="is_flash"  id="is_flash1" value="1" <? if(($action==1)&&($lang['is_flash']==1)) echo 'checked="checked"';?>  /><p />
		<strong>Размеры flash-ролика:</strong><br />
		<label for="flash_width">ширина:</label> <input name="flash_width" id="flash_width"  type="text" size="10" maxlength="10" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['flash_width'])); if($action==0) echo '0'; ?>" /><br />
		<label for="flash_height">высота:</label> <input name="flash_height" id="flash_height" type="text" size="10" maxlength="10" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['flash_height'])); if($action==0) echo '0';?>" />
        
		<p />	
        
        
        
        	

	<label for="url">URL для баннера:<br /></label>
		    	<input name="url" id="url" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($lang['url'])); ?>" /><p />	
	

	<label for="small_txt">Текст к баннеру:<br /></label>
		       <textarea cols="80" rows="20" name="small_txt" id="small_txt"><? if($action==1) echo htmlspecialchars(stripslashes($lang['small_txt']));?></textarea>
               
                <?php
				if($nonvisual==0){
				?>
				 <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
                    <script type="text/javascript">
	CKEDITOR.replace( 'small_txt',
					 
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
				}
				?>
		        <p />
				
				
				
		<label for="is_shown">Отображать на сайте: </label><input class="unbord" name="is_shown"  id="is_shown" type="checkbox" <? if(($action==1)&&($lang['is_shown']==1)) echo 'checked'; ?> /><p />
		
		
		<label for="ord">Порядок показа:</label> <input name="ord" id="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $lang['ord']; else echo '1'; ?>" /><p />

	

	<input type="submit" name="doInp" value="Внести изменения">
	<input type="submit" name="doApply" value="Применить изменения">
	
	<?if($action==1){?>
		<input name="doNew" type="submit" value="Сохранить как новый элемент" />
		<?}?>
	
	</form>
	
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
  //frmvalidator.addValidation("url","req","Заполните  поле URL для баннера!");    
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