<?
session_start();
require_once('../../classes/xajax/xajax_core/xajax.inc.php');
require_once('../../classes/distr_matrgr.php');
require_once('../../classes/distr_matrus.php');
require_once('../../classes/distr_groupitem.php');
require_once('../../classes/distr_objitem.php');
require_once('../../classes/distr_useritem.php');
require_once('../../classes/distr_rights_man.php');
require_once('dmg_xajax_common.php');

function RedrawGrMatrix($gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	$txt='';
	$dmg=new DistrMatrGr;
	$txt=$dmg->Draw('discr/overal.html',true, $gfrom, $ofrom, $per_page);
	return $txt;
}
function RedrawUsMatrix($gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	$txt='';
	$dmg=new DistrMatrUs;
	$txt=$dmg->Draw('discr/us_overal.html',true, $gfrom, $ofrom, $per_page);
	return $txt;
}


function RedrawObj($id){
	$txt='';
	$smarty=new SmartyAj;
	
	$gi=new DistrObjItem;
	$gii=$gi->GetItemById($id);
	if($gii!=false){
		$gii['name_esc']=(addslashes($gii['name']));
		$gii['info_esc']=str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($gii['info']))));
		$smarty->assign('selected_item', $gii);
		$smarty->assign('EVENT', COORDFUNC);
		$txt=$smarty->fetch('discr/object_title_cell.html');
	}
	return $txt;
}

function RedrawGroup($id){
	$txt='';
	$smarty=new SmartyAj;
	
	$gi=new DistrGroupItem;
	$gii=$gi->GetItemById($id);
	if($gii!=false){
		$gii['name_esc']=(addslashes($gii['name']));
		$gii['info_esc']=str_replace("\r",'\r', str_replace("\n",'\n', str_replace("\r\n",'\r\n',  addslashes($gii['info']))));
		$smarty->assign('selected_item', $gii);
		$smarty->assign('EVENT', COORDFUNC);
		$txt=$smarty->fetch('discr/group_title_cell.html');
	}
	return $txt;
}

function RedrawRightsCell($object_id, $group_id){
	$txt='';
	$smarty=new SmartyAj;
	
	$gi=new DistrRightsManager;
	
	$active_rights=Array(); $active_rights=$gi->GetActiveRightsGroup($group_id, $object_id);
	$active_rights_len=count($active_rights);
	
	$inactive_rights=Array(); $inactive_rights=$gi->GetInactiveRightsGroup($group_id, $object_id);
	$inactive_rights_len=count($inactive_rights);
	
	$smarty->assign('group_id',$group_id);
	$smarty->assign('selected_object', Array('id'=>$object_id, 'active_rights'=>$active_rights, 'active_rights_len'=>$active_rights_len, 'inactive_rights'=>$inactive_rights, 'inactive_rights_len'=>$inactive_rights_len));
	$smarty->assign('EVENT', COORDFUNC);
	$txt=$smarty->fetch('discr/group_object_rights_cell.html');
	
	return $txt;
}



function GrantOnObjectToGroup($right_id, $object_id, $group_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$right_id=abs((int) $right_id);
	$object_id=abs((int) $object_id);
	$group_id=abs((int) $group_id);
	
	$gi=new DistrRightsManager;
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 3)) {
	
	  $gi->GrantOnObjectToGroup($right_id, $object_id, $group_id);
	
	  //перерисовка ячейки прав
      $objResponse->assign('group_'.$group_id.'_object_'.$object_id.'_rights_cell','innerHTML',RedrawRightsCell($object_id, $group_id));
	  }else{
		   $objResponse->alert(NO_RIGHTS);
	  }
	}
	
	
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}

function RevokeFromObjectToGroup($right_id, $object_id, $group_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$right_id=abs((int) $right_id);
	$object_id=abs((int) $object_id);
	$group_id=abs((int) $group_id);
	
	$gi=new DistrRightsManager;
	
	//echo "$right_id, $object_id, $group_id "; die();
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 3)) {
		$gi->RevokeFromObjectToGroup($right_id, $object_id, $group_id);
		
		//перерисовка ячейки прав
		$objResponse->assign('group_'.$group_id.'_object_'.$object_id.'_rights_cell','innerHTML',RedrawRightsCell($object_id, $group_id));
	  }else{
		   $objResponse->alert(NO_RIGHTS);
	  }
	}
	
	
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}


