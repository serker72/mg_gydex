<?
require_once('abstractitem.php');

//������������� ������
class RekomItem extends AbstractItem{
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='goods_rekommend';
		$this->item=NULL;
		$this->pagename='page.php';		
	}
	
	
	
	
	
	
}
?>