<?



require_once('abstractgroup.php');

// абстрактная группа итемов (без языковых таблиц)
class NumGroup extends AbstractGroup {
	public $fromname='from';
	
	//установка всех имен
	protected function init(){
		$this->tablename='numbers';
		$this->pagename='viewnumber.php';		
		$this->subkeyname='mid';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	
 
	public function GetItemsRandCli(){
		$arr=array();
		
		 
			$sql='select * from '.$this->tablename.' where is_shown=1 order by rand()';
			 
		$set=new mysqlset($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		
		$class_index=0;
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			//чередование индекса для класса: 0,1,2,3
			$f['class_index']=$class_index;
			$f['low']=!eregi('<br>',$f['number']); //strlen($f['number'])<=4;
			 
			$arr[]=$f;
			
			if($class_index>=3) $class_index=0;
			else $class_index++;
		}
		
		
		
		return $arr;
	}
	
	
	
	public function GetItemsArrFour(){
		$arr=array();
		
		 
			$sql='select * from '.$this->tablename.' where is_shown=1 order by rand() limit 4';
			 
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
	
	
	
	
	
	public function GetItems($is_shown=0, $template, $from=0, $to_page=ITEMS_PER_PAGE){
		$sm=new Smartyadm;
		$all=$this->GetItemsArr($is_shown, $from, $to_page);
		
		
		$sm->assign('filename', 'ed_number.php');
		$sm->assign('items', $all['items']);
		$sm->assign('pages', $all['pages']);
		
		$sm->assign('fromno', $from);
		$sm->assign('topage', $to_page);
		
		
		return $sm->fetch($template);
	}
	
	
	public function GetItemsArr($is_shown=0, $from=0, $to_page=ITEMS_PER_PAGE){
		$arr=array();
		
		if($is_shown==1)  {
			$sql='select * from '.$this->tablename.' where is_shown=1 order by is_on_site asc, id asc';
			$sql_count='select count(*) from '.$this->tablename.' where is_shown=1  ';
			
			}
		else{
			 $sql='select * from '.$this->tablename.' order by is_shown asc,   id asc';
			 
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
		
		
			$sql='select * from '.$this->tablename.' where is_shown=1 order by  id asc';
			
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