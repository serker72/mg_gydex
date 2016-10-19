<?
require_once('abstractgroup.php');

require_once('faq_g_group.php');
require_once('faq_g_item.php');
require_once('solitem.php');
require_once('solgroup.php');




// абстрактная группа итемов (без языковых таблиц)
class FaqClientGroup extends AbstractGroup {
	public $fromname='from';
	
	//установка всех имен
	protected function init(){
		$this->tablename='faq_question';
		$this->pagename='viewsolution.php';		
		$this->subkeyname='parent_id';	
		$this->vis_name='is_shown';		
		
		
		
	}
	
  
	
	
	
	
	
	public function GetItems($on_site=0, $template1, $template2,  $group_id=-1, $solution_id=-1, $url, $question_id=0){
		$sm=new Smartyadm;
		$all=$this->GetItemsArr($on_site,   $group_id, $solution_id);
		
		
		//$sm->assign('filename', 'ed_faq.php');
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
		$sols['items']=array_reverse($sols['items']);
		
		$sols['items'][]=array('id'=>0, 'name'=>'CRM GYDEX. Общее.');
		$sols['items']=array_reverse($sols['items']);
		
		$grs=$_grs->GetItemsArr(0,0,10000000);
		//соберем все вопросы внутри нулевой группы по нашему продукту
		foreach($sols['items'] as $k=>$v){
			$flt=array();
			$flt[]='  t.group_id="0" ';
			$flt[]='  t.solution_id="'.$v['id'].'" ';
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
			
			//echo $sql.'<br>';
			
			$set=new mysqlset($sql);
			$rs=$set->GetResult();
			$rc=$set->GetResultNumRows();
			 
			$quests=array();
			for($i=0; $i<$rc; $i++){
				$f=mysqli_fetch_array($rs);
				foreach($f as $k1=>$v1) $f[$k1]=stripslashes($v1);
				
				 
				$quests[]=$f;
			}
			$v['quests']=$quests;
			$sols['items'][$k]=$v;
		}
		
		
		$sm->assign('sols',$sols['items']);
		$sm->assign('grs',$grs['items']);
		
		
		
		//соберем все группы и все вопросы по выбранному продукту
		if($solution_id!=-1){
			$curr_groups=array();
			$sql='select * from  faq_group where id in(select distinct group_id from faq_question where is_shown=1 and solution_id="'.$solution_id.'" ) order by ord desc, id asc';
			
			//echo $sql.'<br>';
			
			$set=new mysqlset($sql);
			$rs=$set->GetResult();
			$rc=$set->GetResultNumRows();
			
			for($i=0; $i<$rc; $i++){
				$f=mysqli_fetch_array($rs);
				foreach($f as $k1=>$v1) $f[$k1]=stripslashes($v1);
				
				 
				$curr_groups[]=$f;
			}
			
			$curr_groups=array_reverse($curr_groups);
			$curr_groups[]=array('id'=>0, 'name'=>'Общие вопросы');
			
			$curr_groups=array_reverse($curr_groups);
			
			foreach($curr_groups as $k=>$v){
				$sql='select t.*,
					g.name as gr_name,
					s.name as sol_name
			 from '.$this->tablename.'  as t
			 	left join solution as s on s.id=t.solution_id
				left join faq_group as g on g.id=t.group_id
			   where t.is_shown=1 and t.group_id="'.$v['id'].'" and t.solution_id="'.$solution_id.'" order by   t.ord desc,  t.id asc';
			   
			  // echo $sql.'<br>';
			   
			   $qs=array();
			   $set=new mysqlset($sql);
				$rs=$set->GetResult();
				$rc=$set->GetResultNumRows();
				
				for($i=0; $i<$rc; $i++){
					$f=mysqli_fetch_array($rs);
					foreach($f as $k1=>$v1) $f[$k1]=stripslashes($v1);
					
					$qs[]=$f;
				}
				$v['qs']=$qs;
				
				$curr_groups[$k]=$v;	
			}
			
			
			$sm->assign('gr',$curr_groups);
		
		}
		
		//выведем вопросы и ответы по заданной группе и продукту
		if(($solution_id!=-1)){
			$sql='select t.*,
					g.name as gr_name,
					s.name as sol_name
			 from '.$this->tablename.'  as t
			 	left join solution as s on s.id=t.solution_id
				left join faq_group as g on g.id=t.group_id
			   where t.is_shown=1  and t.solution_id="'.$solution_id.'" order by   t.ord desc,  t.id asc';
			   
			  // echo $sql.'<br>';
			 
			 $qs=array();
			 $set=new mysqlset($sql);
			  $rs=$set->GetResult();
			  $rc=$set->GetResultNumRows();
			  
			  for($i=0; $i<$rc; $i++){
				  $f=mysqli_fetch_array($rs);
				  foreach($f as $k1=>$v1) $f[$k1]=stripslashes($v1);
				  
				  $f['is_opened']= ($question_id==$f['id']);
					
					//echo $question_id.' vs '.$f['id'].'<br>';
					
				  $qs[]=$f;
			  }
			  
			  $sm->assign('qs', $qs);
			  $_f=new FaqGItem; $_s=new SolItem;
			  if($solution_id>0) $sol=$_s->getitembyid($solution_id); else $sol=array('id'=>0, 'name'=>'CRM GYDEX. Общее');
			  if($group_id>0) $group=$_f->getitembyid($group_id); else $group=array('id'=>0, 'name'=>'Общие вопросы');
			  
			  //var_dump($sol);
			  
			  $sm->assign('sol', $sol); 
			  $sm->assign('group', $group);
		}
		
		
		$sm->assign('url', $url);
		
		if($solution_id==-1) $txt=$sm->fetch($template1);
		elseif($solution_id!==-1) $txt=$sm->fetch($template2);
		
		return $txt;
	}
	
	 
	public function GetItemsArr($on_site=0,    $group_id=-1, $solution_id=-1){
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
		
		$set=new mysqlset($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		 
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			foreach($f as $k=>$v) $f[$k]=stripslashes($v);
			
			 
			$arr[]=$f;
		}
		
		//$arr['pages']=$pages;
		$all=array('items'=>$arr, 'pages'=>'');
		
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