<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/solitem.php');


require_once('../classes/solfilegroup.php');


//административная авторизация
require_once('inc/adm_header.php');


 

$ph=new SolItem;

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



 
	
if(!isset($_GET['nonvisual']))
	if(!isset($_POST['nonvisual'])) $nonvisual = 0;
	else $nonvisual = $_POST['nonvisual'];		
else $nonvisual = $_GET['nonvisual'];		
$nonvisual=abs((int)$nonvisual);	



if($action==0){
	 

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
	
	
	
	 
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 20)) {
	
	  $photo=$ph->GetItemById($id,$lang_id);
	  if($photo==false){
		  header('Location: index.php');
		  die();	
	  }
	}else{
		header('Location: no_rights.php');
			 die();	
	}
	  
}


if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//заносим новую запись
	$params=Array(); $lparams=Array();
	$params['name']=SecStr($_POST['name']);
	 
	$params['txt']=SecStr($_POST['txt']);	
	
	$params['ord']=abs((int)$_POST['ord']);	
	
	$params['task']=SecStr($_POST['task']);	 
	$params['otrasl']=SecStr($_POST['otrasl']);	
	$params['num_users']=SecStr($_POST['num_users']);	
	$params['char_company']=SecStr($_POST['char_company']);	
	$params['ability']=SecStr($_POST['ability']);	
	
	
	if(isset($_POST['is_shown'])) $params['is_shown']=1; else $params['is_shown']=0;
	
	
	$params['parent_id']=abs((int)$_POST['parent_id']);	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 20)) {
		$r_code=$ph->Add($params);
		unset($razd);
	}else{
		header('Location: no_rights.php');
		 die();		
	}
	
	if(isset($_POST['doInp']))
		header('Location: viewsolution.php?from='.$from.'&to_page='.$to_page);
	else if(isset($_POST['doApply']))
		header('Location: ed_solution.php?action=1&id='.$r_code.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	die();
}


//смена порядка показа фото
if(($action==1)&&isset($_GET['changeOrd'])){
	$params=Array(); $lparams=Array();
	if($_GET['changeOrd']=='0') {
		if((int)$photo['ord']<255)	$params['ord']=(int)$photo['ord']+1;
	}
	
	if($_GET['changeOrd']=='1') {
		if((int)$photo['ord']>0) $params['ord']=(int)$photo['ord']-1;
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 20)) {
		$ph->Edit($id, $params );
	}else{
		header('Location: no_rights.php');
		 die();	
	}
	
	header('Location: viewsolution.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	die();
}


if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	
	$params=Array(); $lparams=Array();
	$params['name']=SecStr($_POST['name']);
	 
	$params['txt']=SecStr($_POST['txt']);	
	
	$params['ord']=abs((int)$_POST['ord']);	
	
	$params['task']=SecStr($_POST['task']);	 
	$params['otrasl']=SecStr($_POST['otrasl']);	
	$params['num_users']=SecStr($_POST['num_users']);	
	$params['char_company']=SecStr($_POST['char_company']);	
	$params['ability']=SecStr($_POST['ability']);	
	
	$params['parent_id']=abs((int)$_POST['parent_id']);	
	
	if(isset($_POST['is_shown'])) $params['is_shown']=1; else $params['is_shown']=0;
	
	if(isset($_POST['doNew'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 20)) {
		
			$id=$ph->Add($params );
		}else{
			header('Location: no_rights.php');
		 die();	
		}
	}else	{
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 20)) {
			$ph->Edit($id, $params);
		}else{
			header('Location: no_rights.php');
		 die();	
		}
	}
	if(isset($_POST['doInp']))
		header('Location: viewsolution.php?from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: ed_solution.php?action=1&id='.$id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	die();
}


if($action==2){
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 20)) {
		$ph->Del($id);
	}else{
		header('Location: no_rights.php');
	 	die();	
	}
	
	header('Location: viewsolution.php?from='.$from.'&to_page='.$to_page);
	die();
}




require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Редактирование продукта - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

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


$smarty->display('page_top.html');

