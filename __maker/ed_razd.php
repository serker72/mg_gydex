<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/allmenu_template_group.php');

//административная авторизация
require_once('inc/adm_header.php');

$razd=new MmenuItem();

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


if(!isset($_GET['parent_id']))
		if(!isset($_POST['parent_id'])) {
			//header('Location: index.php');
			//die();
			$parent_id=0;
		}
		else $parent_id = $_POST['parent_id'];		
	else $parent_id = $_GET['parent_id'];		
	$parent_id=abs((int)$parent_id);

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
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 11)) {
		  $razd->AddLanguage($id,$lang_id);
		  
		  header('Location: ed_razd.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual.'&parent_id='.$parent_id);
		  die();
		}else{
			header('Location: no_rights.php');
    	    die();		
		}
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
	
		$razdel=$razd->GetItemById($id, $lang_id);
	}else{
		header('Location: no_rights.php');
   	    die();		
	}

	
	//echo $razdel['parent_id']; die();
	
	if($razdel==false){
		header('Location: index.php');
		die();	
	}
	$parent_id=$razdel['parent_id'];
}


if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//заносим новую запись
	$params=Array(); $lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['title']=SecStr($_POST['title']);
	$lparams['txt']=SecStr($_POST['txt']);	
	
	$lparams['txt2']=SecStr($_POST['txt2']);	
	$lparams['txt3']=SecStr($_POST['txt3']);	
	
	$lparams['photo_small']=SecStr($_POST['photo_small']);	
	$lparams['photo_for_goods']=SecStr($_POST['banner_rus']);	
	$lparams['lang_id']=$lang_id;	
	
	$params['parent_id']=$parent_id;	
	
	$params['template_id']=abs((int)$_POST['template_id']);
	
	$params['ord']=abs((int)$_POST['ord']);
	if(HAS_URLS) $params['path']=SecStr($_POST['path']);
	
	
	if(HAS_PRICE)	if($_POST['usl']==1) $params['is_price']=1; else $params['is_price']=0;
	if(HAS_NEWS)	if($_POST['usl']==2) $params['is_news']=1;	else $params['is_news']=0;
	if(HAS_LINKS) 	if($_POST['usl']==3) $params['is_links']=1;	else $params['is_links']=0;
	
	
	if(HAS_BASKET&&HAS_PRICE) if(isset($_POST['is_basket'])) $params['is_basket']=1; else $params['is_basket']=0;
	
	if(HAS_PAPERS) if(isset($_POST['is_papers'])) $params['is_papers']=1; else $params['is_papers']=0;
	if(HAS_GALLERY) if(isset($_POST['is_gallery'])) $params['is_gallery']=1; else $params['is_gallery']=0;
	if(HAS_FEEDBACK_FORMS) if(isset($_POST['is_feedback_forms'])) $params['is_feedback_forms']=1; else $params['is_feedback_forms']=0;
	
	if(HAS_FEEDBACK_FORMS) if(isset($_POST['is_otzyv'])) $params['is_otzyv']=1; else $params['is_otzyv']=0;
	
	if(HAS_FEEDBACK_FORMS) if(isset($_POST['is_callback'])) $params['is_callback']=1; else $params['is_callback']=0;
	
	if(HAS_FEEDBACK_FORMS) if(isset($_POST['is_faq'])) $params['is_faq']=1; else $params['is_faq']=0;
	
	
		if(isset($_POST['is_new_window'])) $params['is_new_window']=1; else $params['is_new_window']=0;
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	
		if(isset($_POST['is_pics_list'])) $params['is_pics_list']=1; else $params['is_pics_list']=0;
		
	if(isset($_POST['do_index'])) $lparams['do_index']=1; else $lparams['do_index']=0;	
	if(isset($_POST['do_follow'])) $lparams['do_follow']=1; else $lparams['do_follow']=0;
	$lparams['meta_keywords']=SecStr($_POST['meta_keywords']);
	$lparams['meta_description']=SecStr($_POST['meta_description']);
		
	if(isset($_POST['show_first'])) $params['show_first']=1; else $params['show_first']=0;	
	
	if(isset($_POST['is_to_another_url'])) $params['is_to_another_url']=1; else $params['is_to_another_url']=0;
	$params['another_path']=SecStr($_POST['another_path']);
	
	
	$lparams['name_2']=SecStr($_POST['name_2']);
	$params['ord_2']=abs((int)$_POST['ord_2']);
	$params['ord_3']=abs((int)$_POST['ord_3']);
	
	//if(isset($_POST['is_hor'])) $params['is_hor']=1; else $params['is_hor']=0;	
	 

	 
	if(isset($_POST['is_menu_1'])) $params['is_menu_1']=1; else $params['is_menu_1']=0; 
	if(isset($_POST['is_menu_2'])) $params['is_menu_2']=1; else $params['is_menu_2']=0; 
	if(isset($_POST['is_menu_3'])) $params['is_menu_3']=1; else $params['is_menu_3']=0; 
	if(isset($_POST['is_menu_4'])) $params['is_menu_4']=1; else $params['is_menu_4']=0; 
	
	
	$params['priority']=abs((float)$_POST['priority']);
	
	if($_POST['changefreq']=="") $params['changefreq']=NULL; else $params['changefreq']=SecStr($_POST['changefreq']);
	
	 
	$razd=new MmenuItem();
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 11)) {
	
	  $r_code=$razd->Add($params,$lparams);
	  unset($razd);
	  if(isset($_POST['doInp']))
		  header('Location: razds.php?id='.$parent_id.'&from='.$from.'&to_page='.$to_page);
	  else if(isset($_POST['doApply']))
		  header('Location: ed_razd.php?action=1&lang_id='.$lang_id.'&nonvisual='.$nonvisual.'&id='.$r_code.'&parent_id='.$parent_id.'&from='.$from.'&to_page='.$to_page);
	  die();
	}else{
		header('Location: no_rights.php');
			    	  die();	
	}
}

