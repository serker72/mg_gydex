<?
session_start();
header('Content-type: text/html; charset=windows-1251');

require_once('../../classes/global.php');
require_once('../../classes/authuser.php');
require_once('../../classes/smarty/SmartyAdm.class.php');
require_once('../../classes/smarty/SmartyAj.class.php');

require_once('../../classes/v2/gydex_stat.php');

$ret='';
$_stat=new GydexStat;
$pdate1=SecStr(date('Y-m-d', GydexStat::DateFromdmY($_POST['pdate1'])));
$pdate2=SecStr(date('Y-m-d', GydexStat::DateFromdmY($_POST['pdate2'])));



if(isset($_POST['action'])&&($_POST['action']=="total")){
	 
	$data=$_stat->GetTotal($pdate1, $pdate2);
	
	/*echo "Categories,Дни
	08,2
	09,3
	10,1";*/
	
	$ret="Categories,Просмотров страниц,Уник. посетителей\n";
	foreach($data as $k=>$v) {
		$ret.=$v['pdate'].','.$v['total'].','.$v['total_uniq'];
		if($k<count($data)-1) $ret.="\n";	
	}
	
	//print_r($data);
	 
}
elseif(isset($_POST['action'])&&($_POST['action']=="total_stack")){
	 
	$data=$_stat->GetTotal($pdate1, $pdate2);
	
	/*echo "Categories,Дни
	08,2
	09,3
	10,1";*/
	
	/*$ret="Categories,Просмотров страниц,Уник. посетителей\n";
	foreach($data as $k=>$v) {
		$ret.=$v['pdate'].','.$v['total'].','.$v['total_uniq'];
		if($k<count($data)-1) $ret.="\n";	
	}*/
	$ret="Categories,";
	foreach($data as $k=>$v) {
		$ret.=$v['pdate'];
		if($k<count($data)-1) $ret.=",";	
	}
	$ret.="\n";	
	
	
	
	$ret.='Всего просмотрено страниц,';	
	foreach($data as $k=>$v) {
		$ret.=$v['total'].'';
		if($k<count($data)-1) $ret.=",";	
	}
	 $ret.="\n";	
	 
	 $ret.='Уникальных посетителей,';
	foreach($data as $k=>$v) {
		$ret.=$v['total_uniq'];
		if($k<count($data)-1) $ret.=",";	
	}
	//$ret.="\n";
	
	//print_r($data);
	 
}
elseif(isset($_POST['action'])&&($_POST['action']=="average_time")){
	
	$data=$_stat->GetAverageTime($pdate1, $pdate2);
		 
	//$ret="Categories,Время (мин)\n";
	
	$total=0; foreach($data as $k=>$v) {
		$total+=	abs($v['ave']);
	}
	
	
	if($total>0){
		
		
		
		foreach($data as $k=>$v) {
			$ret.=$v['pdate'].','.round(abs($v['ave'])*100/$total,2).','.abs($v['ave']).' мин';
			//$ret.=$v['pdate'].' '.abs($v['ave']).' мин ,'.round(abs($v['ave'])*100/$total,2);
			if($k<count($data)-1) $ret.="\n";	
		}
		
	}
	
	
	 
}
elseif(isset($_POST['action'])&&($_POST['action']=="orders")){
	$data=$_stat->GetOrders( $pdate1, $pdate2);
		 
	$ret="Categories,Заказы\n";
	foreach($data as $k=>$v) {
		$ret.=$v['pdate'].','.$v['orders'];
		if($k<count($data)-1) $ret.="\n";	
	} 
	
	 
}elseif(isset($_POST['action'])&&($_POST['action']=="sub")){
	 
	$data=$_stat->GetSubPerDay( $pdate2 );
		 
	$ret="Categories,Просмотров\n";
	
	foreach($data['subs'] as $k=>$v){
		$ret.=$v['uri'].','.$v['c_id'];
		if($k<count($data['subs'])-1) $ret.="\n";			
	}
	
	
	 
}
	
//if(DO_RECODE) $ret=iconv('windows-1251','utf-8',$ret);
echo $ret;	
?>