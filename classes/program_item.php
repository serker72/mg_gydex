<?
 
require_once('abstractitem.php');

//����������� ���� (��� �������� ������)
class ProgramItem extends AbstractItem{
	 
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='program';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_active';	
		//$this->subkeyname='mid';	
	}
	
	
	
}
?>