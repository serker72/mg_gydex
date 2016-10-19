<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');


//���� ��� ����
class PricesItem extends AbstractLangItem{
	//protected $subkeyname;
	//protected $dict_kind_tablename='dict_kind';
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='price';
		$this->lang_tablename='price__lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='value_id';
		$this->lang_id_name='lang_id';
//		$this->subkeyname='mid';
		$this->vis_name='is_shown';
	}
	
	
	
	//�������� 
	public function Add($params, $lang_params){
		$url=$params['formula_name'];
		$url=$this->GenUniqVar($url);
		$params['formula_name']=$url;
		
		$mid=AbstractLangItem::Add($params, $lang_params);
		//echo $mid; die();
		$this->ModCurrFlags($mid, $params);
		return $mid;
	}
	
	
	//�������
	public function Edit($id,$params=NULL, $lang_params=NULL){
		
		if(isset($params['formula_name'])){
			$url=$params['formula_name'];
			$url=$this->GenUniqVar($url,$id);
			$params['formula_name']=$url;
		}
		
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
		
		$query = 'delete from good_price where price_id='.$id.';';
		$it=new nonSet($query);
		
		//�������������� �� ������
		/*$query = 'delete from currency_use_lang where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		
		//������������� �� ����� �������
		$query = 'delete from good_price where curr_id='.$id.';';
		$it=new nonSet($query);*/
		
	}
	
	//��������� �������� �� ������������� ������� ��������
	//��������� �������� �� ������ mid
	public function DelGroup($q_string){
		
		/*
		//�������� �������
		$query = 'delete from prop_value_lang where value_id in (select id from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=2 or attach_code=3 and key_value in ('.$q_string.') ) ));';
		$it=new nonSet($query);
		$query = 'delete from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=2 or attach_code=1 and key_value in ('.$q_string.')  ));';
		$it=new nonSet($query);
		
		
		//������������ �������
		$query = 'delete from dict_attach_d where attach_code=2 or attach_code=1 and key_value in ('.$q_string.');';
		$it=new nonSet($query);
		
		
		//��� ���� 3
		//�������� �������
		$query = 'delete from prop_value_lang where value_id in (select id from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=3 and key_value in (select id from price_item where mid in('.$q_string.')) ) ));';
		$it=new nonSet($query);
		$query = 'delete from prop_value where name_id in (select id from prop_name where dict_id in (select dict_id from dict_attach_d where attach_code=3 and key_value in (select id from price_item where mid in('.$q_string.')  )));';
		$it=new nonSet($query);
		
		
		//������������ �������
		$query = 'delete from dict_attach_d where attach_code=3 and key_value in (select id from price_item where mid in('.$q_string.'));';
		$it=new nonSet($query);
		
		//die();
		*/
	}
	
	
	//����� ������� ���� � ��������� ���, ����� ��������
	protected function ModCurrFlags($notid, $params){
		//��������� � �������� �������� ������ �������� � ������� ������.
		if(isset($params['is_base'])&&($params['is_base']==1)){
			
			//������ � ���� ������ base
			$sql='update '.$this->tablename.' set formula_name="" where id<>"'.$notid.'" and formula_name="base"';
			$set=new nonSet($sql);
			
			//�������� ��� ����� ����
			$sql='update '.$this->tablename.' set is_base="0" where id<>"'.$notid.'"';
			$set=new nonSet($sql);
			
			//���������� � ���� base
			$sql='update '.$this->tablename.' set formula_name="base" where id="'.$notid.'"';
			$set=new nonSet($sql);
		}
		
	}
	
	
	//������� ��� ���������� ����������
	public function GenUniqVar($url,$except=NULL){
		//$url;
		$cou=$this->CheckVar($url,$except);
		
		$cter=1; $over=0;
		while($cou>0){
			$url.=$cter;
			$cou=$this->CheckVar($url,$except);
			if(strlen($url)>80) {
				$url=$over;
				$over++;
				$cter=1;
			}
			$cter++;
		}
		
		return $url;
	}
	
	
	//������� ���������� ��� � ����� ����������
	public function CheckVar($url,$except=NULL){
		$sql='select count(*) from '.$this->tablename.' where formula_name="'.$url.'"';
		if($except!==NULL) $sql.=' and id<>"'.$except.'"';
		
		$set=new MysqlSet($sql);
		$rs=$set->GetResult();
		$f=mysqli_fetch_array($rs);
		
		
		return $f['count(*)'];
	}
	
	
	
	
	//����� ���������
	
}
?>