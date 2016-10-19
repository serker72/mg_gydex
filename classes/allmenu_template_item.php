<?
require_once('NonSet.php');
require_once('abstractitem.php');

//абстрактный итем (без языковых таблиц)
class AllmenuTemplateItem extends AbstractItem{
	 
	
	//установка всех имен
	protected function init(){
		$this->tablename='allmenu_template';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	//установка имени таблицы
	
	
}
?>