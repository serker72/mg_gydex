<?
require_once('abstractgroup.php');
 
require_once('commentitem.php');

// список сообщений
class CommentGroup extends AbstractGroup {
	
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='comment';
		$this->instance=new CommentItem;
	 
	}
	
	
	
	//общая функция
	public function ShowMessages($address_id, $tablename='news', $template, $is_active=1, $is_ajax=false){
			
		return $this->GetNode($address_id, $tablename, $template, $is_active, $is_ajax);
		
	}
	
	
	//ветвь сообщений
	public function GetNode($address_id, $tablename, $template, $is_active=1, $is_ajax=false, &$comments){
		if($is_ajax) $sm=new SmartyAj;
		else $sm=new SmartyAdm;
		
		$flt='';
		if($is_active==1) $flt=' and m.is_active=1 ';
		
		$sql='select m.* 
		
	 
		from 
			'.$this->tablename.' as m
			 
			
		where item_id="'.$address_id.'"
		and  tablename="'.$tablename.'"
		 
		'.$flt.'
		
		order by m.pdate asc, m.id asc
		';	
		
		
		$set=new mysqlSet($sql);
		//echo $sql;
		
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
	 
		$all=array();
		
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$f['pdate']=date("d.m.Y H:i:s", $f['pdate']);
			
			//$f['url']=$this->instance->ConstructPath($f['id'], 0, '/', $f);
			
			
			//$f['subs']=$this->GetNode($address_id, $f['id'], $template, $is_active, $is_ajax);
			
			$all[]=$f;
		}
		
		$comments=$all;
		$sm->assign('address_id', $address_id);
		$sm->assign('tablename', $tablename);
		$sm->assign('items', $all);
		
		return $sm->fetch($template);
	}
	
	
	public function CalcNew( $tablename){
		$sql='select count(*) from '.$this->tablename.' where is_new=1 and tablename="'.$tablename.'"   ';
		$set=new mysqlSet($sql);
		//echo $sql;
		
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		$f=mysqli_fetch_array($rs);
		
		return 	(int)$f[0];
	}
	
	public function FindNew($tablename){
		$sql='select * from '.$this->tablename.' where is_new=1 and tablename="'.$tablename.'" order by pdate desc, id desc limit 1  ';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f;
		}else return false;
	}
	
	
	public function Calc($item_id, $tablename, $is_shown=1){
		$sql='select count(*) from '.$this->tablename.' where tablename="'.$tablename.'" and item_id="'.$item_id.'" ';
		if($is_shown==1) $sql.=' and is_active=1 ';
		
		$set=new mysqlSet($sql);
		//echo $sql;
		
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		$f=mysqli_fetch_array($rs);
		
		return 	(int)$f[0];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>