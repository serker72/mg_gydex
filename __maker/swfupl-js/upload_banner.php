<?php
	// Work-around for setting up a session because Flash Player doesn't send the cookies
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
	session_start();
	
	/*//административная авторизация
	require_once('../inc/adm_header_js.php');
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 25)) {*/
	
	  require_once('../../classes/mmenuitem.php');
	  require_once('../../classes_s/resize_applyer_nonglob.php');
  
	  $visib_path = 'userfiles/image';
	  $upload_init_path=ABSPATH.$visib_path;
	  
	  $zz='';//'/notes';
	  
	 
	  
	  //обработка файла
	  
	  $resize_main=Array(
					  'doit'=>true,
					  'do_resize'=>false,
					 /*'resize_kind'=>0,*/
					  'pre'=>''
					 /* 'resize_params'=>Array(
						  'w'=>650,
						  'h'=>650,
						  'cutit'=>false
					  )*/
					  
				  );
				  
				  

			  
				  
	  
	 
	  $pa=new ResizeApplyer();
	  $addname=time();
	  
	  $_FILES['Filedata']['name']=SecurePath(iconv('utf-8','windows-1251',$_FILES['Filedata']['name']));

	  $ext=substr($_FILES['Filedata']['name'], strrpos($_FILES['Filedata']['name'], '.') + 1);

	  if((strtolower($ext)=='swf')||(strtolower($ext)=='flv')){
		 $newname1=$upload_init_path.$zz.'/'.$addname.'-'.$_FILES['Filedata']['name'];
		 move_uploaded_file($_FILES['Filedata']['tmp_name'],$newname1);
 
	  }else{
	  
	  	$newname1=$pa->MakePhoto($_FILES['Filedata'],$resize_main,$upload_init_path.$zz,$addname);
	  }
	 
	  echo '
  
		$("#photo_small").val("'.$visib_path.$zz.'/'.basename($newname1).'");
		
		
		$("#quick_upload").css("display","none");
		$("#full_upload").css("display","block");
		
		';
	

	exit(0);
?>