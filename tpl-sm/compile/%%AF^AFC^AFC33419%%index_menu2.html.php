<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:43
         compiled from index_menu2.html */ ?>
  <div id="icontest">
    	
        <div id="icontest_slider"
       				class="cycle-slideshow"
           			 
                    data-cycle-slides="> div.icon"
                    data-cycle-fx="carousel"
    				data-cycle-timeout="5000"
    			 
        >
            <?php $_from = $this->_tpl_vars['mmenu2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['m2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['m2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['m2']['iteration']++;
?>
            <div class="icon">
                <a href="<?php echo $this->_tpl_vars['item']['url']; ?>
" class="icon" style="background-image:url(/<?php echo $this->_tpl_vars['item']['photo_small']; ?>
);"><?php if ($this->_tpl_vars['item']['name_2'] != ""): ?><?php echo $this->_tpl_vars['item']['name_2']; ?>
<?php else: ?><?php echo $this->_tpl_vars['item']['name']; ?>
<?php endif; ?></a>
                <a href="<?php echo $this->_tpl_vars['item']['url']; ?>
" class="iconmore">Подробнее</a>
            </div>
            <?php endforeach; endif; unset($_from); ?>
            
            <div class="cycle-prev"></div>
    		<div class="cycle-next"></div>
        </div>
        
        
        <div class="clr"></div>
    	
        <div class="icontest_text">
        	
        	 <?php echo $this->_tpl_vars['text']; ?>

             <div class="clr"></div>
        </div>
        
    </div>