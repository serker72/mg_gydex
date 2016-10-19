<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/linkitem.php');



if(!HAS_LINKS){
	header("Location: index.php");
	die();
}


//���������������� �����������
require_once('inc/adm_header.php');


$razd=new MmenuItem();

$ph=new LinkItem();

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



if($action==0){
	if(!isset($_GET['mid']))
		if(!isset($_POST['mid'])) {
			header('Location: index.php');
			die();
			//$mid=0;
		}
		else $mid = $_POST['mid'];		
	else $mid = $_GET['mid'];		
	$mid=abs((int)$mid);
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
	
		$razdel=$razd->GetItemById($mid, $lang_id);
	}else{
		header('Location: no_rights.php');
   	    die();		
	}
	
	/*if($razdel==false){
		header('Location: index.php');
		die();	
	}*/

}


	
if(($action==1)||($action==2)){
	//�������� id
	if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			header('Location: index.php');
			die();
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);
	
	
	
	
	//����� �����
	if(isset($_POST['doLang'])||isset($_GET['doLang'])){
		//���������� �������� ����
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 19)) {
			$ph->AddLanguage($id,$lang_id);
		}else{
			header('Location: no_rights.php');
	   	    die();
		}
		
		header('Location: ed_link.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
		die();
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 19)) {
	
	  $photo=$ph->GetItemById($id,$lang_id);
	  if($photo==false){
		  header('Location: index.php');
		  die();	
	  }
	}else{
		header('Location: no_rights.php');
	   	    die();
	}

	$mid=$photo['mid'];
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
	
		$razdel=$razd->GetItemById($mid, LANG_CODE);
	
	}else{
		header('Location: no_rights.php');
	   	    die();
	}
	//echo $razdel['parent_id']; die();
	
	if($razdel==false){
		header('Location: index.php');
		die();	
	}
}


if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//������� ����� ������
	$params=Array(); $lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$params['url']=SecStr($_POST['url']);
		//$lparams['title']=SecStr($_POST['title']);
	$lparams['small_txt']=SecStr($_POST['small_txt']);	
	
	$lparams['vnedr']=SecStr($_POST['vnedr']);	
	$lparams['otrasl']=SecStr($_POST['otrasl']);	
	
	
	$lparams['simple_code']=SecStr($_POST['simple_code']);	
	$lparams['photo_small']=SecStr($_POST['photo_small']);	
	$lparams['photo_big']=SecStr($_POST['photo_big']);	
	$lparams['lang_id']=$lang_id;	
	$params['use_simple_code']=abs((int)$_POST['use_simple_code']);
	$lparams['short_name']=SecStr($_POST['short_name']);
	
	$params['mid']=$mid;	
	
	$params['ord']=abs((int)$_POST['ord']);
	
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 19)) {
		$r_code=$ph->Add($params,$lparams);
		unset($razd);
	}else{
		header('Location: no_rights.php');
	   	    die();
	}
	if(isset($_POST['doInp']))
		header('Location: viewlinks.php?id='.$mid.'&from='.$from.'&to_page='.$to_page);
	else if(isset($_POST['doApply']))
		header('Location: ed_link.php?action=1&id='.$r_code.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);	
	die();
}


//����� ������� ������ ����
if(($action==1)&&isset($_GET['changeOrd'])){
	$params=Array(); $lparams=Array();
	if($_GET['changeOrd']=='0') {
		if((int)$photo['ord']<255)	$params['ord']=(int)$photo['ord']+1;
	}
	
	if($_GET['changeOrd']=='1') {
		if((int)$photo['ord']>0) $params['ord']=(int)$photo['ord']-1;
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 19)) {
		$ph->Edit($id, $params, $lparams);
	}else{
		header('Location: no_rights.php');
	   	    die();
	}
	
	header('Location: viewlinks.php?id='.$mid.'&from='.$from.'&to_page='.$to_page.'#'.$id);
	die();
}


