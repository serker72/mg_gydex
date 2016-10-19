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