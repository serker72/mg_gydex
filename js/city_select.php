<?
session_start();
//header('Content-type: text/html; charset=utf-8');



require_once('../classes/global.php');
require_once('../classes/authuser.php');
require_once('../classes/smarty/SmartyAdm.class.php');
require_once('../classes/smarty/SmartyAj.class.php');



require_once('../classes/supplier_city_group.php');
require_once('../classes/supplier_city_item.php');


$ret='';

//выборка к-тов + сотрудников
if($_GET['term']) 	{
	
	 
	$_sc=new SupplierCityGroup;
	
	
	$sc=$_sc->GetItemsByIdArr(   iconv("utf-8","windows-1251",SecStr($_GET['term'])),0,0,0);
	
	$ret_arrs=array();
	$vv=array();
	
	$vv['id']=$_GET['term'];  
	//$vv['label']='Ваш ввод: '.$_GET['term'];
	$vv['text']=$_GET['term']; 
	
	array_push($ret_arrs, $vv);
	
	foreach($sc as $k=>$v){


		$vv=array();
		
		$vv['id']=  iconv('windows-1251','utf-8',$v['fullname']);
		$vv['text']= iconv('windows-1251','utf-8',$v['fullname']);
		
		array_push($ret_arrs, $vv);
	}
	

	 
	 
	$ret = array();
	 
	 
	$ret['results'] = $ret_arrs;
	 
	echo json_encode($ret);
	
	
}

//if(DO_RECODE) $ret=iconv('windows-1251','utf-8',$ret);
//echo $ret;	

?>