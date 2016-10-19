<style>
div.work_window_vis{
	position: absolute;
	width: 320px;
	height: 330px;
	
	border: 1px solid black;
	background-color: white;
	overflow: hidden;
	padding: 10px 10px 10px 10px;
	display: block;
}
.work_window_vis input[type=text]{
		
}
.work_window_vis textarea{
	width:300px;
	height:100px;
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


div.val_window_vis{
	position: absolute;
	width: 450px;
	height: 400px;
	
	border: 1px solid black;
	background-color: white;
	overflow: hidden;
	padding: 10px 10px 10px 10px;
	display: block;
}
div.val_window_invis{
	position: absolute;
	width: 450px;
	height: 400px;
	
	top: 100px;
	left: 100px;

	border: 1px solid black;
	background-color: white;
	overflow: hidden;
	padding: 10px 10px 10px 10px;

	display: none;
}

.val_window_vis textarea{
	width:430px;
	height:240px;
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
<div id="file_add_win" class="work_window_invis">
	<h3>Редактирование файла</h3>
	<form action="" method="post" name="inpp" id="inpp">
	
	<input type="hidden" name="old_file_id" id="old_file_id" value="">
	
	<strong>Название файла:</strong><br>
	<input type="text" name="file_id" id="file_id" value="" size="40" maxlength="255"><p>
	
	<strong>Пояснение</strong><br>
	<textarea cols="40" rows="5" name="descr" id="descr"></textarea><p>
	
	<input type="button" name="doInpFile" id="doInpFile" value="Добавить файл" onclick="m=document.getElementById('file_id'); if(m.value.length<1) {alert('Заполните Название файла!'); return false;} n=document.getElementById('descr'); mn=document.getElementById('file_add_win'); mn.className='work_window_invis'; <?=COORDFUNC?> mn=document.getElementById('wait_win'); mn.style.top=coord[1]; mn.style.left=coord[0]; mn.className='wait_window_vis'; xajax_AddFile(m.value, n.value);  " style="display: none; ">

	<input type="button" name="doEdFile" id="doEdFile" value="Править файл" onclick="m=document.getElementById('file_id'); if(m.value.length<1) {alert('Заполните Название файла!'); return false;} n=document.getElementById('descr'); nn=document.getElementById('old_file_id'); mn=document.getElementById('file_add_win'); mn.className='work_window_invis'; <?=COORDFUNC?> mn=document.getElementById('wait_win'); mn.style.top=coord[1]; mn.style.left=coord[0]; mn.className='wait_window_vis'; xajax_EditFile(nn.value, m.value, n.value); " style="display: none;">
	&nbsp;
	<input type="button" value="Отмена" onclick="m=document.getElementById('file_add_win'); m.className='work_window_invis';">
	
	</form>
</div>

<!-- Окно ожидания -->
<div id="wait_win" class="wait_window_invis">
<br>
<br>
<strong><em>подождите...</em></strong>
</div>


<!-- окно правки ресурса -->
<div id="res_add_win" class="work_window_invis">
	<h3>Редактирование ресурса</h3>
	<form action="" method="post" name="inpr" id="inpr">
	
	
	<input type="hidden" name="res_file_id" id="res_file_id" value="">
	<input type="hidden" name="old_res_id" id="old_res_id" value="">
	
	
	<strong>Название ресурса:</strong><br>
	<input type="text" name="res_id" id="res_id" value="" size="40" maxlength="255"><p>
	
	<strong>Пояснение</strong><br>
	<textarea cols="40" rows="5" name="res_descr" id="res_descr"></textarea><p>
	
	
	
	
	<input type="button" name="doInpRes" id="doInpRes" value="Добавить ресурс" onclick="
m=document.getElementById('res_id'); if(m.value.length<1) {alert('Заполните Название ресурса!'); return false;}
 n=document.getElementById('res_descr'); 
 nn=document.getElementById('res_file_id'); 
 
 mn=document.getElementById('res_add_win');  mn.className='work_window_invis';
  <?=COORDFUNC?> mn=document.getElementById('wait_win'); mn.style.top=coord[1]; mn.style.left=coord[0]; mn.className='wait_window_vis'; 

xajax_AddRes(nn.value, m.value, n.value);  
" style="display: none; ">

	<input type="button" name="doEdRes" id="doEdRes" value="Править ресурс" onclick="
	m=document.getElementById('res_id'); if(m.value.length<1) {alert('Заполните Название ресурса!'); return false;}
	 n=document.getElementById('res_descr');
	  nn=document.getElementById('old_res_id'); 
	  
   nnn=document.getElementById('res_file_id'); 
	  mn=document.getElementById('res_add_win'); mn.className='work_window_invis'; 
	  <?=COORDFUNC?> mn=document.getElementById('wait_win'); mn.style.top=coord[1]; mn.style.left=coord[0]; mn.className='wait_window_vis'; 
xajax_EdRes(nnn.value, nn.value, m.value, n.value); 

" style="display: none;">
	&nbsp;
	<input type="button" value="Отмена" onclick="m=document.getElementById('res_add_win'); m.className='work_window_invis';">
	
	</form>
</div>




<!-- окно правки значения ресурса -->
<div id="val_add_win" class="val_window_invis">
	<h3>Редактирование значения ресурса</h3>
	<form action="" method="post" name="inpv" id="inpv">
	
	<input type="hidden" name="val_file_id" id="val_file_id" value="">
	<input type="hidden" name="val_res_id" id="val_res_id" value="">
	
	
	<strong>Выберите язык:</strong><br>
	<select name="val_lang_id" id="val_lang_id">
	<?
	$lg=new LangGroup();
	echo $lg->GetItemsOpt($lang_id,'lang_name');
	?>
	</select><p>
	
	
	<strong>Значение</strong><br>
	<textarea cols="60" rows="20" name="res_value" id="res_value"></textarea><p>
	
	
	<input type="button" name="doInpVal" id="doInpVal" value="Добавить значение" onclick="
m=document.getElementById('res_value'); if(m.value.length<1) {alert('Заполните значение!'); return false;}
 nn=document.getElementById('val_file_id'); 
 nnn=document.getElementById('val_res_id');
 nl=document.getElementById('val_lang_id'); 
 
 mn=document.getElementById('val_add_win');  mn.className='work_window_invis';
  <?=COORDFUNC?> mn=document.getElementById('wait_win'); mn.style.top=coord[1]; mn.style.left=coord[0]; mn.className='wait_window_vis'; 

xajax_AddVal(nn.value, nnn.value, nl.value, m.value);  

return false;
" style="display: none; ">

	<input type="button" name="doEdVal" id="doEdVal" value="Править значение" onclick="
	
	m=document.getElementById('res_value'); if(m.value.length<1) {alert('Заполните значение!'); return false;}
 nn=document.getElementById('val_file_id'); 
 nnn=document.getElementById('val_res_id');
 nl=document.getElementById('val_lang_id'); 
 
 mn=document.getElementById('val_add_win');  mn.className='work_window_invis';
  <?=COORDFUNC?> mn=document.getElementById('wait_win'); mn.style.top=coord[1]; mn.style.left=coord[0]; mn.className='wait_window_vis'; 
 
xajax_EdVal(nn.value, nnn.value, nl.value, m.value);  

return false;

" style="display: none;">
				
	
	&nbsp;
	<input type="button" value="Отмена" onclick="m=document.getElementById('val_add_win'); m.className='val_window_invis';">
	
	</form>
</div>