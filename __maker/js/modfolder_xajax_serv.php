<?
session_name("photofolder");
session_start();
require_once('../../classes/global.php');
require_once(ABSPATH.'classes/xajax/xajax_core/xajax.inc.php');
require_once('modfolder_xajax_common.php');
require_once(ABSPATH.'classes_s/dirrut.php');
require_once(ABSPATH.'classes_s/foldlist_s.php');
require_once('../photosettings.php');


function CreateFolder($newf,$path,$mode){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	$dr=new DirRut();
	
	$mode=(int)$mode;
	
	$o_path=str_replace('//','/',PHOTOS_BASEPATH.$path.'/');
	
	$new_path=realpath(str_replace('//','/',PHOTOS_BASEPATH.$path.'/'));
	$comp_basepath=realpath(PHOTOS_BASEPATH);

	if( (!file_exists($new_path))
	||(strpos($new_path,$comp_basepath)===false)
	||(strpos($new_path,$comp_basepath)!==0)){
		//$objResponse->assign('debug','innerHTML','figvam');  
	}else{
		$div_to_reload=$path;
		/*if($div_to_reload=='') $path_to_reload='/'; //$div_to_reload='/';
		else $path_to_reload=$div_to_reload;
		*/
		
		//if($div_to_reload=='/') $div_to_reload='';
		
		$path_to_reload=$div_to_reload;
		
		$res=$dr->CreateFolder($o_path.$newf);
		if($res!==false){
		
			$folders=DrawSubtree($path_to_reload, $newf, $mode);
			$objResponse->assign('sub_'.'subroot'.$div_to_reload,'innerHTML','');  	
			$objResponse->assign('sub_'.'subroot'.$div_to_reload,'innerHTML',$folders);  	
			$objResponse->assign('debug','innerHTML','subroot'.$div_to_reload);  	
		}

	}
	return  $objResponse;  
}


//переименование папки
function RenameFolder($path, $oldname, $newname, $mode){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	$dr=new DirRut();
	
	$mode=(int)$mode;
	
	$o_path=str_replace('//','/',PHOTOS_BASEPATH.$path.'/');
	
	$new_path=realpath(str_replace('//','/',PHOTOS_BASEPATH.$path.'/'));
	$comp_basepath=realpath(PHOTOS_BASEPATH);

	if( (!file_exists($new_path))
	||(strpos($new_path,$comp_basepath)===false)
	||(strpos($new_path,$comp_basepath)!==0)){
		//$objResponse->assign('debug','innerHTML','figvam');  
	}else{
		
		$div_to_reload=$path;
		/*if($div_to_reload=='') $path_to_reload='/'; //$div_to_reload='/';
		else $path_to_reload=$div_to_reload;
		
		if($div_to_reload=='/') $div_to_reload='';*/
		$path_to_reload=$div_to_reload;
		
		$res=$dr->RenameFolder($o_path,  $oldname, $newname); 
		
		if($res!==false){
			$folders=DrawSubtree($path_to_reload, $newname, $mode);
			$objResponse->assign('sub_'.'subroot'.$div_to_reload,'innerHTML','');  	
			$objResponse->assign('sub_'.'subroot'.$div_to_reload,'innerHTML',$folders);  	
			$objResponse->assign('debug','innerHTML','subroot'.$div_to_reload);  	
		}else{
			//$objResponse->assign('debug','innerHTML',$o_path.'/'.$oldname.' ne sus');  
		}
		
		
	}
	
	
	
	return  $objResponse;  
}


//перерисовка поддерева папок
function DrawSubtree($path, $active_dir, $mode){
	//рисуем каталоги
	
//	$fc=new FoldersList_S('/level/sub','/subsub/subsubsub');
	$fc=new FoldersList_S($path,'/'.$active_dir);
	$fc->SetProgName('photofolder_s.php');
	$fc->SetRoot(false);
	$fc->SetXajax(true);
	
	
//	return $path.'/'.$active_dir;
	
	$params=Array();
	$params['mode']=$mode;
	$params['lang_id']=LANG_CODE;
	$folders=$fc->CreateTree($params);
	unset($fc);

	return  $folders;  
}


