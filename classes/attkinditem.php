<?
require_once('NonSet.php');
require_once('MysqlSet.php');

//���� ���� �����������
class AttKindItem extends AbstractItem{
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='attach_d';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
}
?>