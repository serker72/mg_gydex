<?
require_once('abstractitem.php');



//���� �����
class DistrRightItem extends AbstractItem{
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='discr_rights';
		
		$this->item=NULL;
		$this->pagename='page.php';		
		
		
	}
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		
		//
		$query = 'delete from discr_user_rights where right_id='.$id.';';
		$it=new nonSet($query);
		
		$query = 'delete from discr_group_rights where right_id='.$id.';';
		$it=new nonSet($query);
		
		
		
		AbstractItem::Del($id);
	}	
	
	
	
}
?>