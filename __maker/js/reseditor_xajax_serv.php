<?
session_start();
require_once('../../classes/xajax/xajax_core/xajax.inc.php');
require_once('../../classes/resoursefile_aj.php');
require_once('../../classes/resoursefile_aj.php');
require_once('../../classes/discr_authuser.php');
require_once('../../classes/distr_rights_man.php');

require_once('reseditor_xajax_common.php');


function DrawRes($file_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	//$srt = iconv('utf-8', 'windows-1251', $srt); 
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){
 	  $objResponse->assign(''.$file_id.'_resoures','innerHTML','');  
	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 13)) {
	  
		$result=$rf->DrawRes($file_id);
		
		$objResponse->assign(''.$file_id.'_resoures','innerHTML',$result);  
		$objResponse->assign(''.$file_id.'_resoures','style.display',"block");  
		$objResponse->assign($file_id.'_blo','style.display',"none");  
		$objResponse->assign($file_id.'_hid','style.display',"block");  
	  }else{
		 $objResponse->assign(''.$file_id.'_resoures','innerHTML','');  
		 $objResponse->alert(NO_RIGHTS);
	  }
	
	}
	

	return  $objResponse;  
}

//�������� �������� �������
function DelVal($file_id,$res_id,$lang_id){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	//$srt = iconv('utf-8', 'windows-1251', $srt); 
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	//������ ��������
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 13)) {
		$rf->DelResLang($file_id,$res_id,$lang_id);
		
		//������� ������ �������� �������
		$result=$rf->DrawVals($file_id,$res_id);
		$objResponse->assign($file_id.'_'.$res_id.'_vals', 'innerHTML', $result);
	  }else{
	  	  $objResponse->alert(NO_RIGHTS);
	  }
	}
		
	return  $objResponse;  
}


//�������� �������
function DelRes($file_id,$res_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	//������ ��������
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 13)) {
		$rf->DelRes($file_id,$res_id);
		
		//������� ������  ������ov
		$result=$rf->DrawRes($file_id);
		$objResponse->assign(''.$file_id.'_resoures','innerHTML',$result);  
	  }else{
		  $objResponse->alert(NO_RIGHTS);
	  }
	}

	return  $objResponse;  
}

//�������� �����
function DelFile($file_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');

	//������ ����
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 13)) {
		$rf->DelFile($file_id);
		
		//������� 
		$result=$rf->DrawTree(true);
		$objResponse->assign('library_total','innerHTML',$result);  
	  }else{
		  $objResponse->alert(NO_RIGHTS);
	  }
	}
	
	
	return  $objResponse;  
}

//������� �����
function AddFile($file_id,$descr){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$params=Array();
	$params['file_id']=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$params['descr']=SecStr(iconv('utf-8', 'windows-1251', $descr));
	
	//������� ����
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 13)) {
		$rf->AddFile($params);
		
		//������� 
		$result=$rf->DrawTree(true);
		$objResponse->assign('library_total','innerHTML',$result);  
	  }else{
		 $objResponse->alert(NO_RIGHTS); 
	  }
	}
			
	
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return  $objResponse;  
}

//������ �����
function EditFile($old_file_id, $file_id,$descr){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$old_file_id=SecurePath(iconv('utf-8', 'windows-1251', $old_file_id));
	
	$params=Array();
	$params['file_id']=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$params['descr']=SecStr(iconv('utf-8', 'windows-1251', $descr));
	
	//pravim ����
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 13)) {
	
		$rf->EdFile($old_file_id, $params);
		
		//������� 
		$result=$rf->DrawTree(true);
		$objResponse->assign('library_total','innerHTML',$result);  
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return  $objResponse;  
}

//������� �������
function AddRes($file_id, $res_id, $res_descr){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$params=Array();
	$file_id=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$params['res_id']=SecurePath(iconv('utf-8', 'windows-1251', $res_id));
	$params['descr']=SecStr(iconv('utf-8', 'windows-1251',$res_descr));
	
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 13)) {
	
		$rf->AddRes($file_id, $params);
		
		//������� 
		$result=$rf->DrawRes($file_id);
		$objResponse->assign($file_id.'_blo','style.display',"none");  
		$objResponse->assign($file_id.'_hid','style.display',"block");  
		
		$objResponse->assign($file_id.'_resoures','innerHTML',$result); 
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return  $objResponse;  
}

//������ �������
function EdRes($file_id, $res_id, $new_res_id, $res_descr){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$params=Array();
	$file_id=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$res_id=SecurePath(iconv('utf-8', 'windows-1251', $res_id));
	$params['res_id']=SecurePath(iconv('utf-8', 'windows-1251', $new_res_id));
	$params['descr']=SecStr(iconv('utf-8', 'windows-1251',$res_descr));
	
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 13)) {
		$rf->EdRes($file_id, $res_id, $params);
		
		//������� 
		$result=$rf->DrawRes($file_id);
		$objResponse->assign($file_id.'_blo','style.display',"none");  
		$objResponse->assign($file_id.'_hid','style.display',"block");  
		$objResponse->assign($file_id.'_resoures','innerHTML',$result);  
	  }else{
		 $objResponse->alert(NO_RIGHTS); 
	  }
	}
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return  $objResponse;  
}

//������� �� ����
function AddVal($file_id, $res_id, $lang_id, $value){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$params=Array();
	$file_id=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$res_id=SecurePath(iconv('utf-8', 'windows-1251', $res_id));
	$params['lang_id']=abs((int)iconv('utf-8', 'windows-1251', $lang_id));
	$params['value']=SecStr(iconv('utf-8', 'windows-1251',$value));
	
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 13)) {
	
		$rf->AddResLang($file_id,$res_id,$params);
		
		//������� 
		$result=$rf->DrawVals($file_id,$res_id);
		$objResponse->assign($file_id.'_'.$res_id.'_vals', 'innerHTML', $result);
	  }else{
		  $objResponse->alert(NO_RIGHTS);
	  }
	}
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return $objResponse;  
}

//������ �� ����
function EdVal($file_id, $res_id, $lang_id, $value){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$params=Array();
	$file_id=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$res_id=SecurePath(iconv('utf-8', 'windows-1251', $res_id));
	$lang_id=abs((int)iconv('utf-8', 'windows-1251', $lang_id));
	$params['value']=SecStr(iconv('utf-8', 'windows-1251',$value));
	
	$au=new DiscrAuthUser();
	//�������� �����������
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 13)) {
		$rf->EdResLang($file_id,$res_id,$lang_id, $params);
		
		//������� 
		$result=$rf->DrawVals($file_id,$res_id);
		$objResponse->assign($file_id.'_'.$res_id.'_vals', 'innerHTML', $result);
	  }else{
		 $objResponse->alert(NO_RIGHTS); 
	  }
	}
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return $objResponse;  
}

$xajax_read->processRequest();
?>