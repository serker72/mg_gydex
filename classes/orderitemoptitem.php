<?
require_once('abstractitem.php');

//����� ��� ������ � ������
class OrderItemOptItem extends AbstractItem{
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='order_item_option';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
	
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
		
		
	}
	
	
	
	
	
	
}
?>