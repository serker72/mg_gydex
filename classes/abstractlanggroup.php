<?

require_once('abstractgroup.php');




// абстрактная группа итемов c языковыми таблицами
class AbstractLangGroup extends AbstractGroup{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;
	
	protected $langs=Array();//массив списка языков
	
	//установка всех имен
	protected function init(){
		$this->tablename='table';
				$this->lang_tablename='menu_lang';
		$this->pagename='view.php';		
		$this->subkeyname='mid';	
		
		$this->mid_name='mid';
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
	
	
	
	//сколько итемов
	public function CalcItemsById($id, $lang_id=0){
		
		if($lang_id==0){
			$query='select count(*) from '.$this->tablename.' where '.$this->subkeyname.'='.$id.';';
		}else{
			$query = 'select count(*) from '.$this->tablename.' as t where t.'.$this->subkeyname.'='.$id.' and t.id in(select l.'.$this->mid_name.' from '.$this->lang_tablename.' as l where l.'.$this->mid_name.'=t.id and l.'.$this->lang_id_name.'="'.$lang_id.'")';
		}
		
		//echo $query; die();
		$countt=new mysqlSet($query);
		$rez=$countt->getResult();
		$re = mysqli_fetch_array($rez);
		unset($countt);
		return $re['count(*)'];
	}
	
	
	//установка наименования поля id
	public function SetIdName($name='mid'){
		$this->subkeyname=$name;
	}
	
	//установка имени страницы для вывода номеров навигатора
	public function SetPageName($name='subs.php'){
		$this->pagename=$name;
	}
	
	//получение наименования поля id
	public function GetIdName(){
		return $this->subkeyname;
	}
	
	
	protected function GenerateSQL($params, $notparams=NULL, $orderbyparams=NULL, &$sql_count=''){
		$sql='';
		
		$sql='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.id=l.'.$this->mid_name.' ';
		
		//запрос для посчета общего числа итемов
		$sql_count='select count(*) from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.id=l.'.$this->mid_name.' ';
		
		$qq='';
		foreach($params as $k=>$v){
			$qq.=' and '.$k.'="'.$v.'" ';
		}
		if($notparams!==NULL){
			foreach($notparams as $k=>$v){
				$qq.=' and '.$k.'<>"'.$v.'" ';
			}
		}
		
		$qq2='';
		if($orderbyparams!==NULL){
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
	
	//итемы в тегах option
	public function GetItemsOptByLang_id($current_id=0,$fieldname='name',$lang_id=LANG_CODE,$filter_params=NULL){
		$txt='';
		$filter='';
		if($filter_params!==NULL){
			foreach($filter_params as $k=>$v){
				$filter.=' and '.$k.'="'.$v.'" ';
			}
		}
		
		$sql='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where l.'.$this->lang_id_name.'='.$lang_id.' and l.'.$this->mid_name.'=t.id '.$filter.' order by '.$fieldname;
		$set=new mysqlSet($sql);
		$tc=$set->GetResultNumRows();
		if($tc>0){
			$rs=$set->GetResult();
			for($i=0;$i<$tc;$i++){
				$f=mysqli_fetch_array($rs);
				$txt.="<option value=\"$f[id]\" ";
				
				if($current_id==$f['id']) $txt.='SELECTED';
				
				$txt.=">".htmlspecialchars(stripslashes($f[$fieldname]))."</option>";
			}
		}
		return $txt;
	}
	
	
}
?>