<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from common_left.html */ ?>
<?php if ($this->_tpl_vars['has_slider']): ?>

	<div id="slider-wrapper">    	 
        <div id="slider" class="cycle-slideshow"
        data-cycle-fx="scrollHorz"
  	data-cycle-timeout="10000"
    data-cycle-speed="500"
  	data-cycle-slides="> div.slide"
    data-cycle-pager=".custom-pager"
    data-cycle-pager-template=" <a href='#' class='custom-pager-button'>&nbsp;</a> ">
        	 
             <div class="custom-pager"></div>
             
            
            <div class="cycle-prev"></div>
    		<div class="cycle-next"></div>
        	<?php $_from = $this->_tpl_vars['upbanners']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ban']):
?>
             
            <div class="slide <?php if ($this->_tpl_vars['ban']['align_mode'] == 1): ?> rl <?php endif; ?>" style="background-image:url(/<?php echo $this->_tpl_vars['ban']['photo_small']; ?>
); background-position:<?php if ($this->_tpl_vars['ban']['align_mode'] == 0): ?>left<?php else: ?>right<?php endif; ?> center; background-repeat:no-repeat;">
            	<?php echo $this->_tpl_vars['ban']['small_txt']; ?>

                               
            </div>
            <?php endforeach; endif; unset($_from); ?>
            
            
            
        </div>        
    </div>
<?php endif; ?>
 
    