<?
require_once('abstractitem.php');


//���� ������ ���-���
class DistrGroupItem extends AbstractItem{
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='discr_groups';
	
		$this->item=NULL;
		$this->pagename='page.php';		
		
		
		$this->vis_name='is_blocked';
	}
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		
		//
		$query = 'delete from discr_users_by_groups where group_id='.$id.';';
		$it=new nonSet($query);
		
		$query = 'delete from discr_group_rights where group_id='.$id.';';
		$it=new nonSet($query);
		
		
		
		AbstractItem::Del($id);
	}	
	
	
}
?>