//смена порядка показа 
if(($action==1)&&isset($_GET['changeOrd'])){
	$params=Array(); $lparams=Array();
	if($_GET['changeOrd']=='0') {
		if((int)$razdel['ord']<245)	$params['ord']=(int)$razdel['ord']+10;
	}
	
	if($_GET['changeOrd']=='1') {
		if((int)$razdel['ord']>10) $params['ord']=(int)$razdel['ord']-10;
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 11)) {
	  $razd->Edit($id, $params, $lparams);
	  
	  header('Location: razds.php?id='.$razdel['parent_id'].'&from='.$from.'&to_page='.$to_page.'#'.$id);
	  die();
	}else{
		header('Location: no_rights.php');
			    	  die();	
	}
}


if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	
	$params=Array(); $lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	$lparams['title']=SecStr($_POST['title']);
	$lparams['txt']=SecStr($_POST['txt']);	
	
	$lparams['txt2']=SecStr($_POST['txt2']);	
	$lparams['txt3']=SecStr($_POST['txt3']);
	
	$lparams['photo_small']=SecStr($_POST['photo_small']);	
	$lparams['photo_for_goods']=SecStr($_POST['banner_rus']);	
	$lparams['lang_id']=$lang_id;	
	
	$params['parent_id']=abs((int)$_POST['parent_id']);	
	$params['template_id']=abs((int)$_POST['template_id']);
	
	//$lparams['mid']=$id;	
	
	if(HAS_URLS) $params['path']=SecStr($_POST['path']);
	$params['ord']=abs((int)$_POST['ord']);
	
	
	if(HAS_PRICE)	if($_POST['usl']==1) $params['is_price']=1; else $params['is_price']=0;
	if(HAS_NEWS)	if($_POST['usl']==2) $params['is_news']=1;	else $params['is_news']=0;
	if(HAS_LINKS) 	if($_POST['usl']==3) $params['is_links']=1;	else $params['is_links']=0;
	
	
	if(HAS_BASKET&&HAS_PRICE) if(isset($_POST['is_basket'])) $params['is_basket']=1; else $params['is_basket']=0;
	
	if(HAS_PAPERS) if(isset($_POST['is_papers'])) $params['is_papers']=1; else $params['is_papers']=0;
	if(HAS_GALLERY) if(isset($_POST['is_gallery'])) $params['is_gallery']=1; else $params['is_gallery']=0;
	if(HAS_FEEDBACK_FORMS) if(isset($_POST['is_feedback_forms'])) $params['is_feedback_forms']=1; else $params['is_feedback_forms']=0;
	if(HAS_FEEDBACK_FORMS) if(isset($_POST['is_otzyv'])) $params['is_otzyv']=1; else $params['is_otzyv']=0;
	
	if(HAS_FEEDBACK_FORMS) if(isset($_POST['is_callback'])) $params['is_callback']=1; else $params['is_callback']=0;
	
	if(HAS_FEEDBACK_FORMS) if(isset($_POST['is_faq'])) $params['is_faq']=1; else $params['is_faq']=0;
	
	
	if(isset($_POST['is_new_window'])) $params['is_new_window']=1; else $params['is_new_window']=0;
	if(isset($_POST['is_pics_list'])) $params['is_pics_list']=1; else $params['is_pics_list']=0;
	
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	
	if(isset($_POST['do_index'])) $lparams['do_index']=1; else $lparams['do_index']=0;	
	if(isset($_POST['do_follow'])) $lparams['do_follow']=1; else $lparams['do_follow']=0;
	$lparams['meta_keywords']=SecStr($_POST['meta_keywords']);
	$lparams['meta_description']=SecStr($_POST['meta_description']);
	
	if(isset($_POST['show_first'])) $params['show_first']=1; else $params['show_first']=0;	
	
	if(isset($_POST['is_menu_1'])) $params['is_menu_1']=1; else $params['is_menu_1']=0; 
	if(isset($_POST['is_menu_2'])) $params['is_menu_2']=1; else $params['is_menu_2']=0; 
	if(isset($_POST['is_menu_3'])) $params['is_menu_3']=1; else $params['is_menu_3']=0; 
	if(isset($_POST['is_menu_4'])) $params['is_menu_4']=1; else $params['is_menu_4']=0; 
	
	
	
	if(isset($_POST['is_to_another_url'])) $params['is_to_another_url']=1; else $params['is_to_another_url']=0;
	$params['another_path']=SecStr($_POST['another_path']);
	
	$lparams['name_2']=SecStr($_POST['name_2']);
	$params['ord_2']=abs((int)$_POST['ord_2']);
	$params['ord_3']=abs((int)$_POST['ord_3']);
	
 	
	$params['priority']=abs((float)$_POST['priority']);
	
	if($_POST['changefreq']=="") $params['changefreq']=NULL; else $params['changefreq']=SecStr($_POST['changefreq']);
	
	
	if(isset($_POST['doNew'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 11)) {
			$id=$razd->Add($params,$lparams);
		}else{
			header('Location: no_rights.php');
		  die();	
		}
	}else	{
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 11)) {
			$razd->Edit($id, $params, $lparams);
		}else{
			header('Location: no_rights.php');
		  die();	
		}	
	}
	if(isset($_POST['doInp']))
		header('Location: razds.php?id='.$parent_id.'&from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: ed_razd.php?action=1&lang_id='.$lang_id.'&nonvisual='.$nonvisual.'&id='.$id.'&parent_id='.abs((int)$_POST['parent_id']).'&from='.$from.'&to_page='.$to_page);
	die();
}


