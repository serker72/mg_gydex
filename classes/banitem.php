<?
require_once('abstractlangitem.php');
require_once('mmenuitem.php');


//���� ������
class BanItem extends AbstractLangItem{
	protected $subkeyname;
//	protected $dict_kind_tablename='dict_kind';
	
	
	//��������� ���� ����
	protected function init(){
		$this->tablename='banners';
		$this->lang_tablename='banners_lang';
		$this->item=NULL;
		$this->pagename='page.php';		
		
		$this->mid_name='ban_id';
		$this->lang_id_name='lang_id';
		$this->subkeyname='mid';
		$this->vis_name='is_shown';
	}
	
	
	
	
	//�������
	public function Del($id){
		//������� ���!!!!
		
	//	$this->DelChilds($id);
		
		AbstractLangItem::Del($id);
	}	
	
	
	public function DelChilds($id){
	
		/*$query = 'update price_item set '.$this->mid_name.'=0 where '.$this->mid_name.'='.$id.';';
		$it=new nonSet($query);
		*/
	}
	
}
?>