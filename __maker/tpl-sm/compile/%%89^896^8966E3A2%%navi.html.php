<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:10
         compiled from navi.html */ ?>
<div>

<?php unset($this->_sections['itemsec']);
$this->_sections['itemsec']['name'] = 'itemsec';
$this->_sections['itemsec']['loop'] = is_array($_loop=$this->_tpl_vars['items']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['itemsec']['show'] = true;
$this->_sections['itemsec']['max'] = $this->_sections['itemsec']['loop'];
$this->_sections['itemsec']['step'] = 1;
$this->_sections['itemsec']['start'] = $this->_sections['itemsec']['step'] > 0 ? 0 : $this->_sections['itemsec']['loop']-1;
if ($this->_sections['itemsec']['show']) {
    $this->_sections['itemsec']['total'] = $this->_sections['itemsec']['loop'];
    if ($this->_sections['itemsec']['total'] == 0)
        $this->_sections['itemsec']['show'] = false;
} else
    $this->_sections['itemsec']['total'] = 0;
if ($this->_sections['itemsec']['show']):

            for ($this->_sections['itemsec']['index'] = $this->_sections['itemsec']['start'], $this->_sections['itemsec']['iteration'] = 1;
                 $this->_sections['itemsec']['iteration'] <= $this->_sections['itemsec']['total'];
                 $this->_sections['itemsec']['index'] += $this->_sections['itemsec']['step'], $this->_sections['itemsec']['iteration']++):
$this->_sections['itemsec']['rownum'] = $this->_sections['itemsec']['iteration'];
$this->_sections['itemsec']['index_prev'] = $this->_sections['itemsec']['index'] - $this->_sections['itemsec']['step'];
$this->_sections['itemsec']['index_next'] = $this->_sections['itemsec']['index'] + $this->_sections['itemsec']['step'];
$this->_sections['itemsec']['first']      = ($this->_sections['itemsec']['iteration'] == 1);
$this->_sections['itemsec']['last']       = ($this->_sections['itemsec']['iteration'] == $this->_sections['itemsec']['total']);
?>
<a href="<?php echo $this->_tpl_vars['items'][$this->_sections['itemsec']['index']]['filepath']; ?>
"><?php echo $this->_tpl_vars['items'][$this->_sections['itemsec']['index']]['itemname']; ?>
</a>
<?php if ($this->_tpl_vars['items'][$this->_sections['itemsec']['index']]['has_symb']): ?><?php echo $this->_tpl_vars['symb']; ?>
<?php endif; ?>
<?php endfor; endif; ?>

<?php echo $this->_tpl_vars['aftertext']; ?>

</div>