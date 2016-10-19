<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');


//���� �����
class CurrencyItem extends AbstractLangItem{
	//protected $subkeyname;
	//protected $dict_kind_tablename='dict_kind';
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='currency';
		$this->lang_tablename='currency_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';
//		$this->subkeyname='mid';
		$this->vis_name='is_shown';
	}
	
	
	
	//�������� 
	public function Add($params, $lang_params){
		$mid=AbstractLangItem::Add($params, $lang_params);
		//echo $mid; die();
		$this->ModCurrFlags($mid, $params);
		return $mid;
	}
	
	
	//�������
	public function Edit($id,$params=NULL, $lang_params=NULL){
		
		
		
		AbstractLangItem::Edit($id,$params, $lang_params);
		$this->ModCurrFlags($id, $params);
		
		
	}
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		
		$this->DelChilds($id);
		
		
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
	
	
	//�������� ���� ����������� ������ ������ ������
	public function DelChilds($id){
	
		//�������������� �� ������
		$query = 'delete from currency_use_lang where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		//������������� �� ����� �������
		$query = 'delete from good_price where curr_id='.$id.';';
		$it=new nonSet($query);
		
	}
	
	//��������� �������� �� ������������� ������� ��������
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
		
	}
	
	
	//����� ������ �������� � ������� ������ � ����, ����� ������ ������
	protected function ModCurrFlags($notid, $params){
		//��������� � �������� �������� ������ �������� � ������� ������.
		if(isset($params['is_base_shop'])&&($params['is_base_shop']==1)){
			//�������� ��� ����� ����
			$sql='update '.$this->tablename.' set is_base_shop="0" where id<>"'.$notid.'"';
			$set=new nonSet($sql);
		}
		if(isset($params['is_base_rate'])&&($params['is_base_rate']==1)){
			//�������� ��� ����� ����
			$sql='update '.$this->tablename.' set is_base_rate="0" where id<>"'.$notid.'"';
			$set=new nonSet($sql);
			
			$sql='update '.$this->tablename.' set rate="1" where id="'.$notid.'"';
			$set=new nonSet($sql);
		}
	}
	
	//����� ���������
	
}
?>