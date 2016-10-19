<?
session_name("photofolder");
session_start();
//зачистка сессии при переходе
//(сброс выделения файлов)
if(!isset($_SESSION['photofolder'])) $_SESSION['photofolder']=Array();
foreach($_SESSION['photofolder'] as $k=>$v){
	unset($_SESSION['photofolder'][$k]);
}

Header("Expires: Mon, 26 Jul 1995 05:00:00 GMT");
Header("Cache-Control: nocache, must-revalidate");
Header("Pragma: no-cache");
Header("Last-Modified:".gmdate("D, d M Y H:i:s")."GMT");


require_once('../classes/global.php');
require_once('../classes_s/resizing.php');
//require_once('../classes/folderer.php');
require_once('../classes_s/onefolder.php');
require_once('../classes_s/folderdeployer_s.php');

require_once('../classes_s/dirrut.php');
require_once('../classes_s/filerut.php');


require_once('../classes_s/filelist_s.php');

require_once('../classes/xajax/xajax_core/xajax.inc.php');
require_once('js/modfolder_xajax_common.php');

require_once('../classes_s/foldlist_s.php');

//работа с правами
function chmod_R($path, $perm) {
 
   $handle = opendir($path);
   while ( false !== ($file = readdir($handle)) ) {
     if ( ($file !== ".") && ($file !== "..") ) {
       if ( is_file($path."/".$file) ) {
         chmod($path . "/" . $file, $perm);
       }
       else {
         chmod($path . "/" . $file, $perm);
         chmod_R($path . "/" . $file, $perm);
       }
     }
   }
   closedir($handle);
 }
 

//папка верхнего уровня
//$basepath='d:/temp';
require_once('photosettings.php');

if(isset($_GET['doChmod'])){
	@chmod_R($basepath.'/', 0777); 
}
//echo $basepath;

if(!isset($_GET['folder']))
	if(!isset($_POST['folder'])) {
		$folder='';
	}
	else $folder = $_POST['folder'];		
else $folder = $_GET['folder'];		

if(!(isset($HTTP_POST_VARS['folder'])||isset($HTTP_GET_VARS['folder'])))
	if(isset($_COOKIE['folder'])&&($folder=='')){
		$folder=$_COOKIE['folder'];
	}

$fullpath=$basepath.$folder;
//получим реальный путь

//проверим реальный путь
$comp_fullpath=realpath($fullpath);
$comp_basepath=realpath($basepath);

if( (!file_exists($fullpath))
||(strpos($comp_fullpath,$comp_basepath)===false)
||(strpos($comp_fullpath,$comp_basepath)!==0)){
	$fullpath = $basepath;
	$folder='';
	//echo " NE содержится!";
}else{
	setcookie("folder", $folder,time()+2419200);
	//echo "содержится!";
}	
/*
if(!file_exists($fullpath)){
	$fullpath = $basepath;
	$folder='';
}else{
	setcookie("folder", $folder,time()+2419200);
}
*/



if(!isset($_GET['from'])){
	if(!isset($_POST['from'])){
		if(!isset($_COOKIE['from'])){
			$from=0;
		}else $from = $_COOKIE['from'];		
	}else{
		$from = $_POST['from'];		
	}
}else{
	$from = $_GET['from'];
}	
$from=abs((int)$from);
setcookie("from", $from,time()+2419200);


if(!isset($_GET['mode'])){
	if(!isset($_POST['mode'])){
		$mode=32;
	}else{
		$mode = $_POST['mode'];		
	}
}else{
	$mode = $_GET['mode'];
}

