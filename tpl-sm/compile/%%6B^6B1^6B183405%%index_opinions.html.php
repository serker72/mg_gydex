<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:43
         compiled from index_opinions.html */ ?>
	<div id="clients_opinion">
    	<div id="clients_opinion_inner"
        
         class="cycle-slideshow"
        data-cycle-fx="scrollHorz"
  	data-cycle-timeout="10000"
    data-cycle-speed="500"
  	data-cycle-slides="> div.slide_clients_opinion"
    data-cycle-pager=".co-pager"
    data-cycle-pager-template=" <a href='#' class='co-pager-button'>&nbsp;</a> ">
        	 
             <div class="co-pager"></div>
        	 <?php $_from = $this->_tpl_vars['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
             <div class="slide_clients_opinion">
            	<h2> лиенты о нас</h2>
                
                <div class="opinion"><?php echo $this->_tpl_vars['item']['txt']; ?>
</div>
                <div class="sign"><?php echo $this->_tpl_vars['item']['name']; ?>
</div>
             
             </div>
             <?php endforeach; endif; unset($_from); ?>
             
              
        
    	</div>
    </div>