?>

	
	<?
	//навигация
	//echo $razd->DrawNavig($mid, LANG_CODE,0,'правка статьи');
	?>
	
	
    
	<form action="ed_solution.php" method="post" id="inpp" name="inpp">
	<h1>Редактирование продукта</h1>
	
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
    <input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
    
    
    
    
    <script type="text/javascript" src="/swfupl/swfupload.js"></script>
	<script type="text/javascript" src="swfupl-js/swfupload.queue.js"></script>
	<script type="text/javascript" src="swfupl-js/fileprogress.js"></script>
	<script type="text/javascript" src="swfupl-js/handlers.js"></script>
	<script type="text/javascript">
		var swfu2;

		window.onload = function() {
			function RedrawIt(){
				location.reload();	
			}
			 
			var settings2 = {
				flash_url : "/swfupl/swfupload.swf",
				upload_url: "swfupl-js/upload-sol-file.php",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>",
								"id" : "<?=$id?>"
				},
				file_size_limit : "100 MB",
				file_types : "*.*",
				file_types_description : "Все файлы",
				file_upload_limit : 100,
				file_queue_limit : 100,
				custom_settings : {
					progressTarget : "fsUploadProgress2",
					cancelButtonId : "btnCancel2"
				},
				debug: false,

				// Button settings
				button_placeholder_id: "spanButtonPlaceHolder2",
				button_text: '<span class="theFont">Выберите файл...</span>',
				button_width: 130,
				button_height: 29,
				button_text_style: ".theFont { font-size: 12px; font-family: sans-serif; }",
				button_text_left_padding: 12,
				button_text_top_padding: 5,
				button_image_url: "/img/swfupl-img/TestImageNoText_130x29.png",
				
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : RedrawIt	// Queue plugin event
			};

			 
			swfu2 = new SWFUpload(settings2);
			
			//определим видимость блоков 
			<?if($action==0){?>
			  $("#quick_upload").css("display","block");
			  $("#full_upload").css("display","none");
			<?}else if($action==1){?>
			  $("#quick_upload").css("display","block");
			  $("#full_upload").css("display","none");
			<?}?>
			
			 
			$("#quick_uploader_select").bind("click",function(){
				//alert("2");	
			  $("#quick_upload").css("display","block");
			  $("#full_upload").css("display","none");
			  				 return false;
			});
			
	     };
	</script>
        
    
    
    
    
    
    
    
	
	<label for="name">Название:</label><br />
 <input name="name" type="text" size="60" maxlength="255" value="<? if($action==1) echo $photo['name']; else echo ''; ?>" /><p />

		<strong>Продукт в разделе:<br></strong>
			<select name="parent_id" id="parent_id" style="width: 300px;">
				<? if($action==1) $parent_id=$photo['parent_id'];
				  $curr=-1;
							$ml=new MmenuList();				
							echo $ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name');
				?>
			</select>
			<p>
            



				<strong>Описание:</strong><br />
		        <textarea cols="80" rows="40" name="txt"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt']));?></textarea>
				<?php
				if($nonvisual==0){
					?>
                    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt',
					 
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
				
				<strong>Задачи:</strong><br />
		        <textarea cols="80" rows="20" name="task"><?if($action==1) echo htmlspecialchars(stripslashes($photo['task']));?></textarea>
				<?php
				if($nonvisual==0){
					?>
                    <script type="text/javascript">
	CKEDITOR.replace( 'task',
					 
					 {
						 customConfig : '/ckeditor/config_custom.js',
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
                
                
                <strong>Отрасль:</strong><br />
		        <textarea cols="80" rows="20" name="otrasl"><?if($action==1) echo htmlspecialchars(stripslashes($photo['otrasl']));?></textarea>
				
		        <p />
                
               
               <label for="num_users">Количество пользователей:</label> <input name="num_users" type="text" size="40" maxlength="80" value="<? if($action==1) echo $photo['num_users']; else echo ''; ?>" /><p />
        
       
       			 <strong>Характеристики компании:</strong><br />
		        <textarea cols="80" rows="20" name="char_company"><?if($action==1) echo htmlspecialchars(stripslashes($photo['char_company']));?></textarea>
				
				
		        <p />
                
                
                <strong>Возможности:</strong><br />
		        <textarea cols="80" rows="20" name="ability"><?if($action==1) echo htmlspecialchars(stripslashes($photo['ability']));?></textarea>
				<?php
				if($nonvisual==0){
					?>
                    <script type="text/javascript">
	CKEDITOR.replace( 'ability',
					 
					 {
						 customConfig : '/ckeditor/config_custom.js',
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
                
                
                
				<label for="is_shown">Отображать на сайте:</label> <input class="unbord" name="is_shown" type="checkbox" <? if(($action==1)&&($photo['is_shown']==1)) echo 'checked'; ?> /><p />
	  	
	   
	   
		
		<label for="ord">Порядок показа:</label> <input name="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $photo['ord']; else echo '1'; ?>" /><p />
        
       
        <? if($action==1) {?>
        <h2>Файлы продукта</h2>
        
        <div class="fieldset flash" id="fsUploadProgress2">
			<span class="legend"></span>
			</div>
		<div id="divStatus2"></div>
			<div>
				<span id="spanButtonPlaceHolder2"></span>
                
                <input id="btnCancel2" type="button" value="Отменить все загрузки" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
				
			</div>
        <p />
        
        
        <?
		$_nfg=new SolFileGroup;
		
		echo $_nfg->ShowFiles($id, 'files/list.html', 'sol_files.php', '/sol_file.html', '/sol_file_view.html');
		
		?>
        
        <p />
        
        
        
        <?
		}
		?>
       
       
        
        <input name="doInp" type="submit" value="Внести изменения" onclick="" />
        <input name="doApply" type="submit" value="Применить изменения" onclick="" />
		   
	<?if($action==1){?>
		<input name="doNew" type="submit" value="Сохранить как новый элемент" />
		<?}?>
	</form>
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
 
 // frmvalidator.addValidation("name","req","Заполните  поле Название статьи!");    
 
</script>  
	
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>