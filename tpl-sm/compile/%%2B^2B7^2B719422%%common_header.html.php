<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from common_header.html */ ?>





	<div id="header">
    	<div id="header_contacts">
             <?php echo $this->_tpl_vars['text1']; ?>
<?php echo $this->_tpl_vars['text2']; ?>

            <div class="clr"></div>
        </div>
        
        
        <div id="header_login">
                 
            
			<a class="call" href="/contacts/?do_callback">Заказать обратный звонок</a> 
        
        	<a href="#" class="login">Войти</a>
            <div class="line"></div>
        	<a href="/register.php" class="register">Зарегистрироваться</a>
        	<div class="clr"></div>
        </div>
        <div class="clr"></div>
        
        
    	<div id="header_logo">
       	 <a href="/"><img src="/images/gydex_logo.png" width="197" height="62" alt="GYDEX" /></a> 
        </div>
        
        <div id="header_menu">
        	<ul class="menu">
            <?php unset($this->_sections['hmenusec']);
$this->_sections['hmenusec']['name'] = 'hmenusec';
$this->_sections['hmenusec']['loop'] = is_array($_loop=$this->_tpl_vars['hmenu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['hmenusec']['show'] = true;
$this->_sections['hmenusec']['max'] = $this->_sections['hmenusec']['loop'];
$this->_sections['hmenusec']['step'] = 1;
$this->_sections['hmenusec']['start'] = $this->_sections['hmenusec']['step'] > 0 ? 0 : $this->_sections['hmenusec']['loop']-1;
if ($this->_sections['hmenusec']['show']) {
    $this->_sections['hmenusec']['total'] = $this->_sections['hmenusec']['loop'];
    if ($this->_sections['hmenusec']['total'] == 0)
        $this->_sections['hmenusec']['show'] = false;
} else
    $this->_sections['hmenusec']['total'] = 0;
if ($this->_sections['hmenusec']['show']):

            for ($this->_sections['hmenusec']['index'] = $this->_sections['hmenusec']['start'], $this->_sections['hmenusec']['iteration'] = 1;
                 $this->_sections['hmenusec']['iteration'] <= $this->_sections['hmenusec']['total'];
                 $this->_sections['hmenusec']['index'] += $this->_sections['hmenusec']['step'], $this->_sections['hmenusec']['iteration']++):
$this->_sections['hmenusec']['rownum'] = $this->_sections['hmenusec']['iteration'];
$this->_sections['hmenusec']['index_prev'] = $this->_sections['hmenusec']['index'] - $this->_sections['hmenusec']['step'];
$this->_sections['hmenusec']['index_next'] = $this->_sections['hmenusec']['index'] + $this->_sections['hmenusec']['step'];
$this->_sections['hmenusec']['first']      = ($this->_sections['hmenusec']['iteration'] == 1);
$this->_sections['hmenusec']['last']       = ($this->_sections['hmenusec']['iteration'] == $this->_sections['hmenusec']['total']);
?>
            <?php unset($this->_sections['hmenusec1']);
$this->_sections['hmenusec1']['name'] = 'hmenusec1';
$this->_sections['hmenusec1']['loop'] = is_array($_loop=$this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['subs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['hmenusec1']['show'] = true;
$this->_sections['hmenusec1']['max'] = $this->_sections['hmenusec1']['loop'];
$this->_sections['hmenusec1']['step'] = 1;
$this->_sections['hmenusec1']['start'] = $this->_sections['hmenusec1']['step'] > 0 ? 0 : $this->_sections['hmenusec1']['loop']-1;
if ($this->_sections['hmenusec1']['show']) {
    $this->_sections['hmenusec1']['total'] = $this->_sections['hmenusec1']['loop'];
    if ($this->_sections['hmenusec1']['total'] == 0)
        $this->_sections['hmenusec1']['show'] = false;
} else
    $this->_sections['hmenusec1']['total'] = 0;
if ($this->_sections['hmenusec1']['show']):

            for ($this->_sections['hmenusec1']['index'] = $this->_sections['hmenusec1']['start'], $this->_sections['hmenusec1']['iteration'] = 1;
                 $this->_sections['hmenusec1']['iteration'] <= $this->_sections['hmenusec1']['total'];
                 $this->_sections['hmenusec1']['index'] += $this->_sections['hmenusec1']['step'], $this->_sections['hmenusec1']['iteration']++):
$this->_sections['hmenusec1']['rownum'] = $this->_sections['hmenusec1']['iteration'];
$this->_sections['hmenusec1']['index_prev'] = $this->_sections['hmenusec1']['index'] - $this->_sections['hmenusec1']['step'];
$this->_sections['hmenusec1']['index_next'] = $this->_sections['hmenusec1']['index'] + $this->_sections['hmenusec1']['step'];
$this->_sections['hmenusec1']['first']      = ($this->_sections['hmenusec1']['iteration'] == 1);
$this->_sections['hmenusec1']['last']       = ($this->_sections['hmenusec1']['iteration'] == $this->_sections['hmenusec1']['total']);
?><?php endfor; endif; ?>
             <!-- hmenu item -->
                <li class="<?php if ($this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['is_current']): ?>current<?php endif; ?>"><a href="<?php echo $this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['item_url']; ?>
"  class="<?php if ($this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['is_current']): ?>current<?php endif; ?> <?php if ($this->_sections['hmenusec1']['total'] > 0): ?>nested<?php endif; ?>"><?php echo $this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['name']; ?>
</a>
                	<?php if ($this->_sections['hmenusec1']['total'] > 0): ?>
                    <ul>
                     <?php $_from = $this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['subs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itm']):
?>
                 		<li  class="<?php if ($this->_tpl_vars['itm']['is_current']): ?>current<?php endif; ?> "><a href="<?php echo $this->_tpl_vars['itm']['item_url']; ?>
"  class="<?php if ($this->_tpl_vars['itm']['is_current']): ?>current<?php endif; ?> " ><?php echo $this->_tpl_vars['itm']['name']; ?>
</a></li>
                     <?php endforeach; endif; unset($_from); ?>
                    </ul>
                    <?php endif; ?>
                </li>
            <!-- endof hmenu item -->
        
        	<?php endfor; endif; ?>
         	</ul>
        
   	  		
        </div>
   		<div class="clr"></div>
        
        
  </div>


 