function AddGroup($name, $info, $is_active, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	$params=Array();
	$params['name']=SecStr(iconv('utf-8','windows-1251', $name), 10);
	$params['info']=SecStr(iconv('utf-8','windows-1251', $info), 10);
	if($is_active) $params['is_blocked']=0; else  $params['is_blocked']=1;
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 2)) {
		$gi=new DistrGroupItem;
		$gi->Add($params);
		
		$objResponse->assign('tableplace','innerHTML',RedrawGrMatrix($gfrom, $ofrom, $per_page));  
	  }else{
	  	 $objResponse->alert(NO_RIGHTS);
	  }
	}
	
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}

function EditGroup($id, $name, $info, $is_active){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	$params=Array();
	$id=abs((int) $id);
	$params['name']=SecStr(iconv('utf-8','windows-1251', $name), 10);
	$params['info']=SecStr(iconv('utf-8','windows-1251', $info), 10);
	if($is_active) $params['is_blocked']=0; else  $params['is_blocked']=1;
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 2)) {
		$gi=new DistrGroupItem;
		$gi->Edit($id, $params);
		
		$gii=$gi->GetItemById($id);
		
		$objResponse->assign('group_title_cell_'.$id,'innerHTML',RedrawGroup($id));  
	  }else{
		  $objResponse->alert(NO_RIGHTS); 
	  }
	}
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}


function DelGroup($id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$id=abs((int) $id);
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 2)) {
	
		$gi=new DistrGroupItem;
		$gi->Del($id);
		$objResponse->assign('tableplace','innerHTML',RedrawGrMatrix());
	  }else{
		$objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}


function AddObj($name, $info, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	$params=Array();
	$params['name']=SecStr(iconv('utf-8','windows-1251', $name), 10);
	$params['info']=SecStr(iconv('utf-8','windows-1251', $info), 10);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 1)) {
		$gi=new DistrObjItem;
		$gi->Add($params);
		$objResponse->assign('tableplace','innerHTML',RedrawGrMatrix($gfrom, $ofrom, $per_page));
	  }else{
	  	 $objResponse->alert(NO_RIGHTS);
	  }
    }
	
	
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}


function EdObj($id, $name, $info){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	$params=Array();
	$id=abs((int) $id);
	$params['name']=SecStr(iconv('utf-8','windows-1251', $name), 10);
	$params['info']=SecStr(iconv('utf-8','windows-1251', $info), 10);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 1)) {
		$gi=new DistrObjItem;
		$gi->Edit($id, $params);
	
		$objResponse->assign('object_title_cell_'.$id,'innerHTML',RedrawObj($id));  
	  }else{
		 $objResponse->alert(NO_RIGHTS); 
	  }
	}
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}

function DelObj($id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$id=abs((int) $id);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 1)) {
		$gi=new DistrObjItem;
		$gi->Del($id);
		$objResponse->assign('tableplace','innerHTML',RedrawGrMatrix());
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}

///////////////////////////////////////////////////////////////////////////////////////////
//работа с пользователями

