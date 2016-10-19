<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/langgroup.php');
require_once('../classes/langitem.php');
require_once('../classes/filetext.php');


//административная авторизация
require_once('inc/adm_header.php');
	
if(!isset($_GET['nonvisual']))
	if(!isset($_POST['nonvisual'])) $nonvisual = 0;
	else $nonvisual = $_POST['nonvisual'];		
else $nonvisual = $_GET['nonvisual'];		
$nonvisual=abs((int)$nonvisual);	




if(!isset($_GET['id']))
		if(!isset($_POST['id'])) {
			$id=0;
		}
		else $id = $_POST['id'];		
	else $id = $_GET['id'];		
	$id=abs((int)$id);

	
//получим текст из файла по id
	$txts=Array();
	
	$langs=Array();
	$lg=new LangGroup();
	$langs=$lg->GetLangsIdList();
	
	
	
	//обработка
	if(isset($_POST['doInp'])||isset($_POST['doApply'])){
		$rights_man=new DistrRightsManager;
		if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 12)) {
		  foreach($langs as $k=>$v){
			  $fi=new FileText();
			  if(isset($_POST['txt_'.$v])){
				  $tmp=SecStr($_POST['txt_'.$v]);
				  $fi->Edit($tmp, '../parts/razd'.$id.'-'.$v.'.txt');
			  }
		  }
		  if(isset($_POST['doInp']))
			  header('Location: index.php');
		  else if(isset($_POST['doApply']))
			  header('Location: ed_file.php?id='.$id.'&nonvisual='.$nonvisual);
		  die();
		}else{
			header('Location: no_rights.php');
		  die();
		}
	}
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 12)) {
	
	
	  foreach($langs as $k=>$v){
		  $fi=new FileText();
		  $tmp=$fi->GetItem('../parts/razd'.$id.'-'.$v.'.txt');
		  //echo $tmp;
		  $txts[$v]=$tmp;
	  }
	}else{
		header('Location: no_rights.php');
		  die();		
		
	}
	
require_once('../classes/smarty/SmartyAdm.class.php');
//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;


//соответствие между айди блока и айди его в меню
$_sootv=array(
	3=>30,
	8=>31,
	4=>32,
	5=>33,
	1=>34,
	2=>35,
	6=>36,
	7=>37
);

$smarty->assign("SITETITLE",'Правка общего блока - '.SITETITLE.'');
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);


$_menu_id=$_sootv[$id];
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

$_context->AddContext(new ContextItem( "", '',  "", "Справка", "ed_file.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);





//хлебные крошки
require_once('../classes/v2/mmenuitem_new.php');
require_once('../classes/v2/bc.php');
$_bc=new Bc();
$_bc->AddContext(new BcItem('Старт', 'index.php'));



$_bc->AddContext(new BcItem('Правка общего блока', basename(__FILE__)));

$bc=$_bc->BuildContext();
$smarty->assign('bc', $bc);




$smarty->display('page_top.html');

?>

	
	 
	
	<form action="ed_file.php" method="post" id="inpp" name="inpp">
	<h1>Редактирование общего блока</h1>
	
	
	
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="nonvisual" value="<?=$nonvisual?>">
	
	<?
	//многоязычность
	foreach($txts as $k=>$v){
		$li=new LangItem();
		$lang=$li->GetItemById($k);
	?>
			<strong>Текст блока</strong> <img src="/<?=stripslashes($lang['lang_flag'])?>" alt="" border="0">:<br />
		        <textarea cols="80" rows="20" name="txt_<?=$k?>"><?echo htmlspecialchars(stripslashes($v));?></textarea>
				<?php
				if($nonvisual==0){
					?>
                    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
                    <script type="text/javascript">
	CKEDITOR.replace( 'txt_<?=$k?>',
					 
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
	
	
	<?
	}
	?>
	
        
        <input name="doInp" type="submit" value="Внести изменения" onclick="" />
		<input name="doApply" type="submit" value="Применить изменения" onclick="" />
	</form>
	
	<script  language="JavaScript">  
	  //var  frmvalidator    =  new  Validator("inpp");  
	  //frmvalidator.addValidation("name","req","Заполните  поле Название раздела!");    
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