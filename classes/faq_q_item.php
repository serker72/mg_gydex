<?
require_once('abstractitem.php');

//
class FaqQItem extends AbstractItem{
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='faq_question';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
}
?>