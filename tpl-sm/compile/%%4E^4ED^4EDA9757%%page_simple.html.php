<?php /* Smarty version 2.6.22, created on 2016-10-19 15:56:14
         compiled from razd/page_simple.html */ ?>

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
	<?php echo $this->_tpl_vars['content']; ?>

    
    <div class="simple_text">
    <?php echo $this->_tpl_vars['mm']['txt']; ?>

    </div>
    
    <div class="simple_text">
    <?php echo $this->_tpl_vars['mm']['txt2']; ?>

    </div>
    
     
    <div class="simple_text">    
    <?php echo $this->_tpl_vars['mm']['txt3']; ?>

    </div>

	 <?php echo $this->_tpl_vars['faq']; ?>


	<div class="clr"></div>
    
   <?php if (! has_no_slogan): ?> 
   <div class="gydex_works">GYDEX. Просто. Работает!</div>
   <?php endif; ?>
</div>

 