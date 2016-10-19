<?
require_once('propnamesgroup.php');
require_once('propnameitem.php');
require_once('propvalsgroup.php');
require_once('propvalitem.php');
require_once('resoursefile.php');
require_once('smarty/SmartyAdm.class.php');

//��������� ����-������� ��� �������
class DictNVDisp{
	protected $names_list;
	protected $name_item;
	protected $vals_list;
	protected $val_item;
	
	public function __construct(){
		
		$this->init();
	}
	
	//��������� ���� ����
	protected function init(){
		$this->names_list=new PropNamesGroup();
		$this->name_item=new PropNameItem();
		$this->vals_list=new PropValsGroup();
		$this->val_item=new PropValItem();
	}
	
	
//************************************* ������ �� ���������� **********************************	
	//�������� ��������
	public function AddName($params, $lang_params){
		$mid=$this->name_item->Add($params, $lang_params);
		return $mid;
	}
	
	//������� ��������
	public function EditName($id,$params, $lang_params){
		$this->name_item->Edit($id,$params, $lang_params);
	}
	//������� ��������
	public function DelName($id){
		$this->name_item->Del($id);
	}
	//���������� ��������� ��������
	public function ToggleVisibleName($id,$visibility=1){
		$this->name_item->ToggleVisible($id,$visibility);
	}
	
	//�������� ��������� �������� � �������� ������
	public function ToggleVisibleLangName($id,$lang_id,$visibility=1){
		$this->name_item->ToggleVisibleLang($id,$lang_id,$visibility);
	}
	
	
	//������ ������� �� ���� �������
	public function GetNamesById($id, $mode=0,$from=0,$to_page=PROPS_PER_PAGE){
		return $this->names_list->GetItemsById($id, $mode,$from,$to_page);
	}
	
	//��������� �������� ������ �������
	public function SetNamesTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template, $name_multilang='tpl/alldicts/subitem_name.html', $name_vis_check='tpl/dicts_comp/subitem_lang_vis_check.html'){
		$this->names_list->SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template, $name_multilang, $name_vis_check);
	}
	
//*********************************************************************************************


//******************************������ �� ���������� -����������*******************************

//***************************************** ��� ������ � �������, ������ ***************************************
	//�������� �� ���� �������� ������� ��������, �����, ������
	public function CheckPropertyByValId(&$returned_good_id,&$returned_name_id,$val_id,$lang_id=LANG_CODE){
		return $this->name_item->CheckPropertyByValId($returned_good_id,$returned_name_id,$val_id,$lang_id);
	}
	
	//�� ���� ��������, ������, ����� �������� ��� �������� � ��������� ��������� �������� ��������
	public function DrawNameValsByValIdGoodId($val_id,$good_id,$lang_id=LANG_CODE){
		return $this->vals_list->DrawNameValsByValIdGoodId($val_id,$good_id,$lang_id);
	}
	
	//�� ���� ����� ������, ����� �������� ����� � �������� ��������� �������
	public function DrawNamesValsByItemIdLangId($item_id,$lang_id=LANG_CODE){
		return $this->names_list->DrawNamesValsByItemIdLangId($item_id,$lang_id);
	}
//************************************����� ��� ������ � �������, ������ ***************************************	



//**************** ��� ������ ������� � ������� �� ��������� �������(������� ��. � �������) ************************
	//����� ���� ���� � ������ option ������� �� ������� ����� ��� ��������� �������
	public function DrawFilterForms($mid, $lang_id, $presets, $template1, $template2){
		$txt='';
		$rf=new ResFile(ABSPATH.'cnf/resources.txt');
		
		//$smarty=new SmartyAdm;
		//$smarty->debugging=DEBUG_INFO;
		$names=$this->names_list->GetNamesByMid($mid,$lang_id);
		
		$alls=Array();
		foreach($names as $k=>$v){
			//echo $v['name'];
			$vals=Array();
			
			//������� ����� ���������� �������� ��������
			$selected_value_id='0';
			foreach($presets as $pk=>$pv){
				//echo "$pv[name_id] => $pv[val_name]<br>";
				if($pv['name_id']==$v['id']){
					$selected_value_id=$pv['val_name'];
					//echo htmlspecialchars($pv['val_name']);
					break;
				}
			}
			if($selected_value_id=='0') $not_selected=true; 
			else $not_selected=false; 
			//�������� ������ ������� �� �����, �� ���� �������
			$vs=$this->vals_list->GetValsByNameIdMid($v['id'],$mid,$lang_id);
			foreach($vs as $kk=>$vv){
				if(HAS_URLS) $val_id=urlencode($vv['id']); 
				else  $val_id=$vv['id']; 
				//echo htmlspecialchars($selected_value_id).' '.htmlspecialchars(($vv['id'])).'<br>';
				
				if($selected_value_id==SecStr($vv['id'])) $sel_val=true; 
				else $sel_val=false; 
				
				$vals[]=Array(
					'val_id'=>$val_id,
					'val_name'=>$vv['name'],
					'not_selected'=>$sel_val
				);
			}
			
			$alls[]=Array(
				'byname'=>$v['name'],
				'nameid'=>$v['id'],
				'not_selected'=>$not_selected,
				'not_selected_text'=>$rf->GetValue('razds.php','prop_not_selected',$lang_id),
				'vals'=>$vals
			);
		}
		
		//$smarty->assign('props', $alls);
		//$txt=$smarty->fetch($template1);
		return $alls;
	}
//*********** �����  ��� ������ ������� � ������� �� ��������� �������(������� ��. � �������) ************************


	
	//������ ������� -�������� �� ���� �������, �����
	public function GetNamesVals($dict_id, $item_id, $from=0,$to_page=PROPS_PER_PAGE){
		return $this->names_list->GetNamesVals($dict_id, $item_id, $from,$to_page);
	}
	
	
	//�������, ������, �������� ��������
	public function AddValue($params, $lang_params){
		$mid=$this->val_item->Add($params, $lang_params);
		return $mid;
	}
	
	//������� 
	public function EditValue($id,$params, $lang_params){
		$this->val_item->Edit($id,$params, $lang_params);
	}
	//������� 
	public function DelValue($id){
		$this->val_item->Del($id);
	}
//*********************************************************************************************
	
	//������� ������ ������, �� ������� ���������� ��������
	public function ShowValLangsOpt($id,$current_lang_id){
		return $this->val_item->ShowValLangsOpt($id,$current_lang_id);
	}
}
?>