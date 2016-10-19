<?php /* Smarty version 2.6.22, created on 2016-10-19 16:08:16
         compiled from langs.html */ ?>
<!-- список подразделов в разделе -->

 
<!-- форма разбивки списка итемов по Х штук на страницу -->

	<form action="<?php echo $this->_tpl_vars['listpagename']; ?>
" name="topa" id="topa">
		<input type="hidden" name="from" value="0">
		
		<strong>Разбивать по:</strong> 
		<select name="to_page" id="to_page" onChange="document.forms.topa.submit();">
			<option value="10"<?php if ($this->_tpl_vars['topage'] == 10): ?> SELECTED <?php endif; ?>>10</option>
			<option value="1"<?php if ($this->_tpl_vars['topage'] == 1): ?> SELECTED <?php endif; ?>>1</option>
			<option value="30"<?php if ($this->_tpl_vars['topage'] == 30): ?> SELECTED <?php endif; ?>>30</option>
			<option value="50"<?php if ($this->_tpl_vars['topage'] == 50): ?> SELECTED <?php endif; ?>>50</option>
			<option value="100"<?php if ($this->_tpl_vars['topage'] == 100): ?> SELECTED <?php endif; ?>>100</option>			
			<option value="9999"<?php if ($this->_tpl_vars['topage'] == 9999): ?> SELECTED <?php endif; ?>>9999</option>						
		</select> <strong>позиций на страницу.</strong>
		
		</form>	

<form action="<?php echo $this->_tpl_vars['listpagename']; ?>
" method="post" name="updater" id="updater">
<div align="right">
<input type="submit" name="Update1" id="Update1" value="Внести изменения!" onclick="return window.confirm('ВНИМАНИЕ!!! Вы уверены, что хотите произвести выбранное действие над группой записей?');"></div>
<input type="hidden" name="from" id="from" value="<?php echo $this->_tpl_vars['fromno']; ?>
">
<input type="hidden" name="to_page" id="to_page" value="<?php echo $this->_tpl_vars['topage']; ?>
">
 


<?php echo $this->_tpl_vars['pages']; ?>




 <table class="gydex_table" >
          <thead>
          <tr align="left" valign="top">
              <th width="15%"> Имя </th>
              <th width="*"> Параметры </th>
              
            
              <th width="80"> Дополнительно </th>	
              <th width="24"> <input id="check_all" type="checkbox" value="1" /> </th>
          </tr>
          </thead>
          <tbody>
 
<?php unset($this->_sections['rowsec']);
$this->_sections['rowsec']['name'] = 'rowsec';
$this->_sections['rowsec']['loop'] = is_array($_loop=$this->_tpl_vars['items']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['rowsec']['show'] = true;
$this->_sections['rowsec']['max'] = $this->_sections['rowsec']['loop'];
$this->_sections['rowsec']['step'] = 1;
$this->_sections['rowsec']['start'] = $this->_sections['rowsec']['step'] > 0 ? 0 : $this->_sections['rowsec']['loop']-1;
if ($this->_sections['rowsec']['show']) {
    $this->_sections['rowsec']['total'] = $this->_sections['rowsec']['loop'];
    if ($this->_sections['rowsec']['total'] == 0)
        $this->_sections['rowsec']['show'] = false;
} else
    $this->_sections['rowsec']['total'] = 0;
if ($this->_sections['rowsec']['show']):

            for ($this->_sections['rowsec']['index'] = $this->_sections['rowsec']['start'], $this->_sections['rowsec']['iteration'] = 1;
                 $this->_sections['rowsec']['iteration'] <= $this->_sections['rowsec']['total'];
                 $this->_sections['rowsec']['index'] += $this->_sections['rowsec']['step'], $this->_sections['rowsec']['iteration']++):
$this->_sections['rowsec']['rownum'] = $this->_sections['rowsec']['iteration'];
$this->_sections['rowsec']['index_prev'] = $this->_sections['rowsec']['index'] - $this->_sections['rowsec']['step'];
$this->_sections['rowsec']['index_next'] = $this->_sections['rowsec']['index'] + $this->_sections['rowsec']['step'];
$this->_sections['rowsec']['first']      = ($this->_sections['rowsec']['iteration'] == 1);
$this->_sections['rowsec']['last']       = ($this->_sections['rowsec']['iteration'] == $this->_sections['rowsec']['total']);
?>
	
	

	<?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['is_editable'] == 1): ?>	
	<tr align="left" valign="top">
		<td width="15%" ><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['name']; ?>
</td>
		<td width="*" ><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['descr']; ?>
<br>
		
           <input class="gydex_control" type="checkbox" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_shown" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_shown" value="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['is_visible'] == 1): ?>checked<?php endif; ?> ><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_shown">использовать на сайте</label>
                  
                  
         
         
        </td>	
		<td width="80" >
        	   <a class="gydex_edit" title="правка" href="<?php echo $this->_tpl_vars['filename']; ?>
?doLang=1&action=1&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&from=<?php echo $this->_tpl_vars['fromno']; ?>
&to_page=<?php echo $this->_tpl_vars['topage']; ?>
"></a> 
          
          <a class="gydex_del" title="удалить запись" href="<?php echo $this->_tpl_vars['filename']; ?>
?action=2&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&from=<?php echo $this->_tpl_vars['fromno']; ?>
&to_page=<?php echo $this->_tpl_vars['topage']; ?>
" onclick="return window.confirm('ВНИМАНИЕ!!! Вы действительно хотите удалить данную запись из списка?');"></a>
         
         
        
        
         
		</td>
		<td width="24" align="center" >
			  
           <input name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_do_process" id="do_process_<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
" type="checkbox"  value="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
" />
			 
		</td>
	</tr>
	<?php else: ?>
	<!-- простой итем не редактируемый -->
	<tr align="left" valign="top">
		<td width="15%" ><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['name']; ?>
</td>
		<td width="*"  ><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['descr']; ?>
</td>	
		<td width="80" >
			-
		</td>
		<td width="24" >
			-
		</td>
	</tr>	
	<?php endif; ?>
		
	
<?php endfor; endif; ?>
</table>
<?php echo $this->_tpl_vars['pages']; ?>


<p>Выберите желаемое действие над выбранными позициями:<br>
		<select name="kind" id="kind">
	<option value="1" SELECTED>Внести изменения</option>
	
	<option value="2">Удалить из базы</option>
	<option value="4">Сделать отображаемыми на сайте</option>
	<option value="5">Сделать невидимыми на сайте</option>
	
</select>&nbsp;&nbsp;&nbsp;


  
<P><input type="submit" name="Update" id="Update" value="Внести изменения!" onclick="return window.confirm('ВНИМАНИЕ!!! Вы уверены, что хотите произвести выбранное действие над группой записей?');">
</form>