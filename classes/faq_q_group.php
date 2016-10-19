<?
require_once('abstractgroup.php');

require_once('faq_g_group.php');
require_once('solgroup.php');




// абстрактна€ группа итемов (без €зыковых таблиц)
class FaqQGroup extends AbstractGroup {
	public $fromname='from';
	
	//установка всех имен
	protected function init(){
		$this->tablename='faq_question';
		$this->pagename='viewfaq.php';		
		$this->subkeyname='parent_id';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
	
	public function CalcNew(){
		$sql='select count(*) from '.$this->tablename.' where is_shown=0';
		$set=new mysqlset($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		return (int)$f[0];
	}
	
	
	
	
	
	
	 
	
	
	public function GetItemsArrNum($num=1){
		$arr=array();
		
		 
			$sql='select * from '.$this->tablename.' where is_shown=1 order by rand(), ord desc,  id asc limit '.$num;
			 
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
	
	
	
	
	
	public function GetItems($on_site=0, $template, $from=0, $to_page=ITEMS_PER_PAGE,  $group_id=-1, $solution_id=-1){
		$sm=new Smartyadm;
		$all=$this->GetItemsArr($on_site, $from, $to_page, $group_id, $solution_id);
		
		
		$sm->assign('filename', 'ed_faq.php');
		$sm->assign('items', $all['items']);
		$sm->assign('pages', $all['pages']);
		
		$sm->assign('fromno', $from);
		$sm->assign('topage', $to_page);
		
		
		//список групп и продуктов
		$_sols=new SolGroup;
		$_grs=new FaqGGroup;
		
		$sm->assign('group_id', $group_id);
		$sm->assign('solution_id', $solution_id);
		
		$sols=$_sols->GetItemsArr(0,0,10000000);
		$grs=$_grs->GetItemsArr(0,0,10000000);
		$sm->assign('sols',$sols['items']);
		$sm->assign('grs',$grs['items']);
		
		
		return $sm->fetch($template);
	}
	
	 
	public function GetItemsArr($on_site=0, $from=0, $to_page=ITEMS_PER_PAGE,  $group_id=-1, $solution_id=-1){
		$arr=array();
		$flt=array();
		if($group_id>=0) $flt[]='  t.group_id="'.$group_id.'" ';
		if($solution_id>=0) $flt[]='  t.solution_id="'.$solution_id.'" ';
		if($on_site>0) $flt[]='  t.is_shown="1" ';
		
		
		
			if(count($flt)>0) $_flt=' where '.implode(' and ', $flt);
			else $_flt='';
			$sql='select t.*,
					g.name as gr_name,
					s.name as sol_name
			 from '.$this->tablename.'  as t
			 	left join solution as s on s.id=t.solution_id
				left join faq_group as g on g.id=t.group_id
			   '.$_flt.' order by t.is_shown asc, t.ord desc,  t.id asc';
			$sql_count='select count(*) from '.$this->tablename.' as t '.$_flt.' ';
			
			 
		
		//echo $sql.'<br>';
		
		$set=new mysqlset($sql, $to_page, $from, $sql_count);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		$totalcount=$set->GetResultNumRowsUnf();
		
		$navig = new PageNavigator($this->pagename,$totalcount,$to_page,$from,10,'&to_page='.$to_page.'&group_id='.$group_id.'&solution_id='.$solution_id);
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
		
		
			$sql='select * from '.$this->tablename.' where is_shown=1 order by is_shown asc, ord asc,  id asc';
			
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