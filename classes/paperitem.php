<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');

//итем статья
class PaperItem extends AbstractLangItem{
	protected $subkeyname;
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='paper_item';
		$this->lang_tablename='paper_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='paper_id';
		$this->lang_id_name='lang_id';
		
		$this->vis_name='is_shown';
		
		$this->subkeyname='mid';
	}
	
	
	
	
	
	//конструирование пути к статье при кривых URL в клиентской части
	public function ConstructPath($good_id,$lang_id=LANG_CODE,$separator='/'){
		$txt='';
		
		$pi=new PaperItem();
		$good=$pi->GetItemById($good_id,$lang_id,1);
		if($good!==false){
			//if(HAS_URLS){
			$parent_id=$good['mid'];
			
			$mi=new MmenuItem();
			$mmenuitem=$mi->GetItemById($parent_id,$lang_id,1);
			
			$txt=$mi->ConstructPath($parent_id,$lang_id,1,$separator);
			if($txt!=$this->error404){
				//нет ошибки, возвращаем путь
				//если урл кривой, то возвращаем полученный путь
				//если урл прямой, то путь игнорируем, а возвращаем ссылку на товар
				//*/
				if(HAS_URLS){
					$txt=$txt.'paper_'.$good_id.'.html';
				}else $txt=$separator.'paper.php?id='.$good_id;
			}
		}else return $this->error404;
		
		return $txt;
	}
	
	
	
	//показ подитемов
	
}
?>