<?
require_once('NonSet.php');
require_once('abstractitem.php');

//����������� ���� (��� �������� ������)
class AllmenuTemplateItem extends AbstractItem{
	 
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='allmenu_template';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	//��������� ����� �������
	
	
}
?>