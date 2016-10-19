<?php
	// Work-around for setting up a session because Flash Player doesn't send the cookies
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
	session_start();
	
	//административная авторизация
	require_once('../inc/adm_header_js.php');
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 25)) {
	
	  require_once('../../classes/mmenuitem.php');
	  require_once('../../classes_s/resize_applyer_nonglob.php');
  
	  $visib_path = 'userfiles/image';
	  $upload_init_path=ABSPATH.$visib_path;
	  
	  $zz='';
	  
	  if (!isset($_POST["mid"])) {
		  echo iconv('windows-1251', 'utf-8', 'alert("Ошибка определения каталога загрузки!")');
		  
	  }else {
		  $mid=abs((int)$_POST["mid"]);
	  
		  $mi=new MmenuItem;
		  $path= $mi->RetrievePath($mid, $flaglost, $vloj);
		  
		  
		  $dirs=Array();
				  
		  foreach($path as $k=>$v) {
			  foreach($v as $kk=>$vv){
				  //если есть поле "путь", то пишем его, если нет - то айди раздела
				  if($vv['path']==''){
					  $dirs[]=$kk;
				  }else $dirs[]=SecurePath($vv['path']);
			  }
		  }
		  
		  
		  if(file_exists($upload_init_path)&&is_dir($upload_init_path)){
		  //создаем каталоги от корня
			foreach($dirs as $k=>$v){
				$zz.='/'.$v;
				if(file_exists($upload_init_path.$zz)&&is_dir($upload_init_path.$zz)){
					
				}else {
					//echo 'alert("'.$upload_init_path.$zz.'");';
					mkdir($upload_init_path.$zz);
				}
				
			}
		  }
		 
		 $zz='';
		 
	  }
	  
	  //обработка файла
	  
	/*  $resize_main=Array(
					  'doit'=>true,
					  'do_resize'=>false 
					  
				  );
				  */
				  

	  $resize_tn=Array(
					  'doit'=>true,
					  'do_resize'=>true,
					  'resize_kind'=>0,
					  'pre'=>'tn',
					  'resize_params'=>Array(
						  'w'=>315,
						  'h'=>315,
						  'cutit'=>false
					  )
				  );
			  
				  
	  
	 
	  $pa=new ResizeApplyer();
	  $addname=time();
	  
	  $_FILES['Filedata']['name']=SecureCyr(iconv('utf-8', 'windows-1251',$_FILES['Filedata']['name'])); 

	  
	//  $newname1=$pa->MakePhoto($_FILES['Filedata'],$resize_main,$upload_init_path.$zz,$addname);
	  $newname2=$pa->MakePhoto($_FILES['Filedata'],$resize_tn,$upload_init_path.$zz,$addname);
	  
	 
	  echo '
  
		$("#photo_small").val( "'.$visib_path.$zz.'/'.basename($newname2).'");
		 
		$("#quick_upload").css("display","none");
		$("#full_upload").css("display","block");
		
		';
	
	}else{
		echo iconv('windows-1251', 'utf-8', 'alert("'.NO_RIGHTS.'");');
		
	}
	
	
	exit(0);
?>