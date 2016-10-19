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
	//проверим авторизацию
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

//удаление значения ресурса
function DelVal($file_id,$res_id,$lang_id){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	//$srt = iconv('utf-8', 'windows-1251', $srt); 
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	//удалим значение
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 13)) {
		$rf->DelResLang($file_id,$res_id,$lang_id);
		
		//обновим список значений ресурса
		$result=$rf->DrawVals($file_id,$res_id);
		$objResponse->assign($file_id.'_'.$res_id.'_vals', 'innerHTML', $result);
	  }else{
	  	  $objResponse->alert(NO_RIGHTS);
	  }
	}
		
	return  $objResponse;  
}


//удаление ресурса
function DelRes($file_id,$res_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	//удалим значение
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 13)) {
		$rf->DelRes($file_id,$res_id);
		
		//обновим список  ресурсov
		$result=$rf->DrawRes($file_id);
		$objResponse->assign(''.$file_id.'_resoures','innerHTML',$result);  
	  }else{
		  $objResponse->alert(NO_RIGHTS);
	  }
	}

	return  $objResponse;  
}

//удаление файла
function DelFile($file_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');

	//удалим файл
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 13)) {
		$rf->DelFile($file_id);
		
		//обновим 
		$result=$rf->DrawTree(true);
		$objResponse->assign('library_total','innerHTML',$result);  
	  }else{
		  $objResponse->alert(NO_RIGHTS);
	  }
	}
	
	
	return  $objResponse;  
}

//добавка файла
function AddFile($file_id,$descr){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$params=Array();
	$params['file_id']=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$params['descr']=SecStr(iconv('utf-8', 'windows-1251', $descr));
	
	//добавим файл
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 13)) {
		$rf->AddFile($params);
		
		//обновим 
		$result=$rf->DrawTree(true);
		$objResponse->assign('library_total','innerHTML',$result);  
	  }else{
		 $objResponse->alert(NO_RIGHTS); 
	  }
	}
			
	
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return  $objResponse;  
}

//правка файла
function EditFile($old_file_id, $file_id,$descr){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$old_file_id=SecurePath(iconv('utf-8', 'windows-1251', $old_file_id));
	
	$params=Array();
	$params['file_id']=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$params['descr']=SecStr(iconv('utf-8', 'windows-1251', $descr));
	
	//pravim файл
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 13)) {
	
		$rf->EdFile($old_file_id, $params);
		
		//обновим 
		$result=$rf->DrawTree(true);
		$objResponse->assign('library_total','innerHTML',$result);  
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return  $objResponse;  
}

//добавка ресурса
function AddRes($file_id, $res_id, $res_descr){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	$params=Array();
	$file_id=SecurePath(iconv('utf-8', 'windows-1251', $file_id));
	$params['res_id']=SecurePath(iconv('utf-8', 'windows-1251', $res_id));
	$params['descr']=SecStr(iconv('utf-8', 'windows-1251',$res_descr));
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 13)) {
	
		$rf->AddRes($file_id, $params);
		
		//обновим 
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

//правка ресурса
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
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 13)) {
		$rf->EdRes($file_id, $res_id, $params);
		
		//обновим 
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

//добавка яз знач
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
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 13)) {
	
		$rf->AddResLang($file_id,$res_id,$params);
		
		//обновим 
		$result=$rf->DrawVals($file_id,$res_id);
		$objResponse->assign($file_id.'_'.$res_id.'_vals', 'innerHTML', $result);
	  }else{
		  $objResponse->alert(NO_RIGHTS);
	  }
	}
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return $objResponse;  
}

//правка яз знач
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
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 13)) {
		$rf->EdResLang($file_id,$res_id,$lang_id, $params);
		
		//обновим 
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