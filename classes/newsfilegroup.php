<?
require_once('abstractfilegroup.php');

// абстрактная группа файлов
class NewsFileGroup extends AbstractFileGroup {
	protected $storage_id;
	 
	
	//установка всех имен
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