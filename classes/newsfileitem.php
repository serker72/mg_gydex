<?
require_once('abstractfileitem.php');
 

//абстрактный файл
class NewsFileItem extends AbstractFileItem{
	 
	 
	
	//установка всех имен
	protected function init(){
		$this->tablename='news_file';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		$this->subkeyname='news_id';
			
		 
		$this->storage_path=ABSPATH.'upload/news/';	
	}
	
	
	 
}
?>