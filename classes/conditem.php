<?
require_once('abstractlangitem.php');
require_once('areaitem.php');
require_once('priceitem.php');

//итем - условие работы цены
class CondItem extends AbstractLangItem{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;

	
	
	//установка всех имен
	protected function init(){
		$this->tablename='cond_price';
		$this->lang_tablename='cond_price_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
	}
	
	
	//добавить 
	public function Add($params, $lang_params){
		//если передаем привязку и нулевой код раздела(товара) для привязки, то привязку аннулируем
		if(isset($params['key_value'])&&($params['key_value']==0)) $params['area_id']=0;
		
		return AbstractLangItem::Add($params, $lang_params);
	}
	
	//править
	public function Edit($id,$params=NULL, $lang_params=NULL){
		//если передаем привязку и нулевой код раздела(товара) для привязки, то привязку аннулируем
		if(isset($params['key_value'])&&($params['key_value']==0)) $params['area_id']=0;
		
		AbstractLangItem::Edit($id,$params, $lang_params);
	}
	
	//удаление
	public function Del($id){
		AbstractLangItem::Del($id);
		$sql='update price set cond_id="0" where cond_id="'.$id.'"';
		$set=new nonSet($sql);
	}
	
	//распишем условие
	public function ShowCond($cond_id){
		$txt='';
		
		$item=$this->GetItemById($cond_id);
		if($item!=false){
			//расписываем условие
			
			//анализ области действия
			if($item['area_id']!=0){
				$ait=new AreaItem();
				$ai=$ait->GetItemById($item['area_id']);
				if($ai!=false){
					
					$txt.='<strong>'.stripslashes($ai['name']).':</strong> ';
					if(($item['area_id']==1)||($item['area_id']==2)){
						$mmi=new MmenuItem();
						$mi=$mmi->GetItemById($item['key_value']);
						
						$txt.='<a href="ed_razd.php?action=1&id='.$item['key_value'].'" target="_blank">'.stripslashes($mi['name']).'</a><br>';
					}else if($item['area_id']==3){
						$mmi=new PriceItem();
						$mi=$mmi->GetItemById($item['key_value']);
						$txt.='<a href="ed_price.php?action=1&id='.$item['key_value'].'" target="_blank">'.stripslashes($mi['name']).'</a><br>';
					}
				}
			}
			
			//показ количественного диапазона
			$txt.="<i>От:</i> <b>$item[ffrom]</b> <i>До:</i> <b>$item[tto]</b> ед-ц товара.";
		}else{
			$txt='<em>действует всегда</em>';
		}
		return $txt;
	}
	
	
	
	
	
	
	//список областей действия условия в тегах оптион
	public function DrawAreas($current_id=0){
		$txt='';
		$sql='select * from area_cond_price order by id';
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($f['id']==$current_id){
				$txt.='<option value="'.$f['id'].'" SELECTED>'.stripslashes($f['name']).'</option>';
			}else{
				$txt.='<option value="'.$f['id'].'">'.stripslashes($f['name']).'</option>';
			}
		}
		
		return $txt;
	}
	
	
}
?>