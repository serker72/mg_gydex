<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:44
         compiled from index_clients.html */ ?>
<div id="our_clients">
    	<div id="our_clients_inner">
	        <h2>Ќаши клиенты</h2>
            
            <div align="center">Ќаши успешные клиенты, которые уже воспользовались системой GYDEX.</div>
            
                <div id="our_clients_slider"  class="cycle-slideshow"
           			 
                    data-cycle-slides="> div.our_clients_slide"
                    data-cycle-fx="carousel"
    				data-cycle-timeout="5000"
    			 
    
           data-cycle-pager=".oc-pager"
           data-cycle-pager-template=" <a href='#' class='oc-pager-button'>&nbsp;</a> "
           
           >
                 <?php $_from = $this->_tpl_vars['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?> 
                 <div class="our_clients_slide">
                   <a href="<?php echo $this->_tpl_vars['item']['page_url']; ?>
" target="_blank" style=" display:block; width:200px; height:200px; background-image:url(/<?php echo $this->_tpl_vars['item']['image_big']; ?>
); background-position:center center; background-repeat:no-repeat;"></a>                  
                 </div>
                 <?php endforeach; endif; unset($_from); ?>
                  
            
            </div>
            <div class="oc-pager"></div>
            
        </div>
    
    </div>