<?
require_once('abstractgroup.php');
require_once('PageNavigatorBr.php');


//список пол-лей
class DistrUsersGroup extends AbstractGroup{
	
	//установка всех имен
	protected function init(){
		$this->tablename='discr_users';
	
		$this->item=NULL;
		$this->pagename='discr_matrix_users.php';		
		$this->vis_name='is_blocked';	
		
	}
	
	
	//список итемов
	public function GetItemsArr($gfrom=0, $per_page=ITEMS_PER_PAGE, &$navi='', $extra_params=Array()){
		//список позиций
		$alls=Array();
		
		$sql='select * from '.$this->tablename.' order by login, id';
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
			
			foreach($f as $kk=>$vv){
				$f[$kk.'_esc']=	str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($vv))));
			}
			$alls[]=$f;
		}
		
		
		return $alls;
	}
	
	//список юзеров в группе
	public function GetUsersByGrId($group_id, $template, $is_ajax=false){
		$txt='';
		if($is_ajax) $smarty=new SmartyAj;
		else $smarty=new SmartyAdm;
		$in_arr=$this->GetUsersByGrIdArr($group_id, false);
		$not_in_arr=$this->GetUsersByGrIdArr($group_id, true);
		
		$smarty->assign('users_array', $in_arr);
		$smarty->assign('not_users_array', $not_in_arr);
		
		$smarty->assign('users_array_len', count($in_arr));
		$smarty->assign('not_users_array_len', count($not_in_arr));
		$smarty->assign('group_id', $group_id);
		
		
		$txt=$smarty->fetch($template);
		return $txt;
	}
	
	
	//список юзеров в группе massiv
	public function GetUsersByGrIdArr($group_id, $reverse=false){
		//список позиций
		$alls=Array();
		
		$r_a='';
		if($reverse) $r_a=' not ';
		$sql='select * from '.$this->tablename.' where id '.$r_a.' in(select distinct user_id from discr_users_by_groups where group_id='.$group_id.') order by login, id';
		
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
	
	//генерация джаваскрипта для выделения юзеров
	public function GenerateJSMarked($id, $template, $is_ajax=true){
		$txt='';
		
		$arr1= $this->GetUsersByGrIdArr($id);
		$arr2= $this->GetUsersByGrIdArr($id, true);
		
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