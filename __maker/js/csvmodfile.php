<?
//session_name("csvfolder");
session_start();

//require_once('../../classes_s/global.php');
require_once('../../classes/global.php');
require_once('../../classes_s/filerut.php');
require_once('../csvsettings.php');

//административная авторизация
require_once('../inc/adm_header_js.php');

$err=0;
if(isset($_POST['action'])) $action=abs((int)$_POST['action']);
else $action=0;
if(($action<0)||($action>7)) $action=0;


if($action==0){
	if(isset($_POST['path'])) {
		$path=SecurePath($_POST['path']);
	}else $err=-5;

	if(isset($_POST['name'])) {
		$name=SecurePath($_POST['name']);
	}else $err=-6;
}


if($action==1){
	if(isset($_POST['path'])) {
		$path=SecurePath($_POST['path']);
	}else $err=-1;
	
	
	
	if(isset($_POST['name'])) {
		$name=SecurePath($_POST['name']);
	}else $err=-3;
}

if($action==2){
	if(isset($_POST['path'])) {
		$path=SecurePath($_POST['path']);
	}else $err=-4;
	
	
}

if($action==3){
	if(isset($_POST['name'])) {
		$name=SecurePath($_POST['name']);
	}else $err=-7;
}

if($action==4){
	if(isset($_POST['name'])) {
		$name=SecurePath($_POST['name']);
	}else $err=-8;
}

if($action==5){
	$names=Array();
	
	foreach($_POST as $k=>$v){
		if(eregi("arr",$k)) $names[]=SecurePath($v);
	}
	
	if(count($names)==0) $err=-9;
}

//удаление многих файлов
if($action==7){
	$names=Array();
	
	
	foreach($_SESSION['photofolder'] as $k=>$v){
		$names[]=SecurePath($k);
		unset($_SESSION["photofolder"][$k]);
	}
	
	if(count($names)==0) $err=-10;
}



//выделяем файл
if($action==3){
	$_SESSION["photofolder"][$name]='';
	
}

//не выделяем файл
if($action==4){
	unset($_SESSION["photofolder"][$name]);
}

//выделяем много файлов
if($action==5){
	foreach($names as $k=>$v) $_SESSION["photofolder"][$v]='';
}

//не выделяем много файлов
if($action==6){
	$names=Array();
	foreach($_SESSION["photofolder"] as $k=>$v) {
		unset($_SESSION["photofolder"][$k]);
		$names[]=$k;
	}
}

header('Expires: Wed, 23 Dec 1980 00:30:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

Header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="WINDOWS-1251" standalone="yes"?>';	
	

echo '<response>';
echo "<errcode>$err</errcode>";

if($err==0){
	$dr=new FileRut();
	
	
	//удаляем файл
	if($action==2){
		
		$rights_man=new DistrRightsManager;
	    if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 25)) {
			$dr->DeleteFile($basepath.$path);
		}
		
		//echo "<path>$basepath$path</path>";
	}
	
	
	
	
	//переименуем файл
	if($action==1){
		$newp=substr($basepath.$path, 0, strpos($basepath.$path,basename($basepath.$path)) );
		
		$rights_man=new DistrRightsManager;
	    if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 25)) {
			$dr->RenameFile($basepath.$path,$newp.$name);
		}
		
	}
	
	//выделяем файл
	if(($action==3)||($action==4)){
		//unset($_SESSION["photofolder"][$name]);
		echo "<name>$name</name>";
		echo "<action>$action</action>";
	}
	
	
	//выделяем много файлов
	if(($action==5)||($action==6)){
		echo "<action>$action</action>";
		echo "<lengg>".count($names)."</lengg>";
		
		foreach($names as $k=>$v) {
			$vv=eregi_replace("/","@1",$v);
			//echo "<nameitem>aaaa</nameitem>";
			echo "<nameitem>$vv</nameitem>";
		}
	}
	
	//выделяем много файлов
	if(($action==7)){
		echo "<action>$action</action>";
		echo "<lengg>".count($names)."</lengg>";
		
		foreach($names as $k=>$v) {
			/*$vv=eregi_replace("/","@1",$basepath.$v);
			echo "<nameitem>$vv</nameitem>";*/
			
			$rights_man=new DistrRightsManager;
		    if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 25)) {
				$dr->DeleteFile($basepath.$v);
			}
		}
	}
	
	
	unset($dr);
}

echo '</response>';

?>
