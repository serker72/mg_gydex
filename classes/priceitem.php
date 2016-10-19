<?
require_once('abstractlangitem.php');

require_once('pricedisp.php');
require_once('conditem.php');

//���� �����
class PriceItem extends AbstractLangItem{
	protected $subkeyname;
	
	//���� ��� ���-�� ������ - ���������� ���
	public $price_disp;
	
	
	
	//��������� ���� ����
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
	
	
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		
		
		$good=$this->GetItemById($id);
		
		//������� ������������� ������
		$query = 'delete from goods_rekommend where pri_lid="'.$id.'" or sec_lid="'.$id.'"';
		$it=new nonSet($query);
		
		
		//������� ��� ���� ��� � ������
		$query = 'delete from good_price where good_id="'.$id.'"';
		$it=new nonSet($query);
		
		//������� �������� ������� ��� ������� ������:
		//������� �.�. ���������� � �������, �.�. ����������� ��������, �.�. ���������� � ������
		
		//���������� � ������
		
		//�������� �������
		$query = 'delete from prop_value_lang where value_id in (select id from prop_value where item_id="'.$id.'");';
		$it=new nonSet($query);
		
		$query = 'delete from prop_value where item_id="'.$id.'"';
		$it=new nonSet($query);
		
		//������������ �������
		$query = 'delete from dict_attach_d where attach_code="3" and key_value="'.$id.'";';
		$it=new nonSet($query);
		
		
		
		$it1=new AbstractItem(); 
		
		//��������� ����������
		$it1->SetTableName($this->tablename);
		$it1->Del($id);
		unset($it1);
		
		//��������� ��������
		$query = 'delete from '.$this->lang_tablename.' where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		$this->item=NULL;
	}	
	
	
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
	
		//������� ��� �������� ������
		$query = 'delete from goods_rekommend where pri_lid in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		$query = 'delete from goods_rekommend where sec_lid in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		
	
		//������� ��� ���� ��� 
		$query = 'delete from good_price where good_id in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		
		//������� �������� ������� ��� ������� ������:
		//������� �.�. ���������� � �������, �.�. ����������� ��������, �.�. ���������� � ������
		
		//���������� � ������
		
		//�������� �������
		$query = 'delete from prop_value_lang where value_id in (select id from prop_value where item_id in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.')));';
		$it=new nonSet($query);
		
		$query = 'delete from prop_value where item_id in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		
		//������������ �������
		$query = 'delete from dict_attach_d where attach_code="3" and key_value in (select id from'.$this->tablename.' where '.$this->subkeyname.' in('.$q_string.'))';
		$it=new nonSet($query);
		
		AbstractLangItem::DelGroup($q_string);
	}
	
	
	
	//��������������� ���� � ������ ��� ������ URL � ���������� �����
	public function ConstructPath($good_id,$lang_id=LANG_CODE,$separator='/'){
		$txt='';
		
		$pi=new PriceItem();
		$good=$pi->GetItemById($good_id,$lang_id,1);
		if($good!==false){
			//if(HAS_URLS){
			$parent_id=$good['mid'];
			
			$mi=new MmenuItem();
			$mmenuitem=$mi->GetItemById($parent_id,$lang_id,1);
			
			$txt=$mi->ConstructPath($parent_id,$lang_id,1,$separator);
			if($txt!=$this->error404){
				//��� ������, ���������� ����
				//���� ��� ������, �� ���������� ���������� ����
				//*/
				//���� ��� ������, �� ���� ����������, � ���������� ������ �� �����
				if(HAS_URLS){
					$txt=$txt.'goods_'.$good_id.'.html';
				}else $txt='/good.php?id='.$good_id;
			}
		}else return $this->error404;
		
		return $txt;
	}
	
	
	
	
	//����� ���������
	
}
?>