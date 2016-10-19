<?
require_once('abstractitem.php');
require_once('global.php');

//итем язык
class LangItem extends AbstractItem{
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='langs';
		$this->pagename='viewlangs.php';		
		$this->item=NULL;
		$this->vis_name='is_shown';	
	}
	
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		if($id!=LANG_CODE){
			//статьи
			$query = 'delete from paper_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			//новости
			$query = 'delete from news_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			//ссылки
			$query = 'delete from link_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			//из видов статусов заказа
			$query = 'delete from order_status_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			
			//из валют и цен
			$query = 'delete from currency_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			$query = 'delete from currency_use_lang where lang_id='.$id.';';
			$it=new nonSet($query);
	
			$query = 'delete from price_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			$query = 'delete from cond_price_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			
			//словари свойств
			$query = 'delete from prop_value_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
	
			$query = 'delete from prop_name_lang where lang_id='.$id.';';
			$it=new nonSet($query);
	
	
			$query = 'delete from dict_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			
			
			$query = 'delete from photo_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			$query = 'delete from menu_lang where lang_id='.$id.';';
			$it=new nonSet($query);
			
			
			AbstractItem::Del($id);
			$this->item=NULL;
		}
	}	
	
	
}
?>