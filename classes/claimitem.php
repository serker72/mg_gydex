<?
require_once('abstractitem.php');

//
class ClaimItem extends AbstractItem{
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='claim';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
}
?>