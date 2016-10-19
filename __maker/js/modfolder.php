<?
session_start();


require_once('../../classes/global.php');
require_once('../../classes_s/dirrut.php');
require_once('../photosettings.php');

//административная авторизация
require_once('../inc/adm_header_js.php');

header('Expires: Wed, 23 Dec 1980 00:30:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

Header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="WINDOWS-1251" standalone="yes"?>';



$err=0;
if(isset($_POST['action'])) $action=abs((int)$_POST['action']);
else $action=0;
if(($action<0)||($action>2)) $action=0;


if($action==0){
	if(isset($_POST['path'])) {
		$path=SecurePath($_POST['path']);
	}else $err=-5;

	if(isset($_POST['name'])) {
		
		$name=SecurePath(iconv('utf-8', 'windows-1251', $_POST['name']));
	}else $err=-6;
}


if($action==1){
	if(isset($_POST['path'])) {
		$path=SecurePath($_POST['path']);
	}else $err=-1;
	
	if(isset($_POST['oldname'])) {
		$oldname=SecurePath($_POST['oldname']);
	}else $err=-2;
	
	if(isset($_POST['name'])) {
		//$name=SecurePath($_POST['name']);
		$name=SecurePath(iconv('utf-8', 'windows-1251', $_POST['name']));
	}else $err=-3;
}

if($action==2){
	if(isset($_POST['path'])) {
		$path=SecurePath($_POST['path']);
	}else $err=-4;
	
	
}

echo '<response>';
echo "<errcode>$err</errcode>";

if($err==0){
	$dr=new DirRut();
	
	//переименуем папку
	if($action==1){
		
		//$name=iconv('utf-8', 'windows-1251', $name);
		$rights_man=new DistrRightsManager;
	    if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 25)) {
			$dr->RenameFolder($basepath.'/'.$path, $oldname, $name);
		}
		
		echo "<path>$path/$name</path>";
	}
	
	//удаляем папку
	if($action==2){
		$rights_man=new DistrRightsManager;
	    if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 25)) {
			$dr->DeleteFolder($basepath.'/'.$path);
		}
		
		echo "<path>$path/$name</path>";
	}
	
	//создание папки
	if($action==0){
		//$name=iconv('utf-8', 'windows-1251', $name);
		$rights_man=new DistrRightsManager;
	    if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 25)) {
			$dr->CreateFolder($basepath.'/'.$path.'/'.$name);
		}
		
		echo "<path>$path/$name</path>";
	}
	
	unset($dr);
}

echo '</response>';

?>
