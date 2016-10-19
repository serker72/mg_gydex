<?php
	// Work-around for setting up a session because Flash Player doesn't send the cookies
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
	session_start();
	
	//���������������� �����������
	require_once('../inc/adm_header_js.php');
	
	$rights_man=new DistrRightsManager;
	if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 25)) {
	
	  require_once('../../classes/mmenuitem.php');
	  require_once('../../classes_s/resize_applyer_nonglob.php');
  
	  $visib_path = 'userfiles/image';
	  $upload_init_path=ABSPATH.$visib_path;
	  
	  $zz='';
	  
	  if (!isset($_POST["mid"])) {
		  echo iconv('windows-1251', 'utf-8', 'alert("������ ����������� �������� ��������!")');
		  
	  }else {
		  $mid=abs((int)$_POST["mid"]);
	  
		  $mi=new MmenuItem;
		  $path= $mi->RetrievePath($mid, $flaglost, $vloj);
		  
		   $zz=''; 
		  $dirs=Array();
				  
		  foreach($path as $k=>$v) {
			  foreach($v as $kk=>$vv){
				  //���� ���� ���� "����", �� ����� ���, ���� ��� - �� ���� �������
				  if($vv['path']==''){
					  $dirs[]=$kk;
				  }else $dirs[]=SecurePath($vv['path']);
			  }
		  }
		  
		  
		  if(file_exists($upload_init_path)&&is_dir($upload_init_path)){
		  //������� �������� �� �����
			foreach($dirs as $k=>$v){
				$zz.='/'.$v;
				if(file_exists($upload_init_path.$zz)&&is_dir($upload_init_path.$zz)){
					
				}else mkdir($upload_init_path.$zz);
				
			}
		  }
		  
		
	  }
	  
	  //��������� �����
	  
	  $resize_main=Array(
					  'doit'=>true,
					  'do_resize'=>false,
					  'pre'=>''
					  
				  );
				  
				  
/*		  $resize_main=Array(
					  'doit'=>true,
					  'do_resize'=>true,
					  'resize_kind'=>0,
					  'pre'=>'',
					  'resize_params'=>Array(
						  'w'=>800,
						  'h'=>600,
						  'cutit'=>false
					  )
				  );
	*/  
	  
	 
	  $pa=new ResizeApplyer();
	  $addname=time();
	  

	  
	  $newname1=$pa->MakePhoto($_FILES['Filedata'],$resize_main,$upload_init_path.$zz,$addname);
	  
	 
	  echo '
  
		$("#photo_small").val( "'.$visib_path.$zz.'/'.basename($newname1).'");
		
		
		$("#quick_upload").css("display","none");
		$("#full_upload").css("display","block");
		
		';
	
	}else{
		echo iconv('windows-1251', 'utf-8', 'alert("'.NO_RIGHTS.'");');
		
	}
	
	
	exit(0);
?>