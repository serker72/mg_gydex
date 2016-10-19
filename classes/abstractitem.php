<?
require_once('NonSet.php');
require_once('MysqlSet.php');

//абстрактный итем (без языковых таблиц)
class AbstractItem{
	protected $tablename;//='mmenu';
	protected $item;//=NULL;
	protected $pagename;//='viewsubs.php';	
	protected $vis_name;
	protected $subkeyname='mid';
	
	public $error404=PATH404;//'/404.php';
	
	public function __construct(){
		$this->init();
	}
	
	//установка всех имен
	protected function init(){
		$this->tablename='table';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	//установка имени таблицы
	public function SetTableName($name){
		$this->tablename=$name;
		return true;
	}
	
	//добавить 
	public function Add($params){
		$names='';
		$vals='';
		
		
		foreach($params as $key=>$val){
			if($names=='') $names.=$key;
			else $names.=','.$key;
			
			if($vals=='') {
				if($val===NULL) $vals.='NULL';
				else  $vals.='"'.$val.'"';
			}else{
				if($val===NULL)  $vals.=','.' NULL';
				else  $vals.=','.'"'.$val.'"';
			}
		}
		$query='insert into '.$this->tablename.' ('.$names.') values('.$vals.');';
		$it=new nonSet($query);
		
		
		$code=$it->getResult();
		unset($it);
		return $code;
	}
	
	//править
	public function Edit($id,$params){
		$qq='';
		foreach($params as $key=>$val){
			if($qq=='') $qq.=$key.'="'.$val.'"';
			else $qq.=','.$key.'="'.$val.'"';
		}
		$query='update '.$this->tablename.' set '.$qq.' where id="'.$id.'";';
		$it=new nonSet($query);
		
		
		
		unset($it);
	}
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		//
		$query = 'delete from '.$this->tablename.' where id='.$id.';';
		$it=new nonSet($query);
		
		
		unset($it);				
		
		$this->item=NULL;
	}	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
		$sql='delete from '.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.')';
		//удаляем раздел+подразделы
		$ns=new nonSet($sql);
		
	}
	
	
	
	//получить по айди и коду видимости
	public function GetItemById($id,$mode=0){
		if($id===NULL) return false;
		if($mode==0) $item=new mysqlSet('select * from '.$this->tablename.' where id='.$id.';');
		else $item=new mysqlSet('select * from '.$this->tablename.' where '.$this->vis_name.'=1 and id='.$id.';');
		
//		if($mode==1) $item=new mysqlSet('select * from '.$this->tablename.' where is_russ=1 and id='.$id.';');
	//	if($mode==2) $item=new mysqlSet('select * from '.$this->tablename.' where is_en=1 and id='.$id.';');
		$result=$item->getResult();
		$rc=$item->getResultNumRows();
		unset($item);
		if($rc!=0){
			$res=mysqli_fetch_array($result);
			$this->item= Array();
			foreach($res as $key=>$val){
				$this->item[$key]=$val;
			}
			
			return $this->item;
		} else {
//			echo 'ccc'; die();
			$this->item=NULL;
			return false;
		}
	}
	
	//получение первого итема по набору полей
	public function GetItemByFields($params){
		
		$qq='';
		foreach($params as $key=>$val){
			if($qq=='') $qq.=$key.'="'.$val.'" ';
			else $qq.=' and '.$key.'="'.$val.'" ';
		}
		
		$item=new mysqlSet('select * from '.$this->tablename.' where '.$qq.';');
		$result=$item->getResult();
		$rc=$item->getResultNumRows();
		unset($item);
		if($rc!=0){
			$res=mysqli_fetch_array($result);
			$this->item= Array();
			foreach($res as $key=>$val){
				$this->item[$key]=$val;
			}
			
			return $this->item;
		} else {
			$this->item=NULL;
			return false;
		}	
		
	}
	
	
	//изменить видимость всех языковых подразделов
	public function ToggleVisible($id,$visibility=1){
		$sql='update '.$this->tablename.' set '.$this->vis_name.'="'.$visibility.'" where id="'.$id.'"';
		$ns=new NonSet($sql);
		unset($ns);
		
		return true;
	}
	
	
	public function GetPageName(){
		return $this->pagename;	
	}
	
}
?>