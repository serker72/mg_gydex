<style>

div.work_window_vis{
	position: absolute;
	width: 200px;
	height: 120px;
	
	border: 1px solid black;
	background-color: white;
	overflow: hidden;
	padding: 10px 10px 10px 10px;
	display: block;
}
div.work_window_invis{
	position: absolute;
	width: 320px;
	height: 230px;
	
	top: 100px;
	left: 100px;

	border: 1px solid black;
	background-color: white;
	overflow: hidden;
	padding: 10px 10px 10px 10px;

	display: none;
}

div.wait_window_vis{
	position: absolute;
	width: 200px;
	height: 100px;
	
	border: 1px solid black;
	background-color: white;
	overflow: hidden;
	padding: 10px 10px 10px 10px;
	text-align: center;
	vertical-align: middle;
	display: block;
}
div.wait_window_invis{
	position: absolute;
	width: 200px;
	height: 100px;
	
	top: 100px;
	left: 100px;

	border: 1px solid black;
	background-color: white;
	overflow: hidden;
	padding: 10px 10px 10px 10px;

	text-align: center;
	vertical-align: middle;
	
	display: none;
}

</style>



<!-- Окно создания-правки файла -->
<div id="dir_ed_win" class="work_window_invis">
	<h3>Введите имя папки:</h3>
	<form action="" method="post" name="inpp" id="inpp">
	
	<input type="text" name="folderpath" id="folderpath" value="">
	<input type="hidden" name="action" id="action" value="">
	<input type="text" name="oldname" id="oldname" value="">
	<input type="text" name="mode" id="mode" value="">
	
	<input type="text" name="foldername" id="foldername" size="30" maxlength="255" onKeyDown="if(event.keyCode==13){ }"><p>
	
	<input type="button" id="CreateBttn" name="CreateBttn" value="Создать" onClick="
		m=document.getElementById('folderpath');  
		n=document.getElementById('foldername');  
		oo=document.getElementById('mode');  
		
		xajax_CreateFolder(n.value,m.value, oo.value); return false;
	">
	
	<input type="button" id="RenameBttn" name="RenameBttn" value="Переименовать" onClick="
		m=document.getElementById('folderpath');  
		n=document.getElementById('foldername');  
		oo=document.getElementById('mode');  
		ooo=document.getElementById('oldname');  
		
		xajax_RenameFolder(m.value,ooo.value, n.value, oo.value); return false;
	">


	&nbsp;&nbsp; <input type="button" value="Отмена" onclick="r=document.getElementById('dir_ed_win'); r.className='work_window_invis';">
	
	
	
	<input type="button" name="doInpFile" id="doInpFile" value="Добавить файл" onclick="m=document.getElementById('file_id'); if(m.value.length<1) {alert('Заполните Название файла!'); return false;} n=document.getElementById('descr'); mn=document.getElementById('file_add_win'); mn.className='work_window_invis'; <?=COORDFUNC?> mn=document.getElementById('wait_win'); mn.style.top=coord[1]; mn.style.left=coord[0]; mn.className='wait_window_vis'; xajax_AddFile(m.value, n.value);  " style="display: none; ">

	<input type="button" name="doEdFile" id="doEdFile" value="Править файл" onclick="m=document.getElementById('file_id'); if(m.value.length<1) {alert('Заполните Название файла!'); return false;} n=document.getElementById('descr'); nn=document.getElementById('old_file_id'); mn=document.getElementById('file_add_win'); mn.className='work_window_invis'; <?=COORDFUNC?> mn=document.getElementById('wait_win'); mn.style.top=coord[1]; mn.style.left=coord[0]; mn.className='wait_window_vis'; xajax_EditFile(nn.value, m.value, n.value); " style="display: none;">
	
	</form>
</div>




<!-- Окно ожидания -->
<div id="wait_win" class="wait_window_invis">
<br>
<br>
<strong><em>подождите...</em></strong>
</div>












<!--

<div id="folderer" style="" class="reninvis">
<form action="" name="reform" id="reform">
<h3>Введите имя папки:</h3>
<input type="hidden" name="folderpath" id="folderpath" value="">
<input type="hidden" name="action" id="action" value="">
<input type="hidden" name="oldname" id="oldname" value="">
<input type="text" name="foldername" id="foldername" size="30" maxlength="255" onKeyDown="if(event.keyCode==13) ModifyFolder();"><br>
<input type="button" value="ОК" onClick="ModifyFolder();">&nbsp;&nbsp; <input type="button" value="Отмена" onclick="r=document.getElementById('folderer'); r.className='reninvis';">
</form>
</div>


<div id="wait" style="" class="wait">
<br>
<br>
<strong><em>Подождите, пожалуйста...</em></strong>
</div>


<div id="filerer" style="" class="reninvis">
<form action="" name="fileform" id="fileform">
<h3>Введите имя файла:</h3>
<input type="hidden" name="filepath" id="filepath" value="">
<input type="hidden" name="fileaction" id="fileaction" value="">
<input type="hidden" name="oldfilename" id="oldfilename" value="">
<input type="text" name="filename" id="filename" size="30" maxlength="255" onKeyDown="if(event.keyCode==13) ModifyFile();"><br>
<input type="button" value="ОК" onClick="ModifyFile();">&nbsp;&nbsp; <input type="button" value="Отмена" onclick="r=document.getElementById('filerer'); r.className='reninvis';">
</form>
</div>

-->