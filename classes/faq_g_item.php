<?
require_once('abstractitem.php');

//
class FaqGItem extends AbstractItem{
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='faq_group';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
}
?>