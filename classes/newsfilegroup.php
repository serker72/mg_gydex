<?
require_once('abstractfilegroup.php');

// ����������� ������ ������
class NewsFileGroup extends AbstractFileGroup {
	protected $storage_id;
	 
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='news_file';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		$this->subkeyname='news_id';
			
	
		$this->storage_name='storage_id';	
		$this->storage_path=ABSPATH.'upload/news/';	
	}
	
	
	
	
	 
	 
}
?>