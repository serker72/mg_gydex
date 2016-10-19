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
	  require_once('../../classes/newsfileitem.php');
  		
	  $_nf=new NewsFileItem;	
		
	  $visib_path =  // 'userfiles/image';
	  $upload_init_path= $_nf->GetStoragePath(); //ABSPATH.$visib_path;
	  $visib_path = eregi_replace('^'.ABSPATH, '', $upload_init_path);
	  
	  $zz='';
	  $can_optim=false;
	   
	  $id=abs((int)$_POST['id']);
	  
	  //обработка файла
	
		
		$extension=0;
		if(eregi("^(.*)\\.(jpg|jpeg|jpe)$", $_FILES['Filedata']['name'],$P)) $extension='.jpg';
		if(eregi("^(.*)\\.(gif)$", $_FILES['Filedata']['name'],$P)) $extension='.gif';
		if(eregi("^(.*)\\.(png)$", $_FILES['Filedata']['name'],$P)) $extension='.png';	
		if($extension!==0){
			
			$image1='';
			if($extension=='.jpg') $image1 = imageCreatefromjpeg($_FILES['Filedata']['tmp_name']);
			if($extension=='.gif') $image1 = imageCreatefromgif($_FILES['Filedata']['tmp_name']);
			if($extension=='.png') $image1 = imageCreatefrompng($_FILES['Filedata']['tmp_name']);		
					
			if($image1!='') $can_optim=true;
		}
		$_FILES['Filedata']['name']=SecureCyr(iconv('utf-8', 'windows-1251',$_FILES['Filedata']['name'])); 
		if($can_optim){

			  $resize_tn=Array(
							  'doit'=>true,
							  'do_resize'=>true,
							  'resize_kind'=>0,
							  'pre'=>'',
							  'resize_params'=>Array(
								  'w'=>1024,
								  'h'=>1024,
								  'cutit'=>false
							  )
						  );
					  
						  
			  
			 
			  $pa=new ResizeApplyer();
			  $addname=time();
			  
			 
		
			  
			 
			  $tempname=$pa->MakePhoto($_FILES['Filedata'],$resize_tn,$upload_init_path.$zz,$addname);
	  
		}else{
			  $tempname=tempnam($_nf->GetStoragePath(), '');
			move_uploaded_file ( $_FILES['Filedata']['tmp_name'], $tempname);
		}
		
		$_nf->Add(array('news_id'=>$id, 'orig_name'=>$_FILES['Filedata']['name'],  'filename'=>basename($tempname)));
		
		
	 
	
	}else{
		echo iconv('windows-1251', 'utf-8', 'alert("'.NO_RIGHTS.'");');
		
	}
	
	
	exit(0);
?>