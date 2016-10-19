<?
require_once('abstractitem.php');

//
class SolItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='solution';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
}
?>