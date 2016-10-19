<?
require_once('abstractgroup.php');
require_once('PageNavigatorBr.php');


//итем список групп пол-лей
class DistrGroups extends AbstractGroup{
	
	//установка всех имен
	protected function init(){
		$this->tablename='discr_groups';
	
		$this->item=NULL;
		$this->pagename='discr_matrix_group.php';		
		$this->vis_name='is_blocked';	
		
	}
	
	
	//список итемов
	public function GetItemsArr($gfrom=0, $per_page=ITEMS_PER_PAGE, &$navi='', $extra_params=Array()){
		//список позиций
		$alls=Array();
		
		$sql='select * from '.$this->tablename.' order by id';
		$query_count= 'select count(*) from '.$this->tablename;
		$set=new MysqlSet($sql, $per_page,$gfrom,$query_count);
		//echo $sql;
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		$totalcount=$set->getResultNumRowsUnf();
		
		$extra_str='';
		foreach($extra_params as $k=>$v){
			$extra_str.='&'.$k.'='.$v;	
		}
		$navig = new PageNavigatorBr($this->pagename,$totalcount,$per_page,$gfrom,10,$extra_str);
		$navig->setFirstParamName('gfrom');
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
	
	
	//список групп пол-ля
	public function GetGroupsByUserId($user_id, $template, $is_ajax=false, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
		$txt='';
		if($is_ajax) $smarty=new SmartyAj;
		else $smarty=new SmartyAdm;
		$in_arr=$this->GetGroupsByUserIdArr($user_id, false);
		$not_in_arr=$this->GetGroupsByUserIdArr($user_id, true);
		
		$smarty->assign('users_array', $in_arr);
		$smarty->assign('not_users_array', $not_in_arr);
		$smarty->assign('gfrom',$gfrom);
		$smarty->assign('ofrom',$ofrom);
		$smarty->assign('per_page',$per_page);
		
		$smarty->assign('users_array_len', count($in_arr));
		$smarty->assign('not_users_array_len', count($not_in_arr));
		$smarty->assign('group_id', $user_id);
		
		
		$txt=$smarty->fetch($template);
		return $txt;
	}
	
	
	//список групп пол-ля massiv
	public function GetGroupsByUserIdArr($user_id, $reverse=false){
		//список позиций
		$alls=Array();
		
		$r_a='';
		if($reverse) $r_a=' not ';
		$sql='select * from '.$this->tablename.' where id '.$r_a.' in(select distinct group_id from discr_users_by_groups where user_id='.$user_id.') order by name, id';
		
		$set=new MysqlSet($sql);
		//echo $sql;
		$rs=$set->GetResult();
		$rc=$set->GetResultNumRows();
		
		
		
		for($i=0; $i<$rc; $i++){
			$f=mysqli_fetch_array($rs);

			foreach($f as $kk=>$vv){
				$f[$kk.'_esc']=	str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($vv))));
			}
			$alls[]=$f;
		}
		
		
		return $alls;
	}
	
	//генерация джаваскрипта для выделения групп
	public function GenerateJSMarked($id, $template, $is_ajax=true){
		$txt='';
		
		$arr1= $this->GetGroupsByUserIdArr($id);
		$arr2= $this->GetGroupsByUserIdArr($id, true);
		
		if($is_ajax) $smarty=new SmartyAj;
		else $smarty=new SmartyAdm;
		
		$smarty->assign('users_array', $arr1);
		$smarty->assign('not_users_array', $arr2);
		$smarty->assign('group_id', $id);
		
		
		$txt=$smarty->fetch($template);
		return $txt;	
	}
	
}
?>