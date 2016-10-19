<?
//session_name("csvfolder");
session_start();
//зачистка сессии при переходе
//(сброс выделения файлов)
if(!isset($_SESSION['csvfolder'])) $_SESSION['csvfolder']=Array();
foreach($_SESSION['csvfolder'] as $k=>$v){
	unset($_SESSION['csvfolder'][$k]);
}

Header("Expires: Mon, 26 Jul 1995 05:00:00 GMT");
Header("Cache-Control: nocache, must-revalidate");
Header("Pragma: no-cache");
Header("Last-Modified:".gmdate("D, d M Y H:i:s")."GMT");


require_once('../classes_s/resizing.php');
require_once('../classes_s/onefolder.php');
require_once('../classes/global.php');

require_once('../classes_s/folderdeployer.php');

require_once('../classes_s/dirrut.php');
require_once('../classes_s/filerut.php');


require_once('../classes_s/simplefilelist.php');


//административная авторизация
require_once('inc/adm_header.php');

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 25)) {}
else{
	header('Location: no_rights.php');
	die();	  	
}

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
require_once('csvsettings.php');

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

require_once('csvuploader.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<link rel="stylesheet"  href="style.css" type="text/css" />
	<link rel="stylesheet"  href="photostyle.css" type="text/css">
	<title>Диспетчер csv-файлов</title>

	
	<script src="js/csvmodule.js" type="text/javascript">
	
	</script>
	<script src="js/funcs.js" type="text/javascript">
	
	</script>
</head>

<body>
<?

include_once('photodivs.php');

require_once('csvmode.php');


?>
<table width="90%" cellspacing="1" cellpadding="3" border="0">
<tr>
   
    <td width="*" align="left" valign="top">
	
	<?
		$params=Array();
	$params['folder']=$folder;
	$params['mode']=$mode;
	
	//phpinfo();
	$fl=new SimpleFileList($fullpath,$basepath,$rootpath);
	$fl->SetProgName('csvfiles.php');
	$fl->SetManmode($manmode);
	echo $fl->GetFileList($from,PHOTOS_PER_PAGE,NULL,$params);	

?></div>



<form action="csvfiles.php">
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
</tr>
 <td width="200" valign="top"><img src="../img/01.gif" alt="" width="200" height="1" border="0"></td>
  <td width="*" valign="top">&nbsp;</td>
</table>





</body>
</html>
