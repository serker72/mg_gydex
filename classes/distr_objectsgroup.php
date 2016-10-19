<?
require_once('abstractgroup.php');



//итем список объектов
class DistrObjectsGroup extends AbstractGroup{
	
	//установка всех имен
	protected function init(){
		$this->tablename='discr_objects';
	
		$this->item=NULL;
		$this->pagename='discr_matrix_group.php';		
		//$this->vis_name='is_blocked';	
		
	}
	
	
	//список итемов
	public function GetItemsArr($ofrom=0, $per_page=ITEMS_PER_PAGE, &$navi='', $extra_params=Array()){
		//список позиций
		$alls=Array();
		
		$sql='select * from '.$this->tablename.' order by id';
		$query_count= 'select count(*) from '.$this->tablename;
		$set=new MysqlSet($sql, $per_page,$ofrom,$query_count);
		
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		$totalcount=$set->getResultNumRowsUnf();
		
		$extra_str='';
		foreach($extra_params as $k=>$v){
			$extra_str.='&'.$k.'='.$v;	
		}
		$navig = new PageNavigator($this->pagename,$totalcount,$per_page,$ofrom,10,$extra_str);
		$navig->setFirstParamName('ofrom');
		$navig->setDivWrapperName('alblinks_flat');
		$navig->setPageDisplayDivName('alblinks1_inv');			
		$navi= $navig->GetNavigator();
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);
			$f['name_esc']=(addslashes($f['name']));
			$f['info_esc']=str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($f['info']))));
			
			$alls[]=$f;
		}
		
		
		return $alls;
	}
	
	
}
?>