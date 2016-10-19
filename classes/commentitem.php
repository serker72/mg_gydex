<?
require_once('abstractitem.php');
 
 

//класс сообщение
class CommentItem extends AbstractItem{
	 
	
	//установка всех имен
	protected function init(){
		$this->tablename='comment';
	 	$this->vis_name='is_active';	
	}
	

	
	

	//удалить
	/*
	public function Del($id){
		//удалять ВСЕ!!!!
		
		//список всех вложенных
		$arr=Array();
		
		//находим все подразделы
		$this->SubsListView($id, $arr);
		
		//добавим сам раздел в список
		$arr[]=$id;
		
		//строим запрос на удаление найденных подразделов+самого раздела
		$cter=1; $q='';
		
		foreach($arr as $k=>$v){
			$q.=' '.$v;
			if($cter<count($arr)) $q.=',';
			$cter++;
		}
		
		
		 
		$q1='delete from '.$this->tablename.' where id in(';
		
		
		$q1.=$q.');';	
		
		//удаляем раздел+подразделы
		$ns=new nonSet($q1);
		
	
		//удаляем сам раздел
		//$this->DelOne($id);
		
		$this->item=NULL;
	}	
	
	
	
	//Вспомогательная функция при удалении раздела	
	//РЕКУРСИЯ по списку
	//строим список всех подразделов
	protected function SubsListView($id,&$arr){
		$l_arr=$this->GetSubsList($id, $arr);
		if(count($l_arr)>0){
			foreach($l_arr as $k=>$v){
				$this->SubsListView($v,$arr);
			}
		}
		
	}
	
	//Вспомогательная функция при удалении раздела
	//список всех вложенных подразделов
	protected function GetSubsList($id, &$arr){
		$l_arr=Array();
		$query='select * from '.$this->tablename.' where parent_id="'.$id.'"';
		$set=new mysqlSet($query);
		$count=$set->GetResultNumRows();
		if($count>0){
			$rs=$set->GetResult();
			for($i=0;$i<$count;$i++){
				$f=mysqli_fetch_array($rs);
				$arr[]=$f['id'];
				$l_arr[]=$f['id'];
			}
		}
		return $l_arr;
	}
	
	
	//получение первого итема по набору полей
	public function GetItemByFields($params, $extra=NULL){
		
		$qq='';
		foreach($params as $key=>$val){
			if($qq=='') $qq.='t.'.$key.'="'.$val.'" ';
			else $qq.=' and t.'.$key.'="'.$val.'" ';
		}
		
		if($extra===NULL) 
			$item=new mysqlSet('select * from '.$this->tablename.' as t  where '.$qq.';');
		else{
			
			foreach($extra as $key=>$val){
				if($qq=='') $qq.='tf.'.$key.'="'.$val.'" ';
				else $qq.=' and tf.'.$key.'="'.$val.'" ';
			}	
			
			$item=new mysqlSet('select * from '.$this->tablename.' as t inner join '.$this->mf_tablename.' as tf on (t.id=tf.message_id)  where '.$qq.';');
		}
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
	
	
	
	//конструктор УРЛ
	public function ConstructPath($id,$is_shown=0,$separator='/', $item=NULL,  $clinic=NULL, $rubric=NULL, $indexname=''){
		
		if($item===NULL){
			$item=$this->GetItemById($id,$is_shown);	
		}
		
		//найдем клинику
		if($clinic===NULL){
			$_ci=new ClinicAddrItem;
			$clinic=$_ci->GetItemById($item['address_id']);
			 
		}
		

		
		//найдем любую рубрику клиники
		if($rubric===NULL){
			$_ri=new RubrItem;
			$_cri=new ClinicAddrRubrItem;
			$rubrics=$_cri->GetAddrRubricsArr($clinic['id']);
			$rubric=$rubrics[0];
			$rubric['url']=$_ri->ConstructPath($rubric['id']);
		}
		

		
		if($item!==false){
			//$txt='http://'.urlencode($item['domain']);
			$txt=$rubric['url'].''.$clinic['url'].$separator.'review/review_'.$item['id'].$separator.$indexname;
		}else return PATH404;
		
		return $txt;
	}*/
}
?>