<?
require_once('abstractitem.php');

//итем - группа клиентов
class GroupItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='cl_groups';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		//из разбивки по группам
		$query = 'delete from cl_by_groups where gr_id='.$id.';';
		$it=new nonSet($query);
		
		AbstractItem::Del($id);
	}	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
	}
	
	
	
}
?>