<?
require_once('abstractlangitem.php');

require_once('pricedisp.php');
require_once('conditem.php');

//итем товар
class PriceBaseItem extends AbstractLangItem{
	protected $subkeyname;
	
	//поле для экз-ра класса - диспетчера цен
	public $price_disp;
	
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='price_item';
		$this->lang_tablename='price_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='price_id';
		$this->lang_id_name='lang_id';
		$this->subkeyname='mid';
		$this->vis_name='is_shown';
		
		$this->price_disp=new PriceDisp();
	}
	
	
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		
		
		$good=$this->GetItemById($id);
		
		//удалить рекомендуемые товары
		$query = 'delete from goods_rekommend where pri_lid="'.$id.'" or sec_lid="'.$id.'"';
		$it=new nonSet($query);
		
		
		//удалить все виды цен у товара
		$query = 'delete from good_price where good_id="'.$id.'"';
		$it=new nonSet($query);
		
		//удалить значения свойств для данного товара:
		//словарь м.б. прикреплен к разделу, м.б. унаследован разделом, м.б. прикреплен к товару
		
		//прикреплен к товару
		
		//значения свойств
		$query = 'delete from prop_value_lang where value_id in (select id from prop_value where item_id="'.$id.'");';
		$it=new nonSet($query);
		
		$query = 'delete from prop_value where item_id="'.$id.'"';
		$it=new nonSet($query);
		
		//прикрепления словаря
		$query = 'delete from dict_attach_d where attach_code="3" and key_value="'.$id.'";';
		$it=new nonSet($query);
		
		
		
		$it1=new AbstractItem(); 
		
		//параметры неязыковые
		$it1->SetTableName($this->tablename);
		$it1->Del($id);
		unset($it1);
		
		//параметры языковые
		$query = 'delete from '.$this->lang_tablename.' where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		$this->item=NULL;
	}	
	
	
	//групповое удаление по разным mid
	public function DelGroup($q_string){
	
		//удалить все рекоменд товары
		$query = 'delete from goods_rekommend where pri_lid in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		$query = 'delete from goods_rekommend where sec_lid in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		
	
		//удалить все виды цен 
		$query = 'delete from good_price where good_id in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		
		//удалить значения свойств для данного товара:
		//словарь м.б. прикреплен к разделу, м.б. унаследован разделом, м.б. прикреплен к товару
		
		//прикреплен к товару
		
		//значения свойств
		$query = 'delete from prop_value_lang where value_id in (select id from prop_value where item_id in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.')));';
		$it=new nonSet($query);
		
		$query = 'delete from prop_value where item_id in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		
		//прикрепления словаря
		$query = 'delete from dict_attach_d where attach_code="3" and key_value in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		
		AbstractLangItem::DelGroup($q_string);
	}
	
	
	
	//конструирование пути к товару при кривых URL в клиентской части
	public function ConstructPath($good_id,$lang_id=LANG_CODE,$separator='/'){
		
		return $separator.$this->ConstructUrl($good_id, $lang_id);
	}
	
	//построение клиентского адреса
	public function ConstructUrl($id, $lang_code=LANG_CODE, $params=Array(), $is_shown=0){
		$id_name='id'; 
		
		$name_name='name';
		
		$except_arr=Array();
		$except_arr['PHPSESSID']='';
		$except_arr[$id_name]='';
		$except_arr[$name_name]='';
		
		$result=''; $param_str=''; $st_params=Array();
		if(count($params)>0){
			foreach($params as $k=>$v){
				if(!isset($except_arr[$k])) $st_params[]='&'.$k.'='.urlencode($v);
			}
		}
		
		$mi=new MmenuItem();
		$new=$this->GetItemById($id, $lang_code, $is_shown);
		if($new==false){
			$result=$this->error404;
		}else{
			if(HAS_URLS){
				$result=$mi->ConstructPath($new['mid'],$lang_code,$is_shown);
				if($result!=$this->error404){
					//добавить readnews
					$result.='goods'.$id;
				}
				
				if(($result!=$this->error404)&&(count($st_params)>0)) $result.=implode('',$st_params);
			}else{
				$result= '/good.php?'.$id_name.'='.$id;
				if(count($st_params)>0) $result.=implode('',$st_params);
			}
		}
		return $result;
	}
	
	//сокращенная функция генерации адреса
	//исключаем обработки разделов и проверки сузествования итема
	public function ConstructUrl_small($id, $url){
		$id_name='id'; 
		$name_name='name';
		
		$result=$url;
		if(HAS_URLS){
			if($result!=$this->error404)
				$result.='goods'.$id;
		}else{
			if($result!=$this->error404)
				$result.='good.php?'.$id_name.'='.$id;
		}
		return $result;
	}
	
	
	//показ подитемов
	
}
?>