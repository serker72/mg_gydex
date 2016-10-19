<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:40
         compiled from vmenu2.html */ ?>

 			<ul>
            	<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
        	    <li><a href="<?php echo $this->_tpl_vars['item']['item']['url']; ?>
" <?php if ($this->_tpl_vars['item']['item']['is_active'] == true): ?>class="opened"<?php endif; ?>><?php echo $this->_tpl_vars['item']['item']['name']; ?>
</a>
               		
                   <?php $_from = $this->_tpl_vars['item']['subs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sub'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sub']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sub']):
        $this->_foreach['sub']['iteration']++;
?><?php endforeach; endif; unset($_from); ?> 
                   <?php if ($this->_foreach['sub']['total'] > 0): ?>
                   <ul>
                   <?php $_from = $this->_tpl_vars['item']['subs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sub'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sub']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sub']):
        $this->_foreach['sub']['iteration']++;
?>
                    <li>
                        <a <?php if ($this->_tpl_vars['sub']['item']['is_active'] == true): ?>class="active"<?php endif; ?> href="<?php echo $this->_tpl_vars['sub']['item']['url']; ?>
" ><?php echo $this->_tpl_vars['sub']['item']['name']; ?>
</a>
                    </li>
                   <?php endforeach; endif; unset($_from); ?>  
                      
                   </ul>
                   <?php endif; ?> 
                 </li>
                  <?php endforeach; endif; unset($_from); ?>
             </ul>    
 