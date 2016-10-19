<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');

//итем новость
class NewsItem extends AbstractLangItem{
	protected $subkeyname;
	
	
	//установка всех имен
	protected function init(){
		$this->tablename='news_item';
		$this->lang_tablename='news_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		$this->mid_name='news_id';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
		$this->subkeyname='mid';
	}
	
	//все унаследовано!:)
	
	
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
					$result.='news_'.$id.'.html';
				}
				
				if(($result!=$this->error404)&&(count($st_params)>0)) $result.=implode('',$st_params);
			}else{
				$result= '/readnews.php?'.$id_name.'='.$id;
				if(count($st_params)>0) $result.=implode('',$st_params);
			}
		}
		
		/*$new=$this->GetItemById($id, $lang_code, $is_shown);
		if($new==false){
			$result=$this->error404;
		}else{
			if(HAS_URLS){
				$result='/news_'.$id.'.html';
			}else{
				$result= '/readnews.php?'.$id_name.'='.$id;
				
			}
			if(count($st_params)>0) $result.=implode('',$st_params);
		}
		*/
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
				$result.='readnews'.$id;
		}else{
			if($result!=$this->error404)
				$result.='readnews.php?'.$id_name.'='.$id;
		}
		return $result;
	}
	
	
	public function Del($id){
		
		new nonset('delete from comment where tablename="news" and item_id="'.$id.'"');
		new nonset('delete from news_file where news_id="'.$id.'"');
		
		
		return parent::Del($id);	
	}
}
?>