//добавка пользователя
function AddUser($login, $passw, $c_passw, $name, $address, $email, $phone, $is_active, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$params=Array();
	$params['login']=SecStr(iconv('utf-8','windows-1251', $login), 10);
	$params['passw']=SecStr(iconv('utf-8','windows-1251', $passw), 10);
	$c_passw=SecStr(iconv('utf-8','windows-1251', $c_passw), 10);
	$params['username']=SecStr(iconv('utf-8','windows-1251', $name), 10);
	$params['address']=SecStr(iconv('utf-8','windows-1251', $address), 10);
	$params['email']=SecStr(iconv('utf-8','windows-1251', $email), 10);
	$params['phone']=SecStr(iconv('utf-8','windows-1251', $phone), 10);
	
	$is_active=abs((int)$is_active);
	$params['is_blocked']=(int)($is_active==0);
	
	//foreach($params as $k=>$v) echo "$k = $v\n";
	
	
	$do_it=true;
	//повторить все проверки
	if(strlen($params['login'])==0) {
		$objResponse->assign('err_login','innerHTML','Заполните Логин!');  
		$do_it&&false;	
	}else $objResponse->assign('err_login','innerHTML','');  
	
	if(strlen($params['passw'])==0) {
		$objResponse->assign('err_passw','innerHTML','Заполните Пароль!');  
		$do_it&&false;	
	}else $objResponse->assign('err_passw','innerHTML','');  
	
	if(strlen($c_passw)==0) {
		$objResponse->assign('err_passw','innerHTML','Заполните Подтверждение Пароля!');  
		$do_it&&false;	
	}else $objResponse->assign('err_passw','innerHTML','');  
	
	
	if($c_passw!==$params['passw']){
		$objResponse->assign('err_passw','innerHTML','Пароль и Подтверждение не совпадают!');  
		$do_it&&false;	
	}else {
		$objResponse->assign('err_passw','innerHTML',''); 
		$params['passw']=md5($params['passw']);
	}
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 5)) {
	
		if($do_it){
		  $gi=new DistrUserItem;
		  
		  //проверить, свободен ли такой логин
		  if($gi->CheckLogin($params['login'])>0){
			  //выведем сообщение, что такой логин есть
			  $objResponse->assign('error_window_text','innerHTML','Такой логин уже занят!');  
			  $objResponse->assign('error_window','style.top','50%');  
			  $objResponse->assign('error_window','style.left','50%');  
			  $objResponse->assign('error_window','style.display','block');  
	  
		  }else{
					  //если все в порядке - добавить пользователя						   
			  $gi->Add($params);	
			  $objResponse->assign('user_edit','style.display','none'); 
			  $objResponse->assign('tableplace','innerHTML',RedrawUsMatrix($gfrom, $ofrom, $per_page));  
			  
		  }
		}
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}

//правка пользователя
function EditUser($id, $login, $pass_style, $passw, $c_passw, $name, $address, $email, $phone, $is_active, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$params=Array();
	$id=abs((int)$id);
	$params['login']=SecStr(iconv('utf-8','windows-1251', $login), 10);
	if($pass_style=='block'){
		$params['passw']=SecStr(iconv('utf-8','windows-1251', $passw), 10);
		$c_passw=SecStr(iconv('utf-8','windows-1251', $c_passw), 10);
	}
	
	$params['username']=SecStr(iconv('utf-8','windows-1251', $name), 10);
	$params['address']=SecStr(iconv('utf-8','windows-1251', $address), 10);
	$params['email']=SecStr(iconv('utf-8','windows-1251', $email), 10);
	$params['phone']=SecStr(iconv('utf-8','windows-1251', $phone), 10);
	
	$is_active=abs((int)$is_active);
	$params['is_blocked']=(int)($is_active==0);
	
	$do_it=true;
	//повторить все проверки
	if(strlen($params['login'])==0) {
		$objResponse->assign('err_login','innerHTML','Заполните Логин!');  
		$do_it&&false;	
	}else $objResponse->assign('err_login','innerHTML','');  
	
	if($pass_style=='block'){
	  if(strlen($params['passw'])==0) {
		  $objResponse->assign('err_passw','innerHTML','Заполните Пароль!');  
		  $do_it&&false;	
	  }else $objResponse->assign('err_passw','innerHTML','');  
	  
	  if(strlen($c_passw)==0) {
		  $objResponse->assign('err_passw','innerHTML','Заполните Подтверждение Пароля!');  
		  $do_it&&false;	
	  }else $objResponse->assign('err_passw','innerHTML','');  
	  
	  
	  if($c_passw!==$params['passw']){
		  $objResponse->assign('err_passw','innerHTML','Пароль и Подтверждение не совпадают!');  
		  $do_it&&false;	
	  }else {
		  $objResponse->assign('err_passw','innerHTML',''); 
		  $params['passw']=md5($params['passw']);
	  }
	}
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 5)) {
		if($do_it){
		  $gi=new DistrUserItem;
		  
		  //проверить, свободен ли такой логин
		  if($gi->CheckLogin($params['login'], $id)>0){
			  //выведем сообщение, что такой логин есть
			  $objResponse->assign('error_window_text','innerHTML','Такой логин уже занят!');  
			  $objResponse->assign('error_window','style.top','50%');  
			  $objResponse->assign('error_window','style.left','50%');  
			  $objResponse->assign('error_window','style.display','block');  
	  
		  }else{
					  //если все в порядке - добавить пользователя						   
			  $gi->Edit($id, $params);	
			  $objResponse->assign('user_edit','style.display','none'); 
			  $objResponse->assign('tableplace','innerHTML',RedrawUsMatrix($gfrom, $ofrom, $per_page));  
			  
		  }
		}
	  }else{
		$objResponse->alert(NO_RIGHTS);  
	  }
	}
		
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}


