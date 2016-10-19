<?
 
require_once('abstractitem.php');

//абстрактный итем (без языковых таблиц)
class ProgramItem extends AbstractItem{
	 
	
	//установка всех имен
	protected function init(){
		$this->tablename='program';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_active';	
		//$this->subkeyname='mid';	
	}
	
	
	
}
?>