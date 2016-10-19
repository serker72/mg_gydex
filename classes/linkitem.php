<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');

//итем ссылка
class LinkItem extends AbstractLangItem{
	protected $subkeyname;
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='link_item';
		$this->lang_tablename='link_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='link_id';
		$this->lang_id_name='lang_id';
		
		$this->vis_name='is_shown';
		
		$this->subkeyname='mid';
	}
	
	
	
	
	
	//показ подитемов
	
}
?>