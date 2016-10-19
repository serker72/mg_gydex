<?
require_once('abstractlanggroup.php');




// группа условий применения цен 
class CondGroup extends AbstractLangGroup{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;
	
	//установка всех имен
	protected function init(){
		$this->tablename='cond_price';
		$this->lang_tablename='cond_price_lang';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';	
	}
	
	
	
	
	
	
	
	//список итемов
	public function GetItemsById($id, $mode=0,$from=0,$to_page=10){
		//список позиций
		$txt='';

		
		return $txt;
	}
	
	
	
	
	
	//список итемов
	public function GetItemsCliById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		return $txt;
	}
	
	//список итемов версия для печати
	public function GetItemsCliPrintById($id, $filtermode=0,$params=false){
		//список позиций
		$txt=''; $filter=''; $fll='';
		
		
		
		return $txt;
	}
	
	
	//список итемов в тегах оптион
	public function DrawCondsOpt($current_id=0){
		$txt='';
		$sql='select * from '.$this->tablename.' order by id';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($f['id']==$current_id){
				$txt.='<option value="'.$f['id'].'" SELECTED>'.stripslashes($f['name']).'</option>';
			}else{
				$txt.='<option value="'.$f['id'].'">'.stripslashes($f['name']).'</option>';
			}
		}
		
		return $txt;
	}
	
	
	
	//сколько итемов
	/*
	public function CalcItemsById($id, $mode=0){
		
		if($mode==0){
			$query='select count(*) from '.$this->tablename.' where '.$this->subkeyname.'='.$id.';';
		}else{
			$query='select count(*) from '.$this->tablename.' where is_russ=1 and '.$this->subkeyname.'='.$id.';';
		}
		//echo $query; die();
		$countt=new mysqlSet($query);
		$rez=$countt->getResult();
		$re = mysqli_fetch_array($rez);
		unset($countt);
		return $re['count(*)'];
	}*/
	
	
	
	
	
	protected function GenerateSQL($params, $notparams=NULL, $orderbyparams=NULL, &$sql_count=''){
		$sql='';
		
		//$sql='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.id=l.'.$this->mid_name.' ';
		$sql='select * from '.$this->tablename;
		$sql_count='select count(*) from '.$this->tablename;
		
		
		//запрос для посчета общего числа итемов
		//$sql_count='select count(*) from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.id=l.'.$this->mid_name.' ';
		
		$qq='';
		foreach($params as $k=>$v){
			$qq.=' and '.$k.'="'.$v.'" ';
		}
		if($notparams!=NULL){
			foreach($notparams as $k=>$v){
				$qq.=' and '.$k.'<>"'.$v.'" ';
			}
		}
		
		$qq2='';
		if($orderbyparams!=NULL){
			$cter=0;
			foreach($orderbyparams as $k=>$v){
				if($cter==0) $qq2.=' order by ';
				
				$qq2.=$v.'';
				$cter++;
				
				if($cter!=count($orderbyparams)) $qq2.=', ';
			}
		}
		
		$sql=$sql.$qq.$qq2;
		$sql_count=$sql_count.$qq;
		
				
		return $sql;
	}
	
	
	
	
}
?>