if($action==2){
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 11)) {
		$razd->Del($id);
	
		header('Location: razds.php?id='.$parent_id.'&from='.$from.'&to_page='.$to_page);
		die();
	}else{
		header('Location: no_rights.php');
		  die();	
	}
}




//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Правка раздела сайта - '.SITETITLE);
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
	if($action==0) echo $razd->DrawNavig(0, LANG_CODE,0);
	else echo $razd->DrawNavig($id, LANG_CODE,0);
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
				upload_url: "swfupl-js/upload-razd.php",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>",
								"mid" : "<? if($action==0) echo $parent_id; else echo $id;?>"
				},
				file_size_limit : "100 MB",
				file_types : "*.jpg;*.jpe;*.jpeg;*.gif;*.png",
				file_types_description : "Веб-изображения",
				file_upload_limit : 100,
				file_queue_limit : 1,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_placeholder_id: "spanButtonPlaceHolder",
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
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
			
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
			
	     };
	</script>
    	
	
	<form action="ed_razd.php" method="post" id="inpp" name="inpp">
	<h1>Редактирование раздела</h1>
	
	
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
    <input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	
	
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	<?
	//многоязычность
	if($action==1){
	?>
		<strong>Выберите язык:</strong>
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
	
	
	
	<strong>Название раздела:<br /></strong>
		    	<input name="name" id="name" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($razdel['name'])); ?>" /><p />	

	<strong>Title раздела(&lt;title&gt;):<br /></strong>
		    	<input name="title" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($razdel['title'])); ?>" /><p />
                
                
    <strong>Keywords раздела:<br /></strong>
		    	<textarea name="meta_keywords" cols="80" rows="5"><? if($action==1) echo stripslashes(htmlspecialchars($razdel['meta_keywords'])); ?></textarea><p />
                
    <strong>Description раздела:<br /></strong>
		    	<textarea name="meta_description" cols="80" rows="5"><? if($action==1) echo stripslashes(htmlspecialchars($razdel['meta_description'])); ?></textarea><p />	
	
    <strong>Параметры meta robots:</strong><br />
				index: <input name="do_index" type="checkbox" <? if(($action==1)&&($razdel['do_index']==1)) echo 'checked'; elseif($action==0) echo 'checked';?> />
                
                follow: <input name="do_follow" type="checkbox" <? if(($action==1)&&($razdel['do_follow']==1)) echo 'checked'; elseif($action==0) echo 'checked'; ?> />
                <p />
                
                
                
                <strong>Параметры sitemap.xml:</strong><br>
                <label for="priority">Priority:</label> <input type="text" id="priority" name="priority"  size="5" maxlength="3" value="<? if($action==1) echo stripslashes(htmlspecialchars($razdel['priority'])); else echo "0.5"; ?>" />
                
                &nbsp;&nbsp;
                <label for="changefreq">changefreq:</label> 
                
                <select name="changefreq" id="changefreq" >
                	<option value="" <? if(($action==1)&&($razdel['changefreq']=="")) echo "selected"; elseif($action==0) echo "selected"; ?>  ></option>
                    <option value="always" <? if(($action==1)&&($razdel['changefreq']=="always")) echo "selected"; ?>  >always</option>
                    <option value="hourly" <? if(($action==1)&&($razdel['changefreq']=="hourly")) echo "selected"; ?>  >hourly</option>
                    <option value="daily" <? if(($action==1)&&($razdel['changefreq']=="daily")) echo "selected"; ?>  >daily</option>
                    <option value="weekly" <? if(($action==1)&&($razdel['changefreq']=="weekly")) echo "selected"; ?>  >weekly</option>
                    <option value="monthly" <? if(($action==1)&&($razdel['changefreq']=="monthly")) echo "selected"; ?>  >monthly</option>
                    <option value="yearly" <? if(($action==1)&&($razdel['changefreq']=="yearly")) echo "selected"; ?>  >yearly</option>
                    <option value="never" <? if(($action==1)&&($razdel['changefreq']=="never")) echo "selected"; ?>  >never</option>
                </select>
                
                
                 <p />
                
    

	<?if(HAS_URLS) {?>
	<strong>URL раздела:<br /></strong>
		    	<input name="path" id="path" type="text" size="80" maxlength="80" value="<? if($action==1) echo stripslashes(htmlspecialchars($razdel['path'])); ?>" />
                
                 <input type="button" id="generate" value="сгенерировать" />
                <p />	
                
                
                <script type="text/javascript">
    $(function(){
		var value='';
		function Transliterate(name){
			value='';	
			var obj={
				"Аа":"a",
				"Бб":"b",
				"Вв":"v",
				"Гг":"g",
				"Дд":"d",
				"Ее":"e",
				"Жж":"zh",
				"Зз":"z",
				"Ии":"i",
				"Кк":"k",
				"Лл":"l",
				"Мм":"m",
				"Нн":"n",
				"Оо":"o",
				"Пп":"p",
				"Рр":"r",
				"Сс":"s",
				"Тт":"t",
				"Уу":"u",
				"Фф":"f",
				"Хх":"h",
				"Цц":"tc",
				"Чч":"ch",
				"Щщ":"csh",
				"Шш":"sh",
				"Ъъ":"_",
				"Ьь":"_",
				"Ыы":"y",
				"Ээ":"e",
				"Юю":"yu",
				"Яя":"ya",
				"Ёё":"yo",
				"Йй":"y",
				" .,/\\*:;!&?><":"_"
				
			};
			
			for(i=0;i<name.length;i++){
				letter=name.substr(i,1);
				new_letter='';	
				$.each(obj,function(index,elem){
					
					if(index.indexOf(letter)!=-1){
						//alert(letter+index);
						new_letter=elem;
						value=value+elem;
						return false;	
					}
					return true;
				});
				if(new_letter=='') value=value+letter;
			}
			return value;
		}
		
		$("#name").bind("change", function(){
			if($("#path").val()==""){
				$("#path").val( Transliterate($("#name").val()));
			}
		});
		
		$("#generate").bind("click", function(){
			
				$("#path").val( Transliterate($("#name").val()));
			
		});
		
	});
    </script>  
	<?}?>
			
            
      <strong>Переходить по другому URL:</strong> <input name="is_to_another_url" type="checkbox" <? if(($action==1)&&($razdel['is_to_another_url']==1)) echo 'checked'; ?> />
    <br />

    
    <strong>Другой URL для перехода:<br /></strong>
		    	<input name="another_path" id="another_path" type="text" size="100" maxlength="1024" value="<? if($action==1) echo stripslashes(htmlspecialchars($razdel['another_path'])); ?>" />
               
                <p />	
                       
            
            
			<strong>Местонахождение раздела:<br></strong>
			<select name="parent_id" id="parent_id" style="width: 300px;">
				<?if($action==1) $parent_id=$razdel['parent_id'];
				if($action==1) $curr=$id;
				else $curr=0;
							$ml=new MmenuList();				
							echo $ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name');
				?>
			</select>
			<p>
            
            <strong>Шаблон страницы раздела:</strong><br>
			<!--<a href="#" onclick="window.open(''+$('#template_'+$('#template_id').val()).val(),'preview_photo', 'width=1100, height=700,scrollbars=yes,resizable=yes'); return false;"><img src="../img/folder-gr.gif" alt="просмотреть Шаблон" title="просмотреть Шаблон" /></a>
            -->
            <select name="template_id" id="template_id" style="width: 300px;">
				<?
				 
				?>
				
				<?
							$_pt=new AllmenuTemplateGroup;	
							echo $_pt->GetItemsOpt($razdel['template_id'], 'name');
				?>
			</select>
			<p>


				<strong>Текст раздела 1:<br /></strong>
                <textarea cols="90" rows="30" name="txt"><?if($action==1) echo htmlspecialchars(stripslashes($razdel['txt']));?></textarea>
                
		        <?php
				if($nonvisual==0){
					?>
                    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt',
					 
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
                
                
                <strong>Текст раздела 2:<br /></strong>
                <textarea cols="90" rows="30" name="txt2"><?if($action==1) echo htmlspecialchars(stripslashes($razdel['txt2']));?></textarea>
                
		        <?php
				if($nonvisual==0){
					?>
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt2',
					 
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
                
                <strong>Текст раздела 3:<br /></strong>
                <textarea cols="90" rows="30" name="txt3"><?if($action==1) echo htmlspecialchars(stripslashes($razdel['txt3']));?></textarea>
                
		        <?php
				if($nonvisual==0){
					?>
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt3',
					 
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
				
				<label for="is_shown">Отображать на сайте:</strong> <input name="is_shown" id="is_shown" type="checkbox" <? if(($action==1)&&($razdel['is_shown']==1)) echo 'checked'; ?> />
               
                <p />

		<input name="is_pics_list" type="checkbox" <? if(($action==1)&&($razdel['is_pics_list']==1)) echo 'checked'; ?> /><strong>Отображать подразделы списком миниатюр<br /></strong>

	     <div id="quick_upload">
    <h3>Загрузите кнопку:</h3>
	
			<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend"></span>
			</div>
		<div id="divStatus"></div>
			<div>
				<span id="spanButtonPlaceHolder"></span>
                
                <input id="btnCancel" type="button" value="Отменить все загрузки" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
				
			</div>
            
            <p><a href="#" id="full_uploader_select">...или выберите уже загруженную кнопку.</a></p>
    </div>
    


	<div id="full_upload">
        <strong>Кнопка:<br /></strong>
		<input name="photo_small" id="photo_small" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($razdel['photo_small'])); else echo 'img/no.gif';?>" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=2','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_small.value=''; document.forms.inpp.photo_small.focus();">выбрать фото</a>

        
		
        <p><a href="#" id="quick_uploader_select">...или перейдите к быстрой загрузке.</a></p>
        </div>
        <p />	


<!--
		 <strong>Фото с описанием раздела<br /></strong>
		<input name="banner_rus" id="banner_rus" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($razdel['photo_for_goods'])); else echo 'img/no.gif';?>" /> 
		<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=0','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.banner_rus.value=''; document.forms.inpp.banner_rus.focus();">выбрать фото</a><p>
	
    -->
    
     
				 <input class="unbord" type="checkbox" id="is_menu_1" name="is_menu_1" value="1"  <?if(($action==1)&&($razdel['is_menu_1']==1)) echo 'checked';?> /><label for="is_menu_1">Содержится в меню 1</label> <br />

     		
           <input class="unbord" type="checkbox" id="is_menu_2" name="is_menu_2" value="1"  <?if(($action==1)&&($razdel['is_menu_2']==1)) echo 'checked';?> /><label for="is_menu_2">Содержится в меню 2</label>
           
           &nbsp;&nbsp;
           	<label for="name_2">Название для меню 2: </label><input type="text" name="name_2" id="name_2" value="<? if($action==1) echo htmlspecialchars(stripslashes($razdel['name_2']));?>" size="40" maxlength="255" />
           
           
           &nbsp;&nbsp;
           <label for="ord_2">Порядок показа:</strong> <input name="ord_2" id="ord_2" type="text" size="3" maxlength="3" value="<? if($action==1) echo $razdel['ord_2']; else echo '0'; ?>" />
           
            
            <br />


    
    
				 <input class="unbord" type="checkbox" id="is_menu_3" name="is_menu_3" value="1"  <?if(($action==1)&&($razdel['is_menu_3']==1)) echo 'checked';?> /><label for="is_menu_3">Содержится в меню 3</label> 
                 
                 &nbsp;&nbsp;
           <label for="ord_3">Порядок показа:</strong> <input name="ord_3" id="ord_3" type="text" size="3" maxlength="3" value="<? if($action==1) echo $razdel['ord_3']; else echo '0'; ?>" />
                 
                 <br />


<input class="unbord" type="checkbox" id="is_menu_4" name="is_menu_4" value="1"  <?if(($action==1)&&($razdel['is_menu_4']==1)) echo 'checked';?> /><label for="is_menu_4">Содержится в меню 4</label> <br />

                  
               
              
                 
				 
<p>

				
		<table width="*" border="0" cellspacing="2" cellpadding="2" style="background-color: Gray;">
		<tr>
		<td width="*">
		<?
		
		?>
		<input class="unbord" type="radio" name="usl" value="0" <?if((($action==1)&&($razdel['is_price']==0)&&($razdel['is_news']==0)&&($razdel['is_links']==0))) echo 'checked'; else if($action==0) echo 'checked';?>   />Обычный раздел<br />
		<?
		//}
		
		if(HAS_PRICE){
		?>
		<input class="unbord" type="radio" name="usl" value="1" <?if((($action==1)&&($razdel['is_price']==1))) echo 'checked';?>   /><img src="../img/catalog-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит каталог товаров<br />
		<?
		}
		if(HAS_PRICE&&HAS_BASKET){
		?>	
		
			&nbsp;&nbsp;<input class="unbord" name="is_basket" type="checkbox" <? if(($action==1)&&($razdel['is_basket']==1)) echo 'checked'; ?> /><img src="../img/basket.gif" alt="" width="16" height="14" hspace="2" vspace="0" border="0">Можно заказывать товар<br />
		<?
		}
		if(HAS_NEWS){
		?>

         <input class="unbord" type="radio" name="usl" value="2" <?if((($action==1)&&($razdel['is_news']==1))) echo 'checked';?>  /><img src="../img/news-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит новости<br />	
		<?
		}
		if(HAS_LINKS){
		?>
	
        <input class="unbord" type="radio" name="usl" value="3" <?if((($action==1)&&($razdel['is_links']==1))) echo 'checked';?>  /><img src="../img/links-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит ссылки<br />
		<?
		}
		
		if(HAS_PAPERS){
		?>	
		
			<input class="unbord" name="is_papers" type="checkbox" <? if(($action==1)&&($razdel['is_papers']==1)) echo 'checked'; ?> /><img src="../img/papers-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит каталог статей<br />
		<?
		}
		
		if(HAS_GALLERY){
		?>	
		
			<input class="unbord" name="is_gallery" type="checkbox" <? if(($action==1)&&($razdel['is_gallery']==1)) echo 'checked'; ?> /><img src="../img/photos-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит фотогалерею<br />
		<?
		}
		if(HAS_FEEDBACK_FORMS){
		?>	
		
			<input class="unbord" name="is_feedback_forms" type="checkbox" <? if(($action==1)&&($razdel['is_feedback_forms']==1)) echo 'checked'; ?> /><img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит формы обратной связи<br />
            
            <input class="unbord" name="is_otzyv" type="checkbox" <? if(($action==1)&&($razdel['is_otzyv']==1)) echo 'checked'; ?> /><img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewotzyv.php" target="_blank">отзывы</a><br />
            
            
            <input class="unbord" name="is_callback" type="checkbox" <? if(($action==1)&&($razdel['is_callback']==1)) echo 'checked'; ?> /><img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит форму обратного звонка<br />
            
            
            <input class="unbord" name="is_faq" type="checkbox" <? if(($action==1)&&($razdel['is_faq']==1)) echo 'checked'; ?> /><img src="../img/feedback-16.gif" alt="" width="16" height="16" hspace="2" vspace="0" border="0">Содержит <a href="viewfaq.php" target="_blank">ЧаВо</a><br />
		<?
		}
		?>
		</td>
		</tr>
		</table>
        <p />		

		
		<input class="unbord" name="is_new_window" type="checkbox" <? if(($action==1)&&($razdel['is_new_window']==1)) echo 'checked'; ?> /><strong>Открывать раздел в новом окне<p /></strong>
        
		
		<strong>Порядок показа:</strong> <input name="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $razdel['ord']; else echo '1'; ?>" /><p />
        
        
        <input class="unbord" name="show_first" type="checkbox" <? if(($action==1)&&($razdel['show_first']==1)) echo 'checked'; ?> /><strong>Переходить в первый подраздел<p /></strong>
        
        <input name="doInp" type="submit" value="Внести изменения" />
	    <input name="doApply" type="submit" value="Применить изменения" />
		
		<?if($action==1){?>
		<input name="doNew" type="submit" value="Сохранить как новый элемент" />
		<?}?>
   
	
	</form>
	
	<script  language="JavaScript">  
  var  frmvalidator    =  new  Validator("inpp");  
  frmvalidator.addValidation("name","req","Заполните  поле Название раздела!");    
  
  <?if(HAS_URLS) {?>
  	frmvalidator.addValidation("path","req","Заполните  поле URL раздела!");    
   //frmvalidator.addValidation("path","alnum","Поле URL раздела может содержать только латинские буквы и цифры!");
   frmvalidator.addValidation("path","regexp=^[A-Za-z0-9_]+","Поле URL раздела может содержать только латинские буквы, цифры и символ _!");
  <?}?>
</script>  
	
	
	
<?
//нижний шаблон
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->display('page_bottom.html');
?>