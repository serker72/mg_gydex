<?php /* Smarty version 2.6.22, created on 2016-10-19 17:31:07
         compiled from tree_new_test.html */ ?>
<!-- элемент дерева -->


<div class="accordion">

 
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
	
	 <h3><?php unset($this->_sections['langsec']);
$this->_sections['langsec']['name'] = 'langsec';
$this->_sections['langsec']['loop'] = is_array($_loop=$this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['langs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['langsec']['show'] = true;
$this->_sections['langsec']['max'] = $this->_sections['langsec']['loop'];
$this->_sections['langsec']['step'] = 1;
$this->_sections['langsec']['start'] = $this->_sections['langsec']['step'] > 0 ? 0 : $this->_sections['langsec']['loop']-1;
if ($this->_sections['langsec']['show']) {
    $this->_sections['langsec']['total'] = $this->_sections['langsec']['loop'];
    if ($this->_sections['langsec']['total'] == 0)
        $this->_sections['langsec']['show'] = false;
} else
    $this->_sections['langsec']['total'] = 0;
if ($this->_sections['langsec']['show']):

            for ($this->_sections['langsec']['index'] = $this->_sections['langsec']['start'], $this->_sections['langsec']['iteration'] = 1;
                 $this->_sections['langsec']['iteration'] <= $this->_sections['langsec']['total'];
                 $this->_sections['langsec']['index'] += $this->_sections['langsec']['step'], $this->_sections['langsec']['iteration']++):
$this->_sections['langsec']['rownum'] = $this->_sections['langsec']['iteration'];
$this->_sections['langsec']['index_prev'] = $this->_sections['langsec']['index'] - $this->_sections['langsec']['step'];
$this->_sections['langsec']['index_next'] = $this->_sections['langsec']['index'] + $this->_sections['langsec']['step'];
$this->_sections['langsec']['first']      = ($this->_sections['langsec']['iteration'] == 1);
$this->_sections['langsec']['last']       = ($this->_sections['langsec']['iteration'] == $this->_sections['langsec']['total']);
?><?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['langs'][$this->_sections['langsec']['index']]['id'] == LANG_CODE): ?><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['langs'][$this->_sections['langsec']['index']]['name']; ?>
<?php endif; ?><?php endfor; endif; ?></h3>
	 <div>
	<a href="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['url_into']; ?>
" <?php if ($this->_tpl_vars['in_new']): ?>target="_blank"<?php endif; ?>  ><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['obst_name']; ?>
</a>
	<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['defs']; ?>

	<br>
	
	<?php unset($this->_sections['langsec']);
$this->_sections['langsec']['name'] = 'langsec';
$this->_sections['langsec']['loop'] = is_array($_loop=$this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['langs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['langsec']['show'] = true;
$this->_sections['langsec']['max'] = $this->_sections['langsec']['loop'];
$this->_sections['langsec']['step'] = 1;
$this->_sections['langsec']['start'] = $this->_sections['langsec']['step'] > 0 ? 0 : $this->_sections['langsec']['loop']-1;
if ($this->_sections['langsec']['show']) {
    $this->_sections['langsec']['total'] = $this->_sections['langsec']['loop'];
    if ($this->_sections['langsec']['total'] == 0)
        $this->_sections['langsec']['show'] = false;
} else
    $this->_sections['langsec']['total'] = 0;
if ($this->_sections['langsec']['show']):

            for ($this->_sections['langsec']['index'] = $this->_sections['langsec']['start'], $this->_sections['langsec']['iteration'] = 1;
                 $this->_sections['langsec']['iteration'] <= $this->_sections['langsec']['total'];
                 $this->_sections['langsec']['index'] += $this->_sections['langsec']['step'], $this->_sections['langsec']['iteration']++):
$this->_sections['langsec']['rownum'] = $this->_sections['langsec']['iteration'];
$this->_sections['langsec']['index_prev'] = $this->_sections['langsec']['index'] - $this->_sections['langsec']['step'];
$this->_sections['langsec']['index_next'] = $this->_sections['langsec']['index'] + $this->_sections['langsec']['step'];
$this->_sections['langsec']['first']      = ($this->_sections['langsec']['iteration'] == 1);
$this->_sections['langsec']['last']       = ($this->_sections['langsec']['iteration'] == $this->_sections['langsec']['total']);
?>
		
	<a href="<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['langs'][$this->_sections['langsec']['index']]['url']; ?>
" <?php if ($this->_tpl_vars['in_new']): ?>target="_blank"<?php endif; ?> ><img src="/<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['langs'][$this->_sections['langsec']['index']]['lang_flag']; ?>
" border=0 vspace=0 hspace=2 ><?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['langs'][$this->_sections['langsec']['index']]['name']; ?>
</a> 
	
	<?php endfor; endif; ?>	
	
	
	<?php if ($this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['sub_count'] > 0): ?> 
	<?php echo $this->_tpl_vars['items'][$this->_sections['rowsec']['index']]['subs']; ?>

    <?php endif; ?>
	 
	</div>
	
	<?php endfor; endif; ?>	
 

</div>