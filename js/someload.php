<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');


$res='';
if(isset($_POST['action'])&&($_POST['action']=="get_razd")){
	$id=abs((int)$_POST['id']);
	$mi=new MmenuItem();
	$item=$mi->GetItemById($id,$_SESSION['lang'],1);
	if($item!==false) $res=stripslashes($item['txt']);
	
}
echo $res;
?>