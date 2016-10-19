<?
require_once('abstractitem.php');

//правило использования валют по языкам
class CurrUse extends AbstractItem{
		
	//установка всех имен
	protected function init(){
		$this->tablename='currency_use_lang';
		$this->item=NULL;
		$this->pagename='page.php';	
		$this->vis_name='is_shown';	
		//$this->subkeyname='mid';	
	}
	
	
	//добавить 
	public function Add($params){
		if($this->CheckByCurrIdLangId($params['value_id'],$params['lang_id'])==false)
			return AbstractItem::Add($params);
		else return 0;
	}
	
	//проверить, есть ли уже правило для такой валюты и такого языка
	public function CheckByCurrIdLangId($curr_id,$lang_id){
		$sql='select count(*) from '.$this->tablename.' where lang_id="'.$lang_id.'" and value_id="'.$curr_id.'"';
		
		$item=new mysqlSet($sql);
		$rs=$item->getResult();
		$f=mysqli_fetch_array($rs);
		
		if($f['count(*)']>0) return true;
		else return false;
	}
	
	
	//покажем все правила для данной валюты
	public function GetRulesByCurrId($id,$from=0,$to_page=ITEMS_PER_PAGE,$has_links=true){
		$txt='';
		$sql='select c.plid, l.lang_flag from currency_use_lang as c, langs as l where c.value_id="'.$id.'" and c.lang_id=l.id;';
		$item=new mysqlSet($sql);
		$rs=$item->getResult();
		$rc=$item->getResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			if($has_links) $txt.="<img src=\"/$f[1]\" alt=\"\" border=\"0\"> <a href=\"viewcurrs.php?action=2&id=$f[0]&from=$from&to_page=$to_page\" onclick=\"return window.confirm('ВНИМАНИЕ!!! Вы действительно хотите удалить данную запись из списка?');\"><img src=\"/img/delete.jpg\" alt=\"Удалить правило\" width=\"16\" height=\"16\" border=\"0\"></a><br>";
			else $txt.="<img src=\"/$f[1]\" alt=\"\" border=\"0\"> ";
		}
		return $txt;
	}
	
	
	//получим привязку валюты (код валюты) к данному языку
	public function GetCurrIdByLangId($lang_id){
		$sql='select * from currency_use_lang where lang_id='.$lang_id.' limit 1;';
		$item=new mysqlSet($sql);
		$rs=$item->getResult();
		$rc=$item->getResultNumRows();
		if($rc>0){
			$f=mysqli_fetch_array($rs);
			return $f['value_id'];
		}else return false;
	}
	
	
	//показать языки в тегах оптион, кот НЕ исп-ся 
	public function DrawLangsOptNot(){
		$txt='';
		$sql='select * from langs where id not in(select lang_id from currency_use_lang);';
		$item=new mysqlSet($sql);
		$rs=$item->getResult();
		$rc=$item->getResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$txt.='<option value="'.$f['id'].'">'.stripslashes($f['lang_name']).'</option>';
		}
		return $txt;
	}
	
	
	//удалить
	public function Del($id){
		//удалять ВСЕ!!!!
		$query = 'delete from '.$this->tablename.' where plid='.$id.';';
		$it=new nonSet($query);
		unset($it);				
		$this->item=NULL;
	}
	
	
	/*
	//получить по айди и коду видимости
	public function GetItemById($id,$mode=0){
		if($mode==0) $item=new mysqlSet('select * from '.$this->tablename.' where id='.$id.';');
		else $item=new mysqlSet('select * from '.$this->tablename.' where '.$this->vis_name.'=1 and id='.$id.';');
		
//		if($mode==1) $item=new mysqlSet('select * from '.$this->tablename.' where is_russ=1 and id='.$id.';');
	//	if($mode==2) $item=new mysqlSet('select * from '.$this->tablename.' where is_en=1 and id='.$id.';');
		$result=$item->getResult();
		$rc=$item->getResultNumRows();
		unset($item);
		if($rc!=0){
			$res=mysqli_fetch_array($result);
			$this->item= Array();
			foreach($res as $key=>$val){
				$this->item[$key]=$val;
			}
			
			return $this->item;
		} else {
//			echo 'ccc'; die();
			$this->item=NULL;
			return false;
		}
	}
	*/
	
}
?>