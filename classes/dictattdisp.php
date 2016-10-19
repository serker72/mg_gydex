<?
require_once('alldictitem.php');
require_once('alldictsgroup.php');
require_once('dictattachitem.php');
require_once('attdictsgroup.php');
require_once('attkindsgroup.php');

//��������� ������������ ��������
class DictAttDisp{
	
	//����� ������ 1- ������ 3-�����
	protected $global_mode=1;
	//������ ���������� ������� ������
	protected $global_modes=Array();
		
	protected $dict_attach_item;
	protected $all_dict_item;
	protected $attached_list;
	protected $attached_kind;
	
	public function __construct($mode=1){
		//��������� ���������� ������ ������
		$this->global_modes[]=1;
		$this->global_modes[]=3;
		
		$mode=$this->CheckWorkMode($mode);
		$this->global_mode=$mode;
		$this->init();
	}
	
	//��������� ���� ����
	protected function init(){
		
		$this->dict_attach_item=new DictAttachItem();
		$this->all_dict_item=new AllDictItem();
		$this->attached_list=new AttDictsGroup();
		$this->attached_kind=new AttKindsGroup();
		
		
	}
	
	
	//������� �������� �������
	public function Add($params){
		return $this->dict_attach_item->Add($params);
	}
	
	//�������� �������� �������
	public function Del($id){
		return $this->dict_attach_item->Del($id);
	}
	
	//���������� ������� � �����
	public function AttachDict($dict_id, $id, $kind){
		$this->all_dict_item->AttachDict($dict_id, $id, $kind);
	}
	
	//��������� ������� �� �����
	public function DetachDict($dict_id, $id, $kind){
		$this->all_dict_item->DetachDict($dict_id, $id, $kind);
	}
	
	//��������� ���� ��������
	public function ChangeAttach($dict_id, $id, $active_value){
		$sql='select * from attach_d';
		$set=new mysqlSet($sql);
		$rc=$set->GetResultNumRows();
		$rs=$set->GetResult();
		for($i=0;$i<$rc;$i++){
			$f=mysqli_fetch_array($rs);
			
			if($active_value==$f['id']){
				//������ ��������
				$this->AttachDict($dict_id, $id, $f['id']);
			}else{
				//������ ��������
				$this->DetachDict($dict_id, $id, $f['id']);				
			}
		}
	}
	
	
	//��������� ������� ������ �������� �������
	public function ChangeOrd($id,$dir){
		$da=$this->dict_attach_item->GetItemById($id);
		if($da!=false){
			$params=Array();
			if($dir==0){
				if((int)$da['ord']<245)	$params['ord']=(int)$da['ord']+10;
				else $params['ord']=(int)$da['ord'];
			}
			if($dir==1){
				if((int)$da['ord']>10) $params['ord']=(int)$da['ord']-10;
				else $params['ord']=(int)$da['ord'];
			}
			$this->dict_attach_item->Edit($id,$params);
		}
	}
	
	//����� ������ ������ ��������
	public function GetItemsForWindow($id,$kind,$from,$to_page){
		return $this->attached_list->GetItemsForWindow($id,$kind,$from,$to_page);
	}
	
	
	
	//����� ����������� �������� ����������
	public function ViewAttached($id,$kind){
		//��� ���������
		if($this->global_mode==1){
			return $this->attached_list->GetAttachedToRazd($id,$kind);
		}
		if($this->global_mode==3){
			return $this->attached_list->GetAttachedToGood($id,$kind);
		}
	}
	
	
	//����� �������� � ����x option, ����� �������������
	public function GetItemsOptExcept($id,$kind){
		//��� ��������� & �������
		return $this->attached_list->GetItemsOptExceptToRazd($id,$kind);
	}
	
	//����� ����� ����������� � ����� �����
	public function GetAttachKinds($current_id,$no, $add_code='',$show_no=true){
		return $this->attached_kind->GetItemsRadio($this->global_mode,$current_id, $no, $add_code,$show_no);
	}
	
	//����� ����� ����������� � ����� ������
	public function GetAttachKindsOpt($current_id,$show_no=true){
		return $this->attached_kind->GetItemsOpt($this->global_mode,$current_id,$show_no);
	}
	
	
	//��������� �������� ������ ������
	public function SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template, $name_multilang='tpl/alldicts/subitem_name.html', $name_vis_check='tpl/dicts_comp/subitem_lang_vis_check.html'){
		$this->attached_list->SetTemplates($all_menu_template, $menuitem_template, $menuitem_template_blocked, $razbivka_template, $name_multilang, $name_vis_check);
	}
	
	
	//��������� �������� ����� �� ������������
	public function CheckWorkMode($mode){
		$has_it=false;
		foreach($this->global_modes as $k=>$v){
			//echo "$v<br>";
			if($mode==$v){
				$has_it=true;
				break;
			}
		}
		if($has_it) return $mode;
		else return 1;
	}
	
	//������� �������� ������ ������
	public function GetWorkMode(){
		return $this->global_mode;
	}
	
	
//********************************************* ���������� ����� *******************************************************
	//����� �������� �������
	public function DrawDictsCli($kind_id, $good_id, $lang_id=LANG_CODE, $template1='', $template2='',$template3='',$chosen_array=NULL,$option_name_prefix='',$flag_to_change=''){
		if($kind_id==1){
			//����� ������ ������� ��� ������
			return $this->attached_list->GetPropTablesByGoodId($good_id, $lang_id, $template1, $template2, $template3);
		}else if($kind_id==2){
			//����� ����������� ��� ������� ������� ����� ������
			return $this->attached_list->GetOrderOptsByGoodId($good_id, $lang_id, $template1, $template2, $chosen_array,$option_name_prefix,$flag_to_change);
		}else if($kind_id==3){
			//����� ���� �������, ��� � ������
			return  $this->attached_list->GetAddPhotosByGoodId($good_id, $lang_id, $template1, $template2,$template3) ;
		}
	}
	
	
	
}
?>