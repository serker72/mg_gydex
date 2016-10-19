<?
require_once('abstractitem.php');



//итем право
class DistrRightItem extends AbstractItem{
	
	//установка всех имен
	protected function init(){
		$this->tablename='discr_rights';
		
		$this->item=NULL;
		$this->pagename='page.php';		
		
		
	}
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		//
		$query = 'delete from discr_user_rights where right_id='.$id.';';
		$it=new nonSet($query);
		
		$query = 'delete from discr_group_rights where right_id='.$id.';';
		$it=new nonSet($query);
		
		
		
		AbstractItem::Del($id);
	}	
	
	
	
}
?>