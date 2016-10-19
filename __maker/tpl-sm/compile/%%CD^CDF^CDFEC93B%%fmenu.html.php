<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:40
         compiled from fmenu.html */ ?>

            <?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
            <a href="<?php echo $this->_tpl_vars['item']['item']['url']; ?>
" ><?php echo $this->_tpl_vars['item']['item']['name']; ?>
</a>
            <?php endforeach; endif; unset($_from); ?>            