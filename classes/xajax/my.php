<?
require_once('my_com.php');




?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<title>testing</title>
	    <?php  
		$xajax_read->printJavascript();  
		?>    
</head>

<body>





<strong>Текст для записи:</strong><br>
<form action="">
<textarea cols="40" rows="10" name="mm" id="mm"></textarea>
<br>
<br>
<input type="button" name="doWrite" id="doWrite" value="Записать в файл" onclick="m=document.getElementById('mm'); tt=m.value; xajax_WriteData(tt); return false;">

<input type="button" name="doRead" id="doRead" value="Прочитать из файла" onclick="xajax_ReadData(); return false;">
<br>
<input type="reset">
</form>




</body>
</html>
