<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from otz/item.html */ ?>
	 
    	<div id="clients_opinion_inner">
        	 
            
        	  <?php $_from = $this->_tpl_vars['item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
           
             
                
                <div class="opinion"><?php echo $this->_tpl_vars['item']['txt']; ?>
</div>
                <div class="sign"><?php echo $this->_tpl_vars['item']['name']; ?>
</div>
             
             
            
                <?php endforeach; endif; unset($_from); ?>
             
              
        
    	</div>
   