if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	
	$params=Array(); $lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$params['url']=SecStr($_POST['url']);
		//$lparams['title']=SecStr($_POST['title']);
	$lparams['small_txt']=SecStr($_POST['small_txt']);	
	$lparams['simple_code']=SecStr($_POST['simple_code']);	
	$lparams['photo_small']=SecStr($_POST['photo_small']);	
	$lparams['photo_big']=SecStr($_POST['photo_big']);	
	$lparams['vnedr']=SecStr($_POST['vnedr']);	
	$lparams['otrasl']=SecStr($_POST['otrasl']);	
	$lparams['short_name']=SecStr($_POST['short_name']);
	
	
	$lparams['lang_id']=$lang_id;	
	$params['use_simple_code']=abs((int)$_POST['use_simple_code']);
	
	$params['mid']=abs((int)$_POST['mid']);//$mid;	
	
	$params['ord']=abs((int)$_POST['ord']);
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	
	if(isset($_POST['doNew'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 19)) {
			$id=$ph->Add($params,$lparams);
		}else{
			header('Location: no_rights.php');
	   	    die();
		}
	}else	{
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 19)) {	
			$ph->Edit($id, $params, $lparams);
		}else{
			header('Location: no_rights.php');
	   	    die();
		}
	}
	if(isset($_POST['doInp']))
		header('Location: viewlinks.php?id='.$mid.'&from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: ed_link.php?action=1&id='.$id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);	
	die();
}


if($action==2){
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 19)) {	
		$ph->Del($id);
	}
	
	header('Location: viewlinks.php?id='.$mid.'&from='.$from.'&to_page='.$to_page);
	die();
}




require_once('../classes/smarty/SmartyAdm.class.php');
//����� �� �������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'�������������� ������ - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//���� � ����������� �� ������� �������
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//�����-���
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);


$smarty->display('page_top.html');