require_once('photouploader.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<link rel="stylesheet"  href="style.css" type="text/css" />
	<link rel="stylesheet"  href="photostyle.css" type="text/css">
	<title>Диспетчер изображений</title>

	
	<script src="js/module.js" type="text/javascript">
	
	</script>
	<script src="js/funcs.js" type="text/javascript">
	
	</script>
	
	<?echo $xajax_read->getJavascript('../classes/xajax');?>
</head>

<body>
<div id="debug"></div>
<?

include_once('photodivs_s.php');

require_once('photomode.php');

	/*echo "<h1>one</h1>";

	$fc=new FoldersList_S('/level/sub','/subsub/subsubsub');
	$fc->SetProgName('photofolder_s.php');
	
	$params=Array();
	$params['mode']=$mode;
	$params['lang_id']=LANG_CODE;
	echo $fc->CreateTree($params);
	unset($fc);

	echo "<h1>two</h1>";*/



$sm=new SmartyAdm;
	//рисуем каталоги
	/*$f=new FolderDeployer_S($fullpath,$basepath,$folder);
	$f->SetProgName('photofolder_s.php');
	$params=Array();
	$params['mode']=$mode;
	$params['lang_id']=LANG_CODE;
	*/
	
	//эксперим класс дерева папок
	
	$fc=new FoldersList_S('',$folder);
	$fc->SetProgName('photofolder_s.php');
//	$fc->SetTemplate('photofolder/folders2.html');
	$fc->SetRoot(false);
	
	$params=Array();
	$params['mode']=$mode;
	$params['lang_id']=LANG_CODE;
	
	$sm->assign('folders', $fc->CreateTree($params));
	$sm->assign('folder',$folder);
	$sm->assign('mode',$mode);
	$sm->assign('from',$from);
	
	$sm->assign('COORDFUNC',COORDFUNC);
	$sm->assign('pgor_name','photofolder_s.php');
	
	
	//рисуем файлы
	$sm->assign('navicapt','Вы находитесь в ');
	if($folder=='') $sm->assign('navistr','главной папке.');
	else $sm->assign('navistr',"папке <em>$folder</em>");
	
	//выведем файлы
	$params=Array();
	$params['folder']=$folder;
	$params['mode']=$mode;
	
	$fl=new FileList_S($fullpath,$basepath,$rootpath);
	$fl->SetProgName('photofolder_s.php');
	$fl->SetProgName('photofolder_s.php');
	$fl->SetManmode($manmode);
	$o=Array(
		'0'=>true,
		'1'=>true,
		'2'=>true,
		'3'=>true,
				'20'=>true,
				'30'=>true
	);
	$fl->SetOpenmode($o);
	
	$sizes=Array();
	/*$this->preview_size[0]=90;
		$this->preview_size[1]=120;*/
		$sizes[0]=550;
		$sizes[1]=550;		
		
		$sizes[2]=300;
		$sizes[3]=300;		
		$sizes[4]=170;
		$sizes[5]=170;	
	$fl->SetOptimizeSize($sizes);
	$pres=Array();
	$pres[0]=70;
	$pres[1]=70;
	$fl->SetPreviewSize($pres);
	
	
	$resfiles=$fl->GetFileList($from,PHOTOS_PER_PAGE,'jpg|jpe|jpeg|gif|png|wbm',$params);
	$sm->assign('rows', $resfiles['rows']);
	$sm->assign('pages', $resfiles['pages']);
	$sm->assign('scripts', $resfiles['scripts']);
	
	
	
	$sm->display('photofolder/page.html');

?>
<table width="90%" cellspacing="1" cellpadding="3" border="0">
<tr>
    <td width="200" valign="top"><?
	
		$f=new FolderDeployer($fullpath,$basepath,$folder);
	$f->SetProgName('photofolder_s.php');
	$params=Array();
	$params['mode']=$mode;
	echo $f->CreateTree(false,$params);?>
	
	
	<form action="photofolder.php">
	<input type="hidden" name="mode" value="<?=$mode?>">
	<input type="hidden" name="from" value="<?=$from?>">
	<input type="hidden" name="folder" value="<?=$folder?>">		
	<br>
	<br>
	<br>
	
	<p>
	<input type="submit" name="doChmod" value="Установить права доступа" title="сбросить текущие права доступа ко всем подпапкам и файлам и установить права на полный доступ">
	</form>
	</td>
    <td width="*" align="left" valign="top">
	<h3>Вы находитесь в <?if($folder=='') echo 'главной папке.'; else echo 'папке <em>'.$folder.'</em>'?></h3>
	
	<?
	$params=Array();
	$params['folder']=$folder;
	$params['mode']=$mode;
	
	

	//phpinfo();
	$fl=new FileList($fullpath,$basepath,$rootpath);
	$fl->SetProgName('photofolder_s.php');
	$fl->SetManmode($manmode);
	$o=Array(
		'0'=>true,
		'1'=>true,
		'2'=>true,
		'3'=>true,
				'20'=>true,
				'30'=>true
	);
	$fl->SetOpenmode($o);
	
	$sizes=Array();
	/*$this->preview_size[0]=90;
		$this->preview_size[1]=120;*/
		$sizes[0]=550;
		$sizes[1]=550;		
		
		$sizes[2]=300;
		$sizes[3]=300;		
		$sizes[4]=170;
		$sizes[5]=170;	
	$fl->SetOptimizeSize($sizes);
	$pres=Array();
	$pres[0]=70;
	$pres[1]=70;
	$fl->SetPreviewSize($pres);
echo $fl->GetFileList($from,PHOTOS_PER_PAGE,'jpg|jpe|jpeg|gif|png|wbm',$params);?></div></td>
</tr>
 <td width="200" valign="top"><img src="../img/01.gif" alt="" width="200" height="1" border="0"></td>
  <td width="*" valign="top">&nbsp;</td>
</table>





</body>
</html>
