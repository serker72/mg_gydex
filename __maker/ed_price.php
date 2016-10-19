<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');

require_once('../classes/priceitem.php');
require_once('../classes/firmsgroup.php');

 

if(!HAS_PRICE){
	header("Location: index.php");
	die();
}


//административная авторизация
require_once('inc/adm_header.php');

$razd=new MmenuItem();

$ph=new PriceItem();

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
			 $mid=0;
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
	
	//echo $razdel['parent_id']; die();
	 

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
	
//	die();	
	
	
	//смена языка
	if(isset($_POST['doLang'])||isset($_GET['doLang'])){
		//попытаемся добавить язык
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {
			$ph->AddLanguage($id,$lang_id);
		
			header('Location: ed_price.php?action='.$action.'&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
			die();
		}else{
			header('Location: no_rights.php');
			die();
		}
	}
	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 22)) {
	  $photo=$ph->GetItemById($id,$lang_id);
	  if($photo==false){
		  header('Location: index.php');	
		  die();	
	  }
	  
	  $mid=$photo['mid'];
	}else{
		header('Location: no_rights.php');
			die();
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 11)) {
		$razdel=$razd->GetItemById($mid, LANG_CODE);
		
	}else{
		header('Location: no_rights.php');
			die();
	}
	
	if($razdel==false){
		header('Location: index.php');
		die();	
	}
}


if(($action==0)&&(isset($_POST['doInp'])||isset($_POST['doApply']))){
	//заносим новую запись
	$params=Array(); $lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	
	$lparams['small_txt']=SecStr($_POST['small_txt']);	
	$lparams['big_txt']=SecStr($_POST['big_txt']);	
	$params['photo_small']=SecStr($_POST['photo_small']);	
	$params['photo_big']=SecStr($_POST['photo_big']);	
	$params['articul']=SecStr($_POST['articul']);
	
	$lparams['txt_cost']=SecStr($_POST['txt_cost']);	
	$lparams['txt_video']=SecStr($_POST['txt_video']);	
	$lparams['txt_features']=SecStr($_POST['txt_features']);	
	$lparams['txt_attributes']=SecStr($_POST['txt_attributes']);	
	$lparams['txt_tools']=SecStr($_POST['txt_tools']);	
	$lparams['txt_materials']=SecStr($_POST['txt_materials']);	
	$lparams['txt_add_eq']=SecStr($_POST['txt_add_eq']);	
	$lparams['txt_service']=SecStr($_POST['txt_service']);	
	$lparams['txt_presentation']=SecStr($_POST['txt_presentation']);	
	
	$lparams['lang_id']=$lang_id;	
	
	$params['external_id']=abs((int)$_POST['external_id']);
	
	$params['mid']=$mid;	
	
	$params['ord']=abs((int)$_POST['ord']);
	
	if(HAS_OST) $ost=abs((int)$_POST['ost']);
	else $ost=NULL;
	
	$params['firmid']=abs((int)$_POST['firmid']);
	
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	
	if(isset($_POST['is_new'])) $params['is_new']=1; else $params['is_new']=0;
	if(isset($_POST['is_main'])) $params['is_main']=1; else $params['is_main']=0;
	
		
	$params['priority']=abs((float)$_POST['priority']);
	
	if($_POST['changefreq']=="") $params['changefreq']=NULL; else $params['changefreq']=SecStr($_POST['changefreq']);
	
	
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 22)) {
	  
	  $r_code=$ph->Add($params,$lparams,$ost);
	  unset($razd);
	  
	  if(isset($_POST['doInp']))
		  header('Location: viewpriceitems.php?id='.$mid.'&from='.$from.'&to_page='.$to_page);
	  else if(isset($_POST['doApply']))
		  header('Location: ed_price.php?action=1&id='.$r_code.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	  die();
	}else{
		header('Location: no_rights.php');
			die();
	}
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
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {
		$ph->Edit($id, $params, $lparams);
	}else{
		header('Location: no_rights.php');
			die();
	}
	
	header('Location: viewpriceitems.php?id='.$mid.'&from='.$from.'&to_page='.$to_page.'#'.$id);
	die();
}


