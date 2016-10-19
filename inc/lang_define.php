<?
//определим какой язык
if(isset($_GET['lang'])){
	$backurl=getenv('HTTP_REFERER');
	
	//die();
	$lang=abs((int)$_GET['lang']);
	$li=new LangItem();
	$l=$li->GetItemById($lang,1);
	if($l!=false){
		$_SESSION['lang']=$lang;
		
		header('Location: '.$backurl);
		die();
	}else{
		//попробуем опр-ть язык по умолчанию
		$l=$li->GetItemById(LANG_CODE,1);
		if($l!=false){
			$_SESSION['lang']=LANG_CODE;
		}else{
			header("Location: /404.php");
			die();
		}
	}
}



//хедер определения языка
if(isset($_SESSION['lang'])){
	$lang=abs((int)$_SESSION['lang']);
	$li=new LangItem();
	$l=$li->GetItemById($lang,1);
	if($l==false){
		//попробуем опр-ть язык по умолчанию
		$l=$li->GetItemById(LANG_CODE,1);
		if($l!=false){
			$_SESSION['lang']=LANG_CODE;
			header("Location: /index.php");
			die();
		}else{
			header("Location: /404.php");
			die();
		}
	}else{
		
	}
}else{
	$_SESSION['lang']=LANG_CODE;
	$lang=LANG_CODE;
	$li=new LangItem();
	$l=$li->GetItemById(LANG_CODE);
}
?>