<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from navi.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'navi.html', 7, false),)), $this); ?>
<div class="navitxt" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
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
<?php if (( $this->_sections['itemsec']['last'] && $this->_tpl_vars['has_last'] ) || ! $this->_sections['itemsec']['last']): ?>
<a href="<?php echo $this->_tpl_vars['items'][$this->_sections['itemsec']['index']]['filepath']; ?>
" class="navilink" itemprop="url">
<?php endif; ?>
<span itemprop="title">
<?php echo ((is_array($_tmp=$this->_tpl_vars['items'][$this->_sections['itemsec']['index']]['itemname'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>

</span>
<?php if (( $this->_sections['itemsec']['last'] && $this->_tpl_vars['has_last'] ) || ! $this->_sections['itemsec']['last']): ?>
</a> 
<?php endif; ?>

<?php if ($this->_tpl_vars['items'][$this->_sections['itemsec']['index']]['has_symb']): ?><div class="navilink"><?php echo $this->_tpl_vars['items'][$this->_sections['itemsec']['index']]['symb']; ?>
</div><?php endif; ?>

<?php endfor; endif; ?>

<?php echo $this->_tpl_vars['aftertext']; ?>


</div>