<?
require_once('abstractitem.php');

// ���� ��� ����������� uzera
class RegKindItem extends AbstractItem{
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='reg_kind';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		
		//�� ������ - �������� � ��� ��� �����������
		$query = 'update clients set reg_id=0 where reg_id="'.$id.'";';
		$it=new nonSet($query);
		
		AbstractItem::Del($id);
	}	
	
	
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
		
	}
	
	
}
?>