/*
function DrawRes($file_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	//$srt = iconv('utf-8', 'windows-1251', $srt); 
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	//$rf->SetTemplates('reseditor/res-aj.html', '','reseditor/res_resrow_aj.html','reseditor/res_valrow_aj.html');
	
	
	$result=$rf->DrawRes($file_id);
	
	$objResponse->assign(''.$file_id.'_resoures','innerHTML',$result);  
	$objResponse->assign(''.$file_id.'_resoures','style.display',"block");  
	$objResponse->assign($file_id.'_blo','style.display',"none");  
	$objResponse->assign($file_id.'_hid','style.display',"block");  
	
	
	return  $objResponse;  
}

//удаление значения ресурса
function DelVal($file_id,$res_id,$lang_id){
	
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	//$srt = iconv('utf-8', 'windows-1251', $srt); 
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	//$rf->SetTemplates('reseditor/res-aj.html', '','reseditor/res_resrow_aj.html','reseditor/res_valrow_aj.html');
	
	//удалим значение
	$rf->DelResLang($file_id,$res_id,$lang_id);
	
	//обновим список значений ресурса
	$result=$rf->DrawVals($file_id,$res_id);
	$objResponse->assign($file_id.'_'.$res_id.'_vals', 'innerHTML', $result);
	
	return  $objResponse;  
}


//удаление ресурса
function DelRes($file_id,$res_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');
	
	//удалим значение
	$rf->DelRes($file_id,$res_id);
	
	//обновим список  ресурсov
	$result=$rf->DrawRes($file_id);
	$objResponse->assign(''.$file_id.'_resoures','innerHTML',$result);  
	
	return  $objResponse;  
}

//удаление файла
function DelFile($file_id){
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	$rf=new ResFileAj(ABSPATH.'cnf/resources.txt');

	//удалим файл
	$rf->DelFile($file_id);
	
	//обновим 
	$result=$rf->DrawTree(true);
	$objResponse->assign('library_total','innerHTML',$result);  
	
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
	$rf->AddFile($params);
	
	//обновим 
	$result=$rf->DrawTree(true);
	$objResponse->assign('library_total','innerHTML',$result);  
	
	
	
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
	$rf->EdFile($old_file_id, $params);
	
	//обновим 
	$result=$rf->DrawTree(true);
	$objResponse->assign('library_total','innerHTML',$result);  
	
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
	
	$rf->AddRes($file_id, $params);
	
	//обновим 
	$result=$rf->DrawRes($file_id);
	$objResponse->assign($file_id.'_blo','style.display',"none");  
	$objResponse->assign($file_id.'_hid','style.display',"block");  
	
	$objResponse->assign($file_id.'_resoures','innerHTML',$result);  
	
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
	$rf->EdRes($file_id, $res_id, $params);
	
	//обновим 
	$result=$rf->DrawRes($file_id);
	$objResponse->assign($file_id.'_blo','style.display',"none");  
	$objResponse->assign($file_id.'_hid','style.display',"block");  
	$objResponse->assign($file_id.'_resoures','innerHTML',$result);  
	
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
	$rf->AddResLang($file_id,$res_id,$params);
	
	//обновим 
	$result=$rf->DrawVals($file_id,$res_id);
	$objResponse->assign($file_id.'_'.$res_id.'_vals', 'innerHTML', $result);
	
	
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
	$rf->EdResLang($file_id,$res_id,$lang_id, $params);
	
	//обновим 
	$result=$rf->DrawVals($file_id,$res_id);
	$objResponse->assign($file_id.'_'.$res_id.'_vals', 'innerHTML', $result);
	
	
	$objResponse->assign('wait_win','className','wait_window_invis');  
	
	return $objResponse;  
}
*/

$xajax_read->processRequest();
?>