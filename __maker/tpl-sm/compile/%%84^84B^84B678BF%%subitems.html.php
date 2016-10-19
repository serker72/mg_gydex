<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:40
         compiled from subitems.html */ ?>
<!-- список подразделов в разделе -->

 

<!-- форма разбивки списка итемов по Х штук на страницу -->

	<form action="<?php echo $this->_tpl_vars['listpagename']; ?>
" name="topa" id="topa">
		<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['itemno']; ?>
">
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
<input type="hidden" name="id" id="id" value="<?php echo $this->_tpl_vars['itemno']; ?>
">
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
              <th width="*"> URL </th>
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
		  
	<!-- подраздел в разделе -->
	
	<tr align="left" valign="top">
		<td width="15%">
        <a name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
"></a>
		<?php unset($this->_sections['namesec']);
$this->_sections['namesec']['name'] = 'namesec';
$this->_sections['namesec']['loop'] = is_array($_loop=$this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['namesec']['show'] = true;
$this->_sections['namesec']['max'] = $this->_sections['namesec']['loop'];
$this->_sections['namesec']['step'] = 1;
$this->_sections['namesec']['start'] = $this->_sections['namesec']['step'] > 0 ? 0 : $this->_sections['namesec']['loop']-1;
if ($this->_sections['namesec']['show']) {
    $this->_sections['namesec']['total'] = $this->_sections['namesec']['loop'];
    if ($this->_sections['namesec']['total'] == 0)
        $this->_sections['namesec']['show'] = false;
} else
    $this->_sections['namesec']['total'] = 0;
if ($this->_sections['namesec']['show']):

            for ($this->_sections['namesec']['index'] = $this->_sections['namesec']['start'], $this->_sections['namesec']['iteration'] = 1;
                 $this->_sections['namesec']['iteration'] <= $this->_sections['namesec']['total'];
                 $this->_sections['namesec']['index'] += $this->_sections['namesec']['step'], $this->_sections['namesec']['iteration']++):
$this->_sections['namesec']['rownum'] = $this->_sections['namesec']['iteration'];
$this->_sections['namesec']['index_prev'] = $this->_sections['namesec']['index'] - $this->_sections['namesec']['step'];
$this->_sections['namesec']['index_next'] = $this->_sections['namesec']['index'] + $this->_sections['namesec']['step'];
$this->_sections['namesec']['first']      = ($this->_sections['namesec']['iteration'] == 1);
$this->_sections['namesec']['last']       = ($this->_sections['namesec']['iteration'] == $this->_sections['namesec']['total']);
?>
		

		<?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['is_exist'] == 1): ?>
		<!-- существует -->
			<?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['is_vloj'] == 1): ?>
			<!-- открываемые подразделы -->
				<a href="razds.php?id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['name']; ?>
&nbsp;(<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['sub_count']; ?>
)</a> <br>
			<?php else: ?>
			<!-- блокированное название -->
				<b><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['name']; ?>
</b><br>	
			<?php endif; ?>
			
			<a class="gydex_edit_lang" title="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" href="ed_razd.php?doLang=1&action=1&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&from=<?php echo $this->_tpl_vars['fromno']; ?>
&to_page=<?php echo $this->_tpl_vars['topage']; ?>
&lang_id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_id']; ?>
"><img src="/<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_flag']; ?>
" alt="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" title="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" border="0"></a> 
			 

			      <a class="gydex_edit_html"  href="ed_razd.php?doLang=1&nonvisual=1&action=1&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&from=<?php echo $this->_tpl_vars['fromno']; ?>
&to_page=<?php echo $this->_tpl_vars['topage']; ?>
&lang_id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_id']; ?>
"><img src="/<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_flag']; ?>
" alt="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" title="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" border="0"></a>
					
                  <br>
                    
                  <input class="gydex_control" type="checkbox" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_id']; ?>
_is_shown" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_id']; ?>
_is_shown" value="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_shown']; ?>
" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_shown'] == 1): ?>checked<?php endif; ?> ><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_id']; ?>
_is_shown">видим</label>
                  
                  <br>

		<?php else: ?>
		<!-- не создано -->
			
			<?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['is_vloj'] == 1): ?>
			<!-- открываемые подразделы -->
				<a href="razds.php?id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
">не создано</a><br> 
			<?php else: ?>
			<!-- блокированное название -->
				<b><em>не создано</em></b><br>	
			<?php endif; ?>
			
			<a class="gydex_edit_lang" title="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" href="ed_razd.php?doLang=1&action=1&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&from=<?php echo $this->_tpl_vars['fromno']; ?>
&to_page=<?php echo $this->_tpl_vars['topage']; ?>
&lang_id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_id']; ?>
"><img src="/<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_flag']; ?>
" alt="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" title="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" border="0"></a> 
			

			      <a class="gydex_edit_html"  href="ed_razd.php?doLang=1&nonvisual=1&action=1&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&from=<?php echo $this->_tpl_vars['fromno']; ?>
&to_page=<?php echo $this->_tpl_vars['topage']; ?>
&lang_id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_id']; ?>
"><img src="/<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_flag']; ?>
" alt="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" title="правка на языке <?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['nameitems'][$this->_sections['namesec']['index']]['lang_name']; ?>
" border="0"></a>
					
                  <br>
                    
		<?php endif; ?>
		
		
		
		<?php endfor; endif; ?>
		
		</td>
		<td width="*">
		<!-- параметры раздела -->
		             
             
             
        	<input class="gydex_control" type="radio" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_usl" value="0" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_0_usl" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_price'] == 0 && $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_news'] == 0 && $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_links'] == 0): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_0_usl"><img src="/img/newdis/modules/simple18.png" width="18" height="18" />обычный раздел</label><br>
            	
             <?php if ($this->_tpl_vars['HAS_PRICE']): ?>
             <input class="gydex_control" type="radio" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_usl" value="1" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_1_usl"  <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_price'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_1_usl"><img src="/img/newdis/modules/price18.png" width="18" height="18" />содержит <a href="viewpriceitems.php?id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
