<?php /* Smarty version 2.6.22, created on 2016-10-19 15:47:11
         compiled from razd/page_contacts.html */ ?>

<div id="page_slider-wrapper">   
	<div id="page_slider"> 
        <div class="heading">
        <?php echo $this->_tpl_vars['mm']['name']; ?>

        </div>
    
        <div class="navi">
        <?php echo $this->_tpl_vars['navi']; ?>

        </div>
    
    </div>

</div>


<?php echo $this->_tpl_vars['clients']; ?>



<div id="page_text">
	<div style="float:left; margin-right:20px;">
     	<div itemscope itemtype="http://schema.org/Organization">
       <span itemprop="name"> <?php echo $this->_tpl_vars['mm']['txt']; ?>
</span>
        
        <?php echo $this->_tpl_vars['mm']['txt2']; ?>

        
         
            
        <?php echo $this->_tpl_vars['mm']['txt3']; ?>

        </div>
    </div>
    
    <?php if ($this->_tpl_vars['has_callback_form']): ?>
      <!--<div style="float:left; margin-left:90px;"><br>
<br>
<a class="button" id="callback_caller" href="#">Заказать обратный звонок</a></div>
		<?php if ($this->_tpl_vars['do_callback']): ?>
        <script type="text/javascript">
		$(function(){
			$("#callback_caller").trigger("click");
		});
		</script>
        <?php endif; ?>
    <?php endif; ?>
    -->
    
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "callback.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    
    
    
    
    
     <?php echo $this->_tpl_vars['content']; ?>


	 <?php echo $this->_tpl_vars['faq']; ?>


	<div class="clr"></div>
    
     

</div>

 