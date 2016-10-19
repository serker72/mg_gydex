<?
require_once('abstractitem.php');

//
class OtzItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='otzyv';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_on_site';	
		//$this->subkeyname='mid';	
	}
	
	
}
?>