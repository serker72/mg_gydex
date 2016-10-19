<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:39
         compiled from hmenu2.html */ ?>
	<div id="gydex_menu">
        	<ul class="gydex_menu">
            	<li>
                <a href="/" target="_blank"><img src="/img/newdis/home.jpg" width="27" height="27" /></a>
                </li>
            	
                
                <?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                <li>
                	 <?php $_from = $this->_tpl_vars['item']['subs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sub'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sub']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sub']):
        $this->_foreach['sub']['iteration']++;
?><?php endforeach; endif; unset($_from); ?>
                	<a href="<?php echo $this->_tpl_vars['item']['item']['url']; ?>
" <?php if ($this->_foreach['sub']['total'] > 0): ?> class="nested"<?php endif; ?>><?php echo $this->_tpl_vars['item']['item']['name']; ?>
</a>
                    
                   
                    <?php if ($this->_foreach['sub']['total'] > 0): ?>
                    <ul>
                    <?php $_from = $this->_tpl_vars['item']['subs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sub'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sub']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sub']):
        $this->_foreach['sub']['iteration']++;
?>
                    <li>
                        <a href="<?php echo $this->_tpl_vars['sub']['item']['url']; ?>
"><?php echo $this->_tpl_vars['sub']['item']['name']; ?>
</a>
                    </li>
                    <?php endforeach; endif; unset($_from); ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; endif; unset($_from); ?>
               
                
            </ul>
        </div>
        <div id="gydex_login">
        	
            <div class="gydex_user">
            <?php echo $this->_tpl_vars['username']; ?>

            </div>
            
            <a class="gydex_signout" href="usereffects.php?doOut">
            выйти
            </a>
            
            <br />

            
            <div class="gydex_login">
            Логин: <?php echo $this->_tpl_vars['login']; ?>

            </div>
            
            
            
            
            
        </div>