if(($action==1)&&(isset($_POST['doInp'])||isset($_POST['doApply'])||isset($_POST['doNew']))){
	
	$params=Array(); $lparams=Array();
	$lparams['name']=SecStr($_POST['name']);
	
	$lparams['small_txt']=SecStr($_POST['small_txt']);	
	$lparams['big_txt']=SecStr($_POST['big_txt']);	
	$params['photo_small']=SecStr($_POST['photo_small']);	
	$params['photo_big']=SecStr($_POST['photo_big']);	
	$params['articul']=SecStr($_POST['articul']);
	
	
	$lparams['txt_cost']=SecStr($_POST['txt_cost']);	
	$lparams['txt_video']=SecStr($_POST['txt_video']);	
	$lparams['txt_features']=SecStr($_POST['txt_features']);	
	$lparams['txt_attributes']=SecStr($_POST['txt_attributes']);	
	$lparams['txt_tools']=SecStr($_POST['txt_tools']);	
	$lparams['txt_materials']=SecStr($_POST['txt_materials']);	
	$lparams['txt_add_eq']=SecStr($_POST['txt_add_eq']);	
	$lparams['txt_service']=SecStr($_POST['txt_service']);
	$lparams['txt_presentation']=SecStr($_POST['txt_presentation']);	
		
	
	$lparams['lang_id']=$lang_id;	
	$params['firmid']=abs((int)$_POST['firmid']);
	
	$params['mid']=abs((int)$_POST['mid']);//$mid;	
	
	$params['ord']=abs((int)$_POST['ord']);
	
	
	$params['external_id']=abs((int)$_POST['external_id']);
	
	if(HAS_OST) $ost=abs((int)$_POST['ost']);
	else $ost=NULL;
	
	if(isset($_POST['is_shown'])) $lparams['is_shown']=1; else $lparams['is_shown']=0;
	
	if(isset($_POST['is_new'])) $params['is_new']=1; else $params['is_new']=0;
	if(isset($_POST['is_main'])) $params['is_main']=1; else $params['is_main']=0;
	
		
	$params['priority']=abs((float)$_POST['priority']);
	
	if($_POST['changefreq']=="") $params['changefreq']=NULL; else $params['changefreq']=SecStr($_POST['changefreq']);
	
	
	
	if(isset($_POST['doNew'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 22)) {	
			$id=$ph->Add($params,$lparams,$ost);
		}else{
			header('Location: no_rights.php');
			die();
		}
	}else	{
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 22)) {	
			$ph->Edit($id, $params, $lparams,$ost);
		}else{
			header('Location: no_rights.php');
			die();
		}
	}
	if(isset($_POST['doInp']))
		header('Location: viewpriceitems.php?id='.$mid.'&from='.$from.'&to_page='.$to_page.'#'.$id);
	else if(isset($_POST['doApply'])||isset($_POST['doNew']))
		header('Location: ed_price.php?action=1&id='.$id.'&lang_id='.$lang_id.'&from='.$from.'&to_page='.$to_page.'&nonvisual='.$nonvisual);
	die();
}


if($action==2){
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 22)) {	
		$ph->Del($id);
	}else{
		header('Location: no_rights.php');
			die();
	}
	
	header('Location: viewpriceitems.php?id='.$mid.'&from='.$from.'&to_page='.$to_page);
	die();
}




