<?
require_once('abstractitem.php');


//итем группа пол-лей
class DistrGroupItem extends AbstractItem{
	
	//установка всех имен
	protected function init(){
		$this->tablename='discr_groups';
	
		$this->item=NULL;
		$this->pagename='page.php';		
		
		
		$this->vis_name='is_blocked';
	}
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		//
		$query = 'delete from discr_users_by_groups where group_id='.$id.';';
		$it=new nonSet($query);
		
		$query = 'delete from discr_group_rights where group_id='.$id.';';
		$it=new nonSet($query);
		
		
		
		AbstractItem::Del($id);
	}	
	
	
}
?>