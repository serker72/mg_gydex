<?
require_once('NonSet.php');
require_once('MysqlSet.php');

//итем вида подключения
class AttKindItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='attach_d';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	
}
?>