<?
require_once('abstractlangitem.php');
require_once('areaitem.php');
require_once('priceitem.php');

//���� - ������� ������ ����
class CondItem extends AbstractLangItem{
	protected $lang_tablename;//='menu_lang';
	protected $mid_name;
	protected $lang_id_name;

	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='cond_price';
		$this->lang_tablename='cond_price_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';
		$this->vis_name='is_shown';
	}
	
	
	//�������� 
	public function Add($params, $lang_params){
		//���� �������� �������� � ������� ��� �������(������) ��� ��������, �� �������� ����������
		if(isset($params['key_value'])&&($params['key_value']==0)) $params['area_id']=0;
		
		return AbstractLangItem::Add($params, $lang_params);
	}
	
	//�������
	public function Edit($id,$params=NULL, $lang_params=NULL){
		//���� �������� �������� � ������� ��� �������(������) ��� ��������, �� �������� ����������
		if(isset($params['key_value'])&&($params['key_value']==0)) $params['area_id']=0;
		
		AbstractLangItem::Edit($id,$params, $lang_params);
	}
	
	//��������
	public function Del($id){
		AbstractLangItem::Del($id);
		$sql='update price set cond_id="0" where cond_id="'.$id.'"';
		$set=new nonSet($sql);
	}
	
	//�������� �������
	public function ShowCond($cond_id){
		$txt='';
		
		$item=$this->GetItemById($cond_id);
		if($item!=false){
			//����������� �������
			
			//������ ������� ��������
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
			
			//����� ��������������� ���������
			$txt.="<i>��:</i> <b>$item[ffrom]</b> <i>��:</i> <b>$item[tto]</b> ��-� ������.";
		}else{
			$txt='<em>��������� ������</em>';
		}
		return $txt;
	}
	
	
	
	
	
	
	//������ �������� �������� ������� � ����� ������
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