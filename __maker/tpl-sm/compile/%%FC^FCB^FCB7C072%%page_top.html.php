<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:40
         compiled from page_top.html */ ?>
<!doctype html>
<html>
<head>
<meta charset="windows-1251">
<title><?php echo $this->_tpl_vars['SITETITLE']; ?>
</title>

<link href="newstyle.css?v=1.4" rel="stylesheet" type="text/css" />
<link href="menu.css?v=1.1" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="swfuploader.css" type="text/css">
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script src="js/gen_validatorv4.js" type="text/javascript"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="js/funcs.js" type="text/javascript"></script>
<script src="js/menu.js" type="text/javascript"></script>

<link href="/js/ui/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="/js/ui/jquery-ui.min.js"></script>

<link rel="icon" href="/favicon3.ico" type="image/x-icon" />
</head>

<body>

<div id="gydex_wrapper">

<!-- header here -->
	
    <div id="gydex_header">
    	<div id="gydex_logo">
   	    	<a href="/__maker/"><img src="/img/newdis/logo.jpg" width="204" height="75" /></a> 
        </div>
         
        <?php echo $this->_tpl_vars['HMENU1']; ?>
 
        
        <div class="clr"></div>
        

   </div>
	
    
    
    
    
<!-- main here -->    
    
  <div id="gydex_main_wrapper">
    	<div id="gydex_left_col">
        	 <?php echo $this->_tpl_vars['VMENU1']; ?>

              
	  </div>
        <div id="gydex_main">
        		
           <div id="dialog_help" title="Справка" style="display:none;">
          		<div id="dialog_help_inner"></div>
          </div>  
          
          
          
           <?php echo $this->_tpl_vars['MODULE_HMENU']; ?>

          
          
          
          <?php $_from = $this->_tpl_vars['context']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cont'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cont']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['cont']['iteration']++;
?><?php endforeach; endif; unset($_from); ?>
          <?php if ($this->_foreach['cont']['total'] > 0): ?>
          <div id="gydex_context_wrapper">
          	
          	 
              <div id="gydex_context_left">
              
              <?php if ($this->_tpl_vars['context_caption'] == false): ?>
              Доступные команды
              <?php else: ?>
              <?php echo $this->_tpl_vars['context_caption']; ?>

              <?php endif; ?>
              </div>
              
              
              
              <div id="gydex_context">
          	  <!-- context here -->	  
                  <?php $_from = $this->_tpl_vars['context']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cont'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cont']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['cont']['iteration']++;
?>
                  <div class="gydex_context">	
                  	<a <?php if (! $this->_tpl_vars['item']['item']['is_help']): ?>href="<?php echo $this->_tpl_vars['item']['item']['url']; ?>
"<?php else: ?>href="#" onClick=" OpenHelp('<?php echo $this->_tpl_vars['item']['item']['url']; ?>
'); return false;"<?php endif; ?>><?php echo $this->_tpl_vars['item']['item']['name']; ?>
</a>
                  </div>
                  <?php endforeach; endif; unset($_from); ?>
                   
          		</div>
         	 <!-- context -->
          	</div>
            <?php endif; ?>
             
            
            
        
          	<div id="gydex_breadcrumbs">
            	<?php $_from = $this->_tpl_vars['bc']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['bc'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['bc']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['bc']['iteration']++;
?>
                <?php if (! ($this->_foreach['bc']['iteration'] == $this->_foreach['bc']['total'])): ?>
                <a href="<?php echo $this->_tpl_vars['item']['item']['url']; ?>
"><?php echo $this->_tpl_vars['item']['item']['name']; ?>
</a> > 
                <?php else: ?>
                <?php echo $this->_tpl_vars['item']['item']['name']; ?>

                <?php endif; ?>
                
                <?php endforeach; endif; unset($_from); ?>
                 
          	</div>
          
          
          	<div id="gydex_content">


				