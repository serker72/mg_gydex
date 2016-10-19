<?
require_once('abstractitem.php');

//итем многоязычный
class AbstractLangItem extends AbstractItem{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;

	
	
	//установка всех имен
	protected function init(){
		$this->tablename='allmenu';
		$this->lang_tablename='menu_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='mid';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
	}
	
	//добавить 
	public function Add($params, $lang_params){
		
		
		$it1=new AbstractItem(); $it2=new AbstractItem();
		
		//параметры неязыковые
		$it1->SetTableName($this->tablename);
		$mid=$it1->Add($params);
		
		//параметры языковые
		$lang_params[$this->mid_name]=$mid;
		$it2->SetTableName($this->lang_tablename);
		$it2->Add($lang_params);
		
		unset($it1,$it2);
		return $mid;
	}
	
	//добавить языковый параметр указанному итему с указанным значением языка
	//необходимо при заведении языка, отличного от по умолчанию, для нового раздела
	public function AddLanguage($mid,$lang_id){
		//параметры языковые
		
		
		//проверим, есть ли такой язык если нет то добавим
		$sql='select count(*) from '.$this->lang_tablename.' where '.$this->mid_name.'="'.$mid.'" and '.$this->lang_id_name.'="'.$lang_id.'"';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		if($f['count(*)']==0){
		
			$lang_params=Array();
			$lang_params[$this->mid_name]=$mid;
			$lang_params[$this->lang_id_name]=$lang_id;
			
			
			$it2=new AbstractItem();
			$it2->SetTableName($this->lang_tablename);
			$it2->Add($lang_params);
		}
		
		
	}
	
	
	
	
	
	//править
	public function Edit($id,$params=NULL, $lang_params=NULL){
		
		$it1=new AbstractItem(); 
		
		//параметры неязыковые
		if($params!==NULL){
			$it1->SetTableName($this->tablename);
			$it1->Edit($id,$params);
			unset($it1);
		}
		
		
		//параметры языковые
		if($lang_params!==NULL){
			$qq='';
			foreach($lang_params as $key=>$val){
				if(($key!=$this->mid_name)&&($key!=$this->lang_id_name)){
					if($qq=='') $qq.=$key.'="'.$val.'"';
					else $qq.=','.$key.'="'.$val.'"';
				}
			}
			//$query='update '.$this->lang_tablename.' set '.$qq.' where '.$this->mid_name.'="'.$lang_params[$this->mid_name].'" and '.$this->lang_id_name.'="'.$lang_params[$this->lang_id_name].'"';
			$query='update '.$this->lang_tablename.' set '.$qq.' where '.$this->mid_name.'="'.$id.'" and '.$this->lang_id_name.'="'.$lang_params[$this->lang_id_name].'"';
			
			$it=new nonSet($query);
			
			
			unset($it);
		}
		
	}
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		$it1=new AbstractItem(); 
		
		//параметры неязыковые
		$it1->SetTableName($this->tablename);
		$it1->Del($id);
		unset($it1);
		
		//параметры языковые
		$query = 'delete from '.$this->lang_tablename.' where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		
		unset($it);				
		
		
		
		$this->item=NULL;
	}	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		$sql='delete from '.$this->lang_tablename.' where '.$this->mid_name.' in(select id from '.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$ns=new nonSet($sql);
		
		$sql='delete from '.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.')';
		$ns=new nonSet($sql);
	}
	
	
	
	
	//получить по айди , параметру языка, коду видимости
	public function GetItemById($id,$lang=1,$mode=0){
		if($id===NULL) return false;
		
		
		if($mode==0)	$query='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.id='.$id.' and t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'='.$lang.'';
		else $query='select * from '.$this->tablename.' as t, '.$this->lang_tablename.' as l where t.id='.$id.' and t.id=l.'.$this->mid_name.' and l.'.$this->lang_id_name.'='.$lang.' and l.'.$this->vis_name.'=1';
		
		$item=new mysqlSet($query);
		
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
	
	
	
	
	//изменить видимость всех языковых подразделов
	public function ToggleVisible($id,$visibility=1){
		$sql='update '.$this->lang_tablename.' set is_shown="'.$visibility.'" where '.$this->mid_name.'="'.$id.'"';
		$ns=new NonSet($sql);
		unset($ns);
		
		return true;
	}
	
	//изменить видимость подраздела с заданным языком
	public function ToggleVisibleLang($id,$lang_id,$visibility=1){
		$sql='update '.$this->lang_tablename.' set is_shown="'.$visibility.'" where '.$this->mid_name.'="'.$id.'" and '.$this->lang_id_name.'="'.$lang_id.'"';
		$ns=new NonSet($sql);
		unset($ns);
		
		return true;
	
	}
	
	
}
?>