//удаление пользователя
function DelUser($id){
	$objResponse  =  new xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$id=abs((int)$id);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 5)) {
		$gi=new DistrUserItem;
		$gi->Del($id);	
		
		$objResponse->assign('tableplace','innerHTML',RedrawUsMatrix());  
	  }else{
		 $objResponse->alert(NO_RIGHTS); 
	  }
	}
	
	$objResponse->assign('wait_win','style.display',"none");  
		
	return  $objResponse;  
}

//юзеры в группе
function ShowUsers($id){
	$objResponse  =  new xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$id=abs((int)$id);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){
		//group_'.$id.'_users
	  $objResponse->assign('group_users_edit_inner','innerHTML','');
	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 6)) {
	
		$gi=new DistrUsersGroup;
		$str=$gi->GetUsersByGrId($id, 'discr/group_users.html', true);
		$objResponse->assign('group_users_edit_inner','innerHTML',$str);
		$func=$gi->GenerateJSMarked($id, 'discr/users_mark_js.js', true);
		
		//генерим функцию для разметки массива выделенных пользователей
		$objResponse->setFunction("BuildArrs".$id, 'param', $func);
		$objResponse->call("BuildArrs".$id, '');
	  }else{
		  $objResponse->assign('group_users_edit_inner','innerHTML','');
		 $objResponse->alert(NO_RIGHTS); 
	  }
	}
	
		
	return  $objResponse;  
}

//добавить юзеров в группу
function AddUsersToGroup($group_id, $users){
	$objResponse  =  new xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$group_id=abs((int)$group_id);
	$users_arr=explode(',',$users);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 6)) {
		  
		$gi=new DistrUserItem;
		foreach($users_arr as $k=>$v){
			$v=abs((int)$v);
			$gi->AddUserToGroup($group_id, $v);
		}
		
		$objResponse->loadCommands(ShowUsers($group_id));
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	return  $objResponse;  
}
	
//исключить юзеров из группы
function DelUsersFromGroup($group_id, $users){
	$objResponse  =  new xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	//$objResponse->alert($users);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 6)) {
		$group_id=abs((int)$group_id);
		$users_arr=explode(',',$users);
		$gi=new DistrUserItem;
		foreach($users_arr as $k=>$v){
			$v=abs((int)$v);
			$gi->DelUserFromGroup($group_id, $v);
		}
		
		$objResponse->loadCommands(ShowUsers($group_id));
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	return  $objResponse;  
}


//список групп пользователя
function ShowGroups($id, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	$objResponse  =  new xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');

	$id=abs((int)$id);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){
		//$objResponse->assign('user_'.$id.'_groups','innerHTML','');
		$objResponse->assign('user_rights_edit_inner','innerHTML','');
	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 6)) {
		$gi=new DistrGroups;
		
		$str=$gi->GetGroupsByUserId($id, 'discr/user_groups.html', true, $gfrom, $ofrom, $per_page);
		$objResponse->assign('user_rights_edit_inner','innerHTML',$str);
		$func=$gi->GenerateJSMarked($id, 'discr/users_mark_js.js', true);
		
		//генерим функцию для разметки массива выделенных пользователей
		$objResponse->setFunction("BuildArrs".$id, 'param', $func);
		$objResponse->call("BuildArrs".$id, '');
	  }else{
		//user_rights_edit_inner
		//$objResponse->assign('user_'.$id.'_groups','innerHTML','');
		$objResponse->assign('user_rights_edit_inner','innerHTML','');
		$objResponse->alert(NO_RIGHTS);  
		
	  }
	}
		
	return  $objResponse;  
}