require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$smarty->assign("SITETITLE",'Правка товара - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=21;
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
$_context->AddContext(new ContextItem( 11, 'r', "", "Разделы верхнего урованя", "razds.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 22, 'a', "", "Создать товар", "ed_price.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( "", '',  "", "Справка", "ed_price.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);






//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
$_r2=new MmenuItemNew;
$_razd_bc=$_r2->DrawNavigArr($mid, LANG_CODE);
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));
$_bc->AddContext(new BcItem('Разделы и контент', 'razds.php'));

foreach($_razd_bc as $item) $_bc->AddContext(new BcItem($item['name'], $item['url']));

$_bc->AddContext(new BcItem('Правка товара', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);






$smarty->assign('xajax_scripts',''); 
$smarty->display('page_top_xajax.html');

?>

	
 

	<script src="/uploadifive/jquery.uploadifive.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/uploadifive/uploadifive.css">
    
 	
    
    
    
    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
    

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
    
    
    
    
    
    
	
	<form  action="ed_price.php" method="post" id="inpp" name="inpp">
	<h1>Редактирование товара</h1>
	
	
    
    
    
	<input type="hidden" name="to_page" value="<?=$to_page?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="action" value="<?=$action?>">
	
	
	
	<input type="hidden" name="id" value="<?if($action==1) echo $id;?>">
	
	<label for="name">Название товара:</label><br />
		    	<input name="name" id="name" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['name'])); ?>" /><p />	

	<label for="articul">Артикул товара:</label><br />
		    	<input name="articul" id="articul" type="text" size="40" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['articul'])); ?>" /><p />	
                
                
                 <strong>Параметры sitemap.xml:</strong><br>
                <label for="priority">Priority:</label> <input type="text" id="priority" name="priority"  size="5" maxlength="3" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['priority'])); else echo "0.5"; ?>" />
                
                &nbsp;&nbsp;
                <label for="changefreq">changefreq:</label> 
                
                <select name="changefreq" id="changefreq" >
                	<option value="" <? if(($action==1)&&($photo['changefreq']=="")) echo "selected"; elseif($action==0) echo "selected"; ?>  ></option>
                    <option value="always" <? if(($action==1)&&($photo['changefreq']=="always")) echo "selected"; ?>  >always</option>
                    <option value="hourly" <? if(($action==1)&&($photo['changefreq']=="hourly")) echo "selected"; ?>  >hourly</option>
                    <option value="daily" <? if(($action==1)&&($photo['changefreq']=="daily")) echo "selected"; ?>  >daily</option>
                    <option value="weekly" <? if(($action==1)&&($photo['changefreq']=="weekly")) echo "selected"; ?>  >weekly</option>
                    <option value="monthly" <? if(($action==1)&&($photo['changefreq']=="monthly")) echo "selected"; ?>  >monthly</option>
                    <option value="yearly" <? if(($action==1)&&($photo['changefreq']=="yearly")) echo "selected"; ?>  >yearly</option>
                    <option value="never" <? if(($action==1)&&($photo['changefreq']=="never")) echo "selected"; ?>  >never</option>
                </select>
                
                
                 <p />
	
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
    
    <label for="external_id">Внешний ID:</label><br />
		    	<input name="external_id" id="external_id"  type="text" size="10" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['external_id'])); else echo '0'; ?>" /><p />	
	
	
	<?
	if($action==1){
	?>
	<label>Основная цена товара:</label><br>
	<?
		echo $ph->price_disp->GetGoodBasePrice($id);
	?>
		<input type="button" value="Редактировать..." onclick="winop('goodprices.php?good_id=<?=$id?>','900','600', 'goodprices');">
		<p>
	<?
	}?>
	
		<?if(HAS_OST){?>
		<label for="ost">Остаток товара:</label>
		<input type="text" name="ost" id="ost" size="5" maxlength="5" value="<?if($action==0) echo '0'; else if($action==1){if(isset($photo['ostatok'])) echo $photo['ostatok']; else echo '0'; }?>"><p>
		<?}?>
		
	
			<label for="mid" >Местонахождение товара:</label><br>
			<select name="mid" id="mid" style="width: 300px;">
				<?
				$parent_id=$mid; 
				$curr=0;
				?>
				
				<?
							$ml=new MmenuList();				
							echo $ml->GetItemsOptByParentIdLangId($parent_id,$curr,LANG_CODE,'name');
				?>
			</select>
			<p>
	
	
	<label for="firmid">Фирма-производитель:</label>&nbsp;
	<select name="firmid" id="firmid">
		<option value="0" <?if(($action==0)||(($action==1)&&($photo['firmid']==0))) echo 'SELECTED';?>>-не определена-</option>
		<?
		$fg=new FirmsGroup();
		if($action==0) echo $fg->GetItemsOptByLang_id(0,'name',$lang_id);
		else echo $fg->GetItemsOptByLang_id($photo['firmid'],'name',$lang_id);
		?>
	</select>
    
    <a href="viewfirms.php" target="_blank">Список фирм-производителей...</a>
	<p>
	
    
    
    
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
    <h3>Выберите фото:</h3>
	<label for="photo_small">
  
         <a href="#" onclick=" pic_url=$('#photo_small').val(); if(!pic_url.match(/^http:/)) pic_url='/'+pic_url;  window.open(pic_url,'photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); return false;"><img src="/img/newdis/modules/eview16.png" width="18" height="18" alt="предварительный просмотр" title="предварительный просмотр" /></a>
    
    Фото малое</label><br />
		<input name="photo_small" id="photo_small" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['photo_small'])); else echo 'img/no.gif';?>" /> 
	<!--	<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=2','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_small.value=''; document.forms.inpp.photo_small.focus();">выбрать фото</a>-->
	<p />		


		<label for="photo_big">
          <a href="#" onclick=" pic_url=$('#photo_big').val(); if(!pic_url.match(/^http:/)) pic_url='/'+pic_url;  window.open(pic_url,'photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); return false;"><img src="/img/newdis/modules/eview16.png" width="18" height="18" alt="предварительный просмотр" title="предварительный просмотр" /></a>
        
        Фото большое</label><br />
		<input name="photo_big" id="photo_big" type="text" size="100" maxlength="255" value="<? if($action==1) echo stripslashes(htmlspecialchars($photo['photo_big'])); else echo 'img/no.gif';?>" /> 
		<!--<a href="javascript: //" class="" onclick="window.open('photofolder.php?mode=3','photo_manager', 'width=1100, height=700,scrollbars=yes,resizable=yes'); document.forms.inpp.photo_big.value=''; document.forms.inpp.photo_big.focus();">выбрать фото</a>-->
		
        
         <p><a href="#" id="quick_uploader_select">...или перейдите к быстрой загрузке.</a></p>
        </div>
        
        <p />	
	
	
	
		<label for="small_txt">Краткое описание товара:</label><br />
		<textarea cols="80" rows="10" id="small_txt" name="small_txt"><?if($action==1) echo htmlspecialchars(stripslashes($photo['small_txt']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    <script type="text/javascript">
	CKEDITOR.replace( 'small_txt',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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




				<label for="big_txt">Описание товара:</label><br />
                <textarea cols="80" rows="20" name="big_txt" id="big_txt"><?if($action==1) echo htmlspecialchars(stripslashes($photo['big_txt']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'big_txt',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                
                
                <label for="txt_cost">Стоимость:</label><br />
                <textarea cols="80" rows="20" name="txt_cost" id="txt_cost"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_cost']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_cost',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                
                
                 <label for="txt_video">Видео:</label><br />
                <textarea cols="80" rows="20" name="txt_video" id="txt_video"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_video']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_video',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                 
                 <label for="txt_presentation">Презентация:</label><br />
                <textarea cols="80" rows="20" name="txt_presentation" id="txt_presentation"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_presentation']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_presentation',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                 
                 
                  <label for="txt_features">Особенности:</label><br />
                <textarea cols="80" rows="20" name="txt_features" id="txt_features"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_features']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_features',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                 
                 
                  <label for="txt_attributes">Технические характеристики:</label><br />
                <textarea cols="80" rows="20" name="txt_attributes" id="txt_attributes"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_attributes']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_attributes',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                 
                 
                  <label for="txt_tools">Инструмент:</label><br />
                <textarea cols="80" rows="20" name="txt_tools" id="txt_tools"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_tools']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_tools',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                 
                 
                  <label for="txt_materials">Расходные материалы:</label><br />
                <textarea cols="80" rows="20" name="txt_materials" id="txt_materials"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_materials']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_materials',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                 
                  <label for="txt_add_eq">Дополнительное оборудование:</label><br />
                <textarea cols="80" rows="20" name="txt_add_eq" id="txt_add_eq"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_add_eq']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_add_eq',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                 
                 
                 <label for="txt_service">Сервис:</label><br />
                <textarea cols="80" rows="20" name="txt_service" id="txt_service"><?if($action==1) echo htmlspecialchars(stripslashes($photo['txt_service']));?></textarea>
		        <?php
				if($nonvisual==1){
				?>
				
				<?
				}else{
					?>
                    
                    
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_service',
					 
					 {
						 customConfig : '/ckeditor/config_custom_mini.js',
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
                
                
				
				<? if($action==1){?>
				<input type="button" name="showDicts" id="showDicts" value="Словари свойств товара" onclick="winop('eddicts.php?kind=3&id=<?=$id?>&mode=3','950','600','dicts');">
				<input type="button" name="showRekoms" id="showRekoms" value="Рекомендуемые товары" onclick="winop('viewrekoms.php?good_id=<?=$id?>','800','600','rekoms');">
<p>
				<?}?>
				
				
				<label for="is_shown">Отображать на сайте:</label> <input class="unbord" name="is_shown" id="is_shown" type="checkbox" <? if(($action==1)&&($photo['is_shown']==1)) echo 'checked'; ?> /><p />


	<label for="is_new">Новинка:</label> <input class="unbord" name="is_new" id="is_new" type="checkbox" <? if(($action==1)&&($photo['is_new']==1)) echo 'checked'; ?> />
    
    <label for="is_main">На главной:</label> <input class="unbord" name="is_main" id="is_main" type="checkbox" <? if(($action==1)&&($photo['is_main']==1)) echo 'checked'; ?> /> <p />
	   
		
		<label for="ord">Порядок показа:</label> <input name="ord" id="ord" type="text" size="3" maxlength="3" value="<? if($action==1) echo $photo['ord']; else echo '1'; ?>" /><p />
        
       
	   
	   
        
        <input name="doInp" type="submit" value="Внести изменения" onclick="" />
        <input name="doApply" type="submit" value="Применить изменения" />
	   <?if($action==1){?>
		<input name="doNew" type="submit" value="Сохранить как новый элемент" />
		<?}?>
	
	</form>
	
	<script  language="JavaScript">  
	  var  frmvalidator    =  new  Validator("inpp");  
	 
	  frmvalidator.addValidation("mid","dontselect=0","Укажите местонахождение товара!");    
	 
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