?>

	
	<?
	//���������
	echo $razd->DrawNavig($mid, LANG_CODE,0,'������ ������');
	?>
	

    
    <script type="text/javascript" src="/swfupl/swfupload.js"></script>
	<script type="text/javascript" src="swfupl-js/swfupload.queue.js"></script>
	<script type="text/javascript" src="swfupl-js/fileprogress.js"></script>
	<script type="text/javascript" src="swfupl-js/handlers.js"></script>
	<script type="text/javascript">
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "/swfupl/swfupload.swf",
				upload_url: "swfupl-js/upload-link.php",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>",
								"mid" : "<?=$mid?>"
				},
				file_size_limit : "100 MB",
				file_types : "*.jpg;*.jpe;*.jpeg;*.gif;*.png",
				file_types_description : "���-�����������",
				file_upload_limit : 100,
				file_queue_limit : 1,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">�������� ����...</span>',
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
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
			
			//��������� ��������� ������ 
			<?if($action==0){?>
			  $("#quick_upload").css("display","block");
			  $("#full_upload").css("display","none");
			<?}else if($action==1){?>
			  $("#quick_upload").css("display","none");
			  $("#full_upload").css("display","block");
			<?}?>
			
			//����� ������ ��������, �������� �������
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
			
	     };
	</script>
    
	
	<form action="ed_link.php" method="post" id="inpp" name="inpp">
	<h1>�������������� ������</h1>
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	<?
	//��������������
	if($action==1){
	?>
		<strong>�������� ����:</strong>
		<select name="lang_id" id="lang_id">
		<?
			$lg=new LangGroup();
			echo $lg->GetItemsOpt($lang_id,'lang_name');
		?>
		</select>
		
		<input type="submit" name="doLang" value="�������">
		<p>
	<?
	}
	?>
	
			<strong>��������������� ������:</strong><br>
			<select name="mid" id="mid" style="width: 300px;">
				<?
				$parent_id=$mid; 
				$curr=0;
							$ml=new MmenuList();				
							echo $ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name');
				?>
			</select>
			<?
			//echo htmlspecialchars($ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name'));
			?>
			<p>
	
	<table width="98%" cellspacing="8" cellpadding="2" border="0">
	<tr>
	    <td width="60%" valign="top">
		<input class="unbord" type="radio" name="use_simple_code" value="0" <?if($action==0) echo 'checked'; else if($action==1){  if($photo['use_simple_code']==0) echo 'checked';}?>><strong>������������ ������:</strong><p>
		
		<strong>�����:</strong><br />
		    	<input name="url" type="text" size="60" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['url'])); ?>" /><p />	

		<strong>��������:</strong><br />
		    	<input name="name" type="text" size="60" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['name'])); ?>" /><p />	
		
		<strong>������� ��������:</strong><br />
		    	<input name="short_name" type="text" size="60" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['short_name'])); ?>" /><p />	
		
         <div id="quick_upload">
    <h3>��������� ������:</h3>
	
			<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend"></span>
			</div>
		<div id="divStatus"></div>
			<div>
				<span id="spanButtonPlaceHolder"></span>
                
                <input id="btnCancel" type="button" value="�������� ��� ��������" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
				
			</div>
            
            <p><a href="#" id="full_uploader_select">...��� �������� ��� ����������� ������.</a></p>
    </div>
    
    
    <div id="full_upload">
        <strong>������:</strong><br />
		<input name="photo_small" id="photo_small" type="text" size="60" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['photo_small'])); else echo 'img/no.gif';?>" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=2','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_small.value=''; document.forms.inpp.photo_small.focus();">������� ����</a><br />
		
        <input name="photo_big" id="photo_big" type="text" size="60" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['photo_big'])); else echo 'img/no.gif';?>" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=3','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_big.value=''; document.forms.inpp.photo_big.focus();">������� ����</a>
        
		
        <p><a href="#" id="quick_uploader_select">...��� ��������� � ������� ��������.</a></p>
        </div>
        <p />
        
        <strong>�������:</strong><br />
		        <textarea cols="80" rows="10" name="otrasl"><?if($action==1) echo htmlspecialchars(stripslashes($photo['otrasl']));?></textarea>
				<?php
				if($nonvisual==0){
					?>
                    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'otrasl',
					 
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
                	
		
		<strong>�������� ������:</strong><br />
		        <textarea cols="80" rows="10" name="small_txt"><?if($action==1) echo htmlspecialchars(stripslashes($photo['small_txt']));?></textarea>
				<?php
				if($nonvisual==0){
					?>
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'small_txt',
					 
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
                
                
                <label for="vnedr">���������:</label><br />
				<input name="vnedr" id="vnedr" type="text" size="60" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['vnedr'])); ?>" /> 
                <p />
		
		</td>
	    <td width="40%" valign="top">
		<input class="unbord" type="radio" name="use_simple_code" value="1" <?if(($action==1)&&($photo['use_simple_code']==1)) echo 'checked';?>><strong>������������ ���:</strong><p>
		
		<textarea cols="60" rows="25" name="simple_code"><?if($action==1) echo htmlspecialchars(stripslashes($photo['simple_code']));?></textarea>
		
		</td>
	</tr>
	</table>

	
	<strong>���������� �� �����:</strong> <input class="unbord" name="is_shown" type="checkbox" <? if(($action==1)&&($photo['is_shown']==1)) echo 'checked'; ?> /><p />
	   
		
		<strong>������� ������:</strong> <input name="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $photo['ord']; else echo '1'; ?>" /><p />
        
        <input name="doInp" type="submit" value="������ ���������" onclick="" />
		<input name="doApply" type="submit" value="��������� ���������" onclick="" />
		
		<?if($action==1){?>
		<input name="doNew" type="submit" value="��������� ��� ����� �������" />
		<?}?>
		
	</form>
	
	<script  language="JavaScript">  
 // var  frmvalidator    =  new  Validator("inpp");  
 // frmvalidator.addValidation("name","req","���������  ���� �������� ������!");    
</script>  
	
	
	
<?
//������ ������
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>