//добавить юзера в группы
function AddUserToGroups($user_id, $groups, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	$objResponse  =  new xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$user_id=abs((int)$user_id);
	$users_arr=explode(',',$groups);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 6)) {
		$gi=new DistrUserItem;
		foreach($users_arr as $k=>$v){
			$v=abs((int)$v);
			$gi->AddUserToGroup($v, $user_id);
		}
		
		//$objResponse->assign('tableplace','innerHTML',RedrawUsMatrix($gfrom, $ofrom, $per_page));
		
		$objResponse->loadCommands(ShowGroups($user_id, $gfrom, $ofrom, $per_page));
		//$objResponse->assign('user_'.$user_id.'_groups','style.display','block');
	  }else{
		$objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	return  $objResponse;  
}
	
//исключить юзера из групп
function DelUserFromGroups($user_id, $groups, $gfrom=0, $ofrom=0, $per_page=ITEMS_PER_PAGE){
	$objResponse  =  new xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$user_id=abs((int)$user_id);
	$users_arr=explode(',',$groups);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 6)) {
		$gi=new DistrUserItem;
		foreach($users_arr as $k=>$v){
			$v=abs((int)$v);
			$gi->DelUserFromGroup($v, $user_id);
		}
		
		//$objResponse->assign('tableplace','innerHTML',RedrawUsMatrix($gfrom, $ofrom, $per_page));
		$objResponse->loadCommands(ShowGroups($user_id, $gfrom, $ofrom, $per_page));
		//$objResponse->assign('user_'.$user_id.'_groups','style.display','block');
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	return  $objResponse;   
}

function RedrawRightsCellUser($object_id, $user_id){
	$txt='';
	$smarty=new SmartyAj;
	
	$gi=new DistrRightsManager;
	
	$active_rights=Array(); $active_rights=$gi->GetActiveRightsUser($user_id, $object_id);
	$active_rights_len=count($active_rights);
	
	$inactive_rights=Array(); $inactive_rights=$gi->GetInactiveRightsUser($user_id, $object_id);
	$inactive_rights_len=count($inactive_rights);
	
	$smarty->assign('group_id',$user_id);
	$smarty->assign('selected_object', Array('id'=>$object_id, 'active_rights'=>$active_rights, 'active_rights_len'=>$active_rights_len, 'inactive_rights'=>$inactive_rights, 'inactive_rights_len'=>$inactive_rights_len));
	$smarty->assign('EVENT', COORDFUNC);
	$txt=$smarty->fetch('discr/user_object_rights_cell.html');
	
	return $txt;
}


//дать права юзеру на объект
function GrantOnObjectToUser($right_id, $object_id, $user_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$right_id=abs((int) $right_id);
	$object_id=abs((int) $object_id);
	$user_id=abs((int) $user_id);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 4)) {
		$gi=new DistrRightsManager;
		$gi->GrantOnObjectToUser($right_id, $object_id, $user_id);
		
		//перерисовка ячейки прав
		$objResponse->assign('group_'.$user_id.'_object_'.$object_id.'_rights_cell','innerHTML',RedrawRightsCellUser($object_id, $user_id));
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}

function RevokeFromObjectToUser($right_id, $object_id, $user_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$right_id=abs((int) $right_id);
	$object_id=abs((int) $object_id);
	$user_id=abs((int) $user_id);
	
	$au=new DiscrAuthUser();
	//проверим авторизацию
	$global_profile=$au->Auth();
	if($global_profile===NULL){

	  $objResponse->alert(NO_RIGHTS);
	}else{
	  $rights_man=new DistrRightsManager;
	  if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 4)) {
	
		$gi=new DistrRightsManager;
		
		//echo "$right_id, $object_id, $group_id "; die();
		$gi->RevokeFromObjectToUser($right_id, $object_id, $user_id);
		
		//перерисовка ячейки прав
		
		$objResponse->assign('group_'.$user_id.'_object_'.$object_id.'_rights_cell','innerHTML',RedrawRightsCellUser($object_id, $user_id));
	  }else{
		 $objResponse->alert(NO_RIGHTS);  
	  }
	}
	
	$objResponse->assign('wait_win','style.display',"none");  
	
	return  $objResponse;  
}


$xajax_read->processRequest();
?>