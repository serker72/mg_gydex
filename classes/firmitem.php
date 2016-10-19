<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');


//итем фирма
class FirmItem extends AbstractLangItem{
	protected $subkeyname;
//	protected $dict_kind_tablename='dict_kind';
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='firms';
		$this->lang_tablename='firms_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='firmid';
		$this->lang_id_name='lang_id';
		$this->subkeyname='mid';
		$this->vis_name='is_shown';
	}
	
	
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		$this->DelChilds($id);
		
		AbstractLangItem::Del($id);
	}	
	
	
	//вычищаем айди фирмы из списка товаров
	public function DelChilds($id){
	
		//прикрепления словаря
		$query = 'update price_item set '.$this->mid_name.'=0 where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		
	}
	
	//построение клиентского адреса
	public function ConstructUrl($id, $lang_code=LANG_CODE, $params=Array(), $is_shown=0){
		$id_name='id'; $name_name='name';
		
		$except_name=$id_name;
		
		$except_arr=Array();
		$except_arr['PHPSESSID']='';
		$except_arr[$except_name]='';
		
		$result=''; $param_str=''; $st_params=Array();
		if(count($params)>0){
			foreach($params as $k=>$v){
				if(!isset($except_arr[$k])) $st_params[]='&'.$k.'='.urlencode($v);
			}
		}
		
		
		$result= '/firm.php?'.$id_name.'='.$id;
		if(count($st_params)>0) $result.=implode('',$st_params);
		
		return $result;
		
	}
	
	
	
	//показ подитемов
	
}
?>