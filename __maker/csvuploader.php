<?
//загрузчик файлов из формы
if(isset($_POST['doLoad'])){
	
	foreach($HTTP_POST_FILES as $k=>$v){
		if(eregi("photo_load",$k)){
			if(file_exists($v['tmp_name'])){
				$pref = time();
				//$newname=eregi_replace('([[:alnum:]])(\\.[[:alnum:].]*)?$','\\1'.'-'.$pref.$extension,$v['name']);
				$newname=eregi_replace('([[:alnum:]])(\\.[[:alnum:].]*)?$','\\1'.'-'.$pref.'\\2',$v['name']);
				$rights_man=new DistrRightsManager;
				if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 25)) {
					copy($v['tmp_name'],$fullpath.'/'.$newname);
				}else{}
			}
		}
	}
	
	header("Location: csvfiles.php?folder=".$folder."&from=".$from."&mode=".$mode);
	die();
}
?>