<?
require_once('abstractitem.php');

//���� - ������ ��������
class GroupItem extends AbstractItem{
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='cl_groups';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		
		//�� �������� �� �������
		$query = 'delete from cl_by_groups where gr_id='.$id.';';
		$it=new nonSet($query);
		
		AbstractItem::Del($id);
	}	
	
	
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
		
	}
	
	
	
}
?>