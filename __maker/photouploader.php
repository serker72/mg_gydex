<?
//загрузчик фотографий из формы
if(isset($_POST['doLoad'])){
	require_once('../classes_s/resize_applyer.php');
//	$upph=Array();

	//разберем условия работы
	//ресайз 1
	$resize1=Array();
	$doit=isset($_POST['changeSize']);
	if($doit){
		if(isset($_POST['resize_kind'])){
			$resize_kind=abs((int)$_POST['resize_kind']);
		}else{
			$resize_kind=0;
		}
		switch($resize_kind){
			case 0:
				//проверка пикселей
				if(isset($_POST['new_w'])){
					$w=abs((int)$_POST['new_w']);
					if($w==0) $w=800;
				}else $w=800;
				if(isset($_POST['new_h'])){
					$h=abs((int)$_POST['new_h']);
					if($h==0) $h=600;					
				}else $h=600;
				$cutit=isset($_POST['do_cut']);
				
				$resize1=Array(
					'doit'=>$doit,
					'resize_kind'=>0,
					'pre'=>'',
					'do_resize'=>true,
					'resize_params'=>Array(
						'w'=>$w,
						'h'=>$h,
						'cutit'=>$cutit
					)
				
				);
			break;
			case 1:
				//проверка %
				if(isset($_POST['new_percent'])){
					$percent=abs((int)$_POST['new_percent']);
					if($percent==0) $percent=800;
				}else $percent=15;
				
				$resize1=Array(
					'doit'=>$doit,
					'pre'=>'',
					'do_resize'=>true,
					'resize_kind'=>1,
					'resize_params'=>Array(
						'percent'=>$percent
					)
				
				);
			break;
		}
	}else{
		$resize1=Array(
			'doit'=>true,
			'pre'=>'',
			'do_resize'=>false,
			'resize_kind'=>1,
			'resize_params'=>Array()
		);
	
	
	}
	
	
	//ресайз 2 (допфайл)
	$resize2=Array();
	$doit=isset($_POST['makeAddFile']);
	if($doit){
		if(isset($_POST['add_file_pre'])){
			$pre=SecurePath($_POST['add_file_pre']);
		}else{
			$pre=0;
		}
		
		
		$do_resize=isset($_POST['changeAddFile']);
		if($do_resize){
		
			if(isset($_POST['add_resize_kind'])){
				$resize_kind=abs((int)$_POST['add_resize_kind']);
			}else{
				$resize_kind=0;
			}
			switch($resize_kind){
				case 0:
					//проверка пикселей
					if(isset($_POST['add_new_w'])){
						$w=abs((int)$_POST['add_new_w']);
						if($w==0) $w=800;
					}else $w=800;
					if(isset($_POST['add_new_h'])){
						$h=abs((int)$_POST['add_new_h']);
						if($h==0) $h=800;
					}else $h=600;
					$cutit=isset($_POST['add_do_cut']);
					
					$resize2=Array(
						'doit'=>$doit,
						'do_resize'=>$do_resize,
						'resize_kind'=>0,
						'pre'=>$pre,
						'resize_params'=>Array(
							'w'=>$w,
							'h'=>$h,
							'cutit'=>$cutit
						)
					
					);
				break;
				case 1:
					//проверка %
					if(isset($_POST['add_new_percent'])){
						$percent=abs((int)$_POST['add_new_percent']);
						if($percent==0) $percent=15;
					}else $percent=15;
					
					$resize2=Array(
						'doit'=>$doit,
						'pre'=>$pre,
						'do_resize'=>$do_resize,
						'resize_kind'=>1,
						'resize_params'=>Array(
							'percent'=>$percent
						)
					
					);
				break;
			}
		}else{
			$resize2=Array(
				'doit'=>$doit,
				'do_resize'=>false,
				'resize_kind'=>0,
				'pre'=>$pre,
				'resize_params'=>Array()
			);
		}
		
	}
	
	
	
	
	//работа с превью
	$resize3=Array();
	$doit=isset($_POST['doPreview']);
	if($doit){
		
		//проверка пикселей
		if(isset($_POST['pre_w'])){
			$w=abs((int)$_POST['pre_w']);
			if($w==0) $w=120;
		}else $w=120;
		if(isset($_POST['pre_h'])){
			$h=abs((int)$_POST['pre_h']);
			if($h==0) $h=90;					
		}else $h=90;
		$cutit=isset($_POST['pre_do_cut']);
		
		$resize3=Array(
			'doit'=>$doit,
			'resize_kind'=>0,
			'pre'=>'tn',
			'do_resize'=>true,
			'resize_params'=>Array(
				'w'=>$w,
				'h'=>$h,
				'cutit'=>$cutit
			)
		
		);
	
	}
	
	
	//ресайз 4 (допфайл)
	$resize4=Array();
	$doit=isset($_POST['makeAdd1File']);
	if($doit){
		if(isset($_POST['add1_file_pre'])){
			$pre=SecurePath($_POST['add1_file_pre']);
		}else{
			$pre=0;
		}
		
		
		$do_resize=isset($_POST['changeAdd1File']);
		if($do_resize){
		
			if(isset($_POST['add1_resize_kind'])){
				$resize_kind=abs((int)$_POST['add1_resize_kind']);
			}else{
				$resize_kind=0;
			}
			switch($resize_kind){
				case 0:
					//проверка пикселей
					if(isset($_POST['add1_new_w'])){
						$w=abs((int)$_POST['add1_new_w']);
						if($w==0) $w=800;
					}else $w=800;
					if(isset($_POST['add1_new_h'])){
						$h=abs((int)$_POST['add1_new_h']);
						if($h==0) $h=800;
					}else $h=600;
					$cutit=isset($_POST['add1_do_cut']);
					
					$resize4=Array(
						'doit'=>$doit,
						'do_resize'=>$do_resize,
						'resize_kind'=>0,
						'pre'=>$pre,
						'resize_params'=>Array(
							'w'=>$w,
							'h'=>$h,
							'cutit'=>$cutit
						)
					
					);
				break;
				case 1:
					//проверка %
					if(isset($_POST['add1_new_percent'])){
						$percent=abs((int)$_POST['add1_new_percent']);
						if($percent==0) $percent=15;
					}else $percent=15;
					
					$resize4=Array(
						'doit'=>$doit,
						'pre'=>$pre,
						'do_resize'=>$do_resize,
						'resize_kind'=>1,
						'resize_params'=>Array(
							'percent'=>$percent
						)
					
					);
				break;
			}
		}else{
			$resize4=Array(
				'doit'=>$doit,
				'do_resize'=>false,
				'resize_kind'=>0,
				'pre'=>$pre,
				'resize_params'=>Array()
			);
		}
		
	}
	
	
	
	
	foreach($HTTP_POST_FILES as $k=>$v){
		if(eregi("photo_load",$k)){
			if(file_exists($v['tmp_name'])){
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 25)) {
				
				  $pa=new ResizeApplyer();
				  $addname=time();
				  $pa->MakePhoto($v,$resize1,$fullpath,$addname);
				  $pa->MakePhoto($v,$resize2,$fullpath,$addname);
				  $pa->MakePhoto($v,$resize3,$fullpath,$addname);						
				  $pa->MakePhoto($v,$resize4,$fullpath,$addname);					
				}else{}
				unset($pa);
			}
			
		}
	
	}
	
	
	
	
	header("Location: photofolder.php?folder=".$folder."&from=".$from."&mode=".$mode);
	die();
}


?>