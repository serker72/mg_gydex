<?
require_once('abstractitem.php');

//
class NumItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='numbers';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
}
?>