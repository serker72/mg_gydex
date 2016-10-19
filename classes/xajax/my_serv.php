<?
require_once('my_com.php');
function ReadData(){
	$ft=new FileText();
	$srt=$ft->GetItem('somefile.txt');
	$objResponse  =  new  xajaxResponse(); 
	$objResponse->setCharacterEncoding('windows-1251');
	
	//$srt = iconv('utf-8', 'windows-1251', $srt); 
	
	$objResponse->assign('mm','value',"$srt");  
	return  $objResponse;  
}

function WriteData($text=''){
	$objResponse  =  new  xajaxResponse();  
	$objResponse->setCharacterEncoding('windows-1251');
	
	$text = iconv('utf-8', 'windows-1251', $text); 
	
	$ft=new FileText();
	$ft->Edit($text, 'somefile.txt');
	
	
	$objResponse->assign(  "mm",  "value",  $text  );  
	return  $objResponse;  
}


$xajax_read->processRequest();
?>