<?



require_once('abstractgroup.php');

// абстрактная группа итемов (без языковых таблиц)
class OtzGroup extends AbstractGroup {
	public $fromname='from';
	
	//установка всех имен
	protected function init(){
		$this->tablename='otzyv';
		$this->pagename='viewotzyv.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_on_site';		
		
		
		
	}
	
	
	public function CalcNew(){
		$sql='select count(*) from '.$this->tablename.' where is_on_site=0';
		$set=new mysqlset($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		return (int)$f[0];
	}
	
	
	
	
	
	
	public function GetItemsArrTwo(){
		$arr=array();
		
		 
			$sql='select * from '.$this->tablename.' where is_on_site=1 order by is_on_site asc, ord desc,  id asc limit 2';
			 
		$set=new mysqlset($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			
			 
			$arr[]=$f;
		}
		
		
		
		return $arr;
	}
	
	
	public function GetItemsArrNum($num=1){
		$arr=array();
		
		 
			$sql='select * from '.$this->tablename.' where is_on_site=1 order by rand(), ord desc,  id asc limit '.$num;
			 
		$set=new mysqlset($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			
			 
			$arr[]=$f;
		}
		
		
		
		return $arr;
	}
	
	
	
	
	
	public function GetItems($on_site=0, $template, $from=0, $to_page=ITEMS_PER_PAGE){
		$sm=new Smartyadm;
		$all=$this->GetItemsArr($on_site, $from, $to_page);
		
		
		$sm->assign('filename', 'ed_otzyv.php');
		$sm->assign('items', $all['items']);
		$sm->assign('pages', $all['pages']);
		
		$sm->assign('fromno', $from);
		$sm->assign('topage', $to_page);
		
		
		return $sm->fetch($template);
	}
	
	
	public function GetItemsArr($on_site=0, $from=0, $to_page=ITEMS_PER_PAGE){
		$arr=array();
		
		if($on_site==1)  {
			$sql='select * from '.$this->tablename.' where is_on_site=1 order by is_on_site asc, ord desc,  id asc';
			$sql_count='select count(*) from '.$this->tablename.' where is_on_site=1  ';
			
			}
		else{
			 $sql='select * from '.$this->tablename.' order by is_on_site asc, ord desc,  id asc';
			 
			 $sql_count='select count(*) from '.$this->tablename.'   ';
		}
		$set=new mysqlset($sql, $to_page, $from, $sql_count);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		$totalcount=$set->GetResultNumRowsUnf();
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page);
		$navig->setFirstParamName($this->fromname);
		
		$navig->setDivWrapperName('alblinks');
		$navig->setPageDisplayDivName('alblinks1');			
		$pages= $navig->GetNavigator();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			
			 
			$arr[]=$f;
		}
		
		//$arr['pages']=$pages;
		$all=array('items'=>$arr, 'pages'=>$pages);
		
		return $all;
	}
	
	
	
	
	
	public function GetItemsCli($template){
		$sm=new Smartyadm;
		$all=$this->GetItemsArrCli();
		
		
	
		$sm->assign('items', $all);
		
		
		return $sm->fetch($template);
	}
	
	
	
	public function GetItemsArrCli(){
		$arr=array();
		
		
			$sql='select * from '.$this->tablename.' where is_on_site=1 order by is_on_site asc, ord asc,  id asc';
			
		$set=new mysqlset($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		$totalcount=$set->GetResultNumRowsUnf();
		
		
		
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			
			 
			$arr[]=$f;
		}
		
		
		
		return $arr;
	}
}
?>