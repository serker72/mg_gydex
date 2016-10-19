<?
require_once('distr_groups.php');
require_once('distr_groupitem.php');
require_once('distr_rightsgroup.php');
require_once('distr_objectsgroup.php');
require_once('distr_rights_man.php');
require_once('discr_authuser.php');
require_once('smarty/SmartyAdm.class.php');
require_once('smarty/SmartyAj.class.php');


//матрица доступа по группам
class DistrMatrGr{
	protected $_groups;
	protected $_group;
	protected $_rights;
	protected $_objects;
	protected $_rights_manager;
	
	function __construct(){
		$this->_groups= new DistrGroups;
		$this->_group= new DistrGroupItem;
		$this->_rights= new DistrRightsGroup;
		$this->_rights_manager= new DistrRightsManager;
		$this->_objects= new DistrObjectsGroup;
	}
	
	
	public function Draw($template,$is_ajax=false, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
		$txt='';
		
		$au=new DiscrAuthUser();
		//проверим авторизацию
		$global_profile=$au->Auth();
		
		
		if(isset($global_profile['login'])&&isset($global_profile['passw'])&&($this->_rights_manager->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 2))) {
			$groups_arr= $this->_groups->GetItemsArr($gfrom, $per_page, $gnavi, Array('ofrom'=>$ofrom));
		}else $groups_arr=Array();
		
		if(isset($global_profile['login'])&&isset($global_profile['passw'])&&($this->_rights_manager->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 1))) {
			$objects_arr= $this->_objects->GetItemsArr($ofrom, $per_page, $onavi, Array('gfrom'=>$gfrom));
																																								        }else $objects_arr=Array();
		foreach($groups_arr as $k=>$v){
				$new_objects=Array();
				foreach($objects_arr as $kk=>$vv){
					//права
					$vv['active_rights']=Array(); 
					if(isset($global_profile['login'])&&isset($global_profile['passw'])&&($this->_rights_manager->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 3))) $vv['active_rights']=$this->_rights_manager->GetActiveRightsGroup($v['id'], $vv['id']);
					$vv['active_rights_len']=count($vv['active_rights']);
					
					$vv['inactive_rights']=Array(); 
					if(isset($global_profile['login'])&&isset($global_profile['passw'])&&($this->_rights_manager->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 3))) $vv['inactive_rights']=$this->_rights_manager->GetInactiveRightsGroup($v['id'], $vv['id']);
					$vv['inactive_rights_len']=count($vv['inactive_rights']);
					
					$new_objects[$kk]=$vv;
					
				}
				$v['inner_objects']=Array(); $v['inner_objects']=$new_objects;
				$groups_arr[$k]=$v;
		}
		if($is_ajax) $smarty=new SmartyAj; else $smarty=new SmartyAdm;
		$smarty->debugging = DEBUG_INFO;
		
		$smarty->assign('navi_groups',$gnavi);
		$smarty->assign('navi_objects',$onavi);
		$smarty->assign('groups',$groups_arr);
		$smarty->assign('objects',$objects_arr);
		$smarty->assign('gfrom',$gfrom);
		$smarty->assign('ofrom',$ofrom);
		$smarty->assign('per_page',$per_page);
		$smarty->assign('EVENT',COORDFUNC);
		$smarty->assign('COORDFUNC',COORDFUNC);
		$txt=$smarty->fetch($template);
		return $txt;
	}
	
	
	
}
?>