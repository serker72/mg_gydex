<?
require_once('abstractlangitem.php');

//итем значние свойства в словаре
class PropValItem  extends  AbstractLangItem{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;

	
	
	//установка всех имен
	protected function init(){
		$this->tablename='prop_value';
		$this->lang_tablename='prop_value_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
	}
	
	
	//удаление
	public function Del($id){
		//из опций заказа
		$query = 'delete from order_item_option where value_id='.$id.';';
		$it=new nonSet($query);
		unset($it);		
		
		AbstractLangItem::Del($id);
	}
		
	//групповое удаление по разным mid
	public function DelGroup($q_string){
		
	}
	
	//вывести список языков, на котором определено свойство
	public function ShowValLangsOpt($id,$current_lang_id){
		$txt='';
		$sql='select l.id, l.lang_name from langs as l where l.id in(select distinct pl.lang_id from prop_value as pv inner join prop_value_lang as pl on (pv.id=pl.value_id)  where pv.id='.$id.' and pl.lang_id<>'.$current_lang_id.')';
		
		$set=new mysqlSet($sql);
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			$txt.='	<option value="'.$f[0].'">'.htmlspecialchars(stripslashes($f[1])).'</option>';
		}
		
		return $txt;
	}	
}
?>