" class="gydex_link">товары (<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['count_of_goods']; ?>
)</a></label><br>  
              	 <div class="indent">
                 <?php if ($this->_tpl_vars['HAS_BASKET']): ?> 
                 
                 	<input class="gydex_control" type="checkbox" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_basket" value="1" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_basket" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_basket'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_basket"><img src="/img/newdis/modules/basket18.png" width="18" height="18" />можно заказывать товар</label><br>
                    
                    
                 <?php endif; ?> 
                 
                 
                 
                  <a href="eddicts.php?kind=1&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&mode=1" target="_blank" class="gydex_link gydex_link_link"><img src="/img/newdis/modules/props18.png" width="18" height="18" /> Словари свойств раздела</a> <br>
                  
                  
                  <a href="pricestogood.php?value_id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&mode=1" target="_blank" class="gydex_link gydex_link_link"><img src="/img/newdis/modules/prices18.png" width="18" height="18" /> Цены, применяемые в разделе</a> <br>
                 
                 
                 
                 </div>
            
             <?php endif; ?>
             
              <?php if ($this->_tpl_vars['HAS_NEWS']): ?>
             <input class="gydex_control" type="radio" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_usl" value="2" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_2_usl" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_news'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_2_usl"><img src="/img/newdis/modules/links18.png" width="18" height="18" />содержит <a href="viewnews.php?id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
" class="gydex_link">новости (<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['count_of_news']; ?>
)</a></label><br>
             <?php endif; ?>
             
             
             <?php if ($this->_tpl_vars['HAS_LINKS']): ?>
             <input class="gydex_control" type="radio" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_usl" value="3" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_3_usl" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_links'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_3_usl"><img src="/img/newdis/modules/news18.png" width="18" height="18" />содержит <a href="viewlinks.php?id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
" class="gydex_link">каталог ссылок (<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['count_of_links']; ?>
)</a></label><br>
             <?php endif; ?>
             
             
 
                <?php if ($this->_tpl_vars['HAS_PAPERS']): ?>        
               <input class="gydex_control" type="checkbox" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_papers" value="1" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_papers" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_papers'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_papers"><img src="/img/newdis/modules/papers18.png" width="18" height="18" />содержит <a href="viewpapers.php?id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
" class="gydex_link">статьи (<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['count_of_papers']; ?>
)</a></label><br>
               <?php endif; ?>
               
               
                <?php if ($this->_tpl_vars['HAS_GALLERY']): ?>        
               <input class="gydex_control" type="checkbox" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_gallery" value="1" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_gallery" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_gallery'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_gallery"><img src="/img/newdis/modules/photos18.png" width="18" height="18" />содержит <a href="viewphotos.php?id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
" class="gydex_link">фотогалерею (<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['count_of_photos']; ?>
)</a></label><br>
               <?php endif; ?>
               
                  <?php if ($this->_tpl_vars['HAS_FEEDBACK_FORMS']): ?> 
                    <input class="gydex_control" type="checkbox" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_feedback_forms" value="1" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_feedback_forms" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_feedback_forms'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_otzyv"><img src="/img/newdis/modules/feedback18.png" width="18" height="18" />содержит форму заказа</label><br>
                  
                    <input class="gydex_control" type="checkbox" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_feedback_service" value="1" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_feedback_service" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_feedback_service'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_otzyv"><img src="/img/newdis/modules/feedback18.png" width="18" height="18" />содержит форму заявки на сервис</label><br>
                  
                           
                <input class="gydex_control" type="checkbox" name="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_otzyv" value="1" id="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_otzyv" <?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['data']['is_otzyv'] == 1): ?> checked="checked" <?php endif; ?> /><label for="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
_is_otzyv"><img src="/img/newdis/modules/feedback18.png" width="18" height="18" />содержит <a href="viewotzyv.php" class="gydex_link">отзывы</a></label><br>

					<?php endif; ?>
		
		</td>	
		<td width="*">
		<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['url']; ?>

		</td>
		<td width="80" >
        
        	
           <a class="gydex_up" title="увеличить порядок показа" href="<?php echo $this->_tpl_vars['filename']; ?>
?action=1&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&from=<?php echo $this->_tpl_vars['fromno']; ?>
&to_page=<?php echo $this->_tpl_vars['topage']; ?>
&changeOrd=0"></a>
          <a class="gydex_down" title="уменьшить порядок показа" href="<?php echo $this->_tpl_vars['filename']; ?>
?action=1&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
&from=<?php echo $this->_tpl_vars['fromno']; ?>
&to_page=<?php echo $this->_tpl_vars['topage']; ?>
&changeOrd=1"></a>
          
          
          <a class="gydex_del" title="удалить запись" href="ed_razd.php?action=2&id=<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['id']; ?>
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
	
<?php endfor; endif; ?>
	 </tbody>
     </table>

<?php echo $this->_tpl_vars['pages']; ?>




 <p>Выберите желаемое действие над выбранными позициями:<br>
          <select name="kind" id="kind">
              <option value="1" SELECTED>Внести изменения</option>
              
              <option value="2">Удалить из базы</option>
              <option value="4">Сделать отображаемыми на сайте</option>
              <option value="5">Сделать невидимыми на сайте</option>
              
          </select>&nbsp;&nbsp;&nbsp;
          
          
          
          <p><input type="submit" name="Update" id="Update" value="Внести изменения!" onclick="return window.confirm('ВНИМАНИЕ!!! Вы уверены, что хотите произвести выбранное действие над группой записей?');">
          </form>