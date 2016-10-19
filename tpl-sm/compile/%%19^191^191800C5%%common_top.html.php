<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from common_top.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'common_top.html', 8, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
    <?php echo $this->_tpl_vars['metalang']; ?>

    
    <?php if ($this->_tpl_vars['canonical']): ?>
    <link rel="canonical" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['canonical'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
    <?php endif; ?>
    
	<meta name="description" content="<?php echo $this->_tpl_vars['description']; ?>
">
	<meta name="keywords" content="<?php echo $this->_tpl_vars['keywords']; ?>
">
    <?php if ($this->_tpl_vars['do_follow'] == 1 && $this->_tpl_vars['do_index'] == 1): ?>
    <meta name="robots" content="all" />
     <?php elseif ($this->_tpl_vars['do_follow'] == 0 && $this->_tpl_vars['do_index'] == 0): ?>
    <meta name="robots" content="noindex, nofollow" />
    <?php elseif ($this->_tpl_vars['do_follow'] == 0 && $this->_tpl_vars['do_index'] == 1): ?>
    <meta name="robots" content="nofollow" />
    <?php elseif ($this->_tpl_vars['do_index'] == 0 && $this->_tpl_vars['do_follow'] == 1): ?>
    <meta name="robots" content="noindex" />
   <?php endif; ?>
  	<title><?php echo $this->_tpl_vars['SITETITLE']; ?>
</title>
    <script language="JavaScript" src="/js/gen_validatorv31.js" type="text/javascript"></script>
    
    
    <link href="/style.css?v=1.114" rel="stylesheet" type="text/css" />
    <link href="/menu.css?v=1.4" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/js/menu.js?v=1.3"></script>
    <script type="text/javascript" src="/js/jquery.cycle2.min.js"></script>
    <script type="text/javascript" src="/js/jquery.cycle2.carousel.min.js"></script>
 
  	<script type="text/javascript" src="/js/popup.js?v=1.2"></script>
     
   
   	<script type="text/javascript" src="/js/select2/select2.min.js"></script> 
	<script type="text/javascript" src="/js/select2/select2_locale_ru.js"></script>

	<link href="/js/select2/select2.css" rel="stylesheet"/>

	<link rel="icon" href="/favicon3.ico" type="image/x-icon" />

    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-22367784-2', 'auto');
  ga('send', 'pageview');

</script>

</head>

<body>


<div id="wrapper">


    <div id="popup_login" class="hidden" style="">
    	<div id="popup_login_window" class="hidden" style="">
                            
        	
            <a href="#" class="close_login"></a>
            
             <div class="popup_header"></div>       
             
             
            <h2 style="margin-bottom:10px; margin-top:10px;">Вход на сайт</h2>                        
            <br clear="all" />
    
            <div id="login_message" class="req"></div>
                                       
                                
    	</div>
	</div>       



    <div id="popup_login1" class="hidden" style="">
    	<div id="popup_login_window1" class="hidden" style="">
        	
        	<a href="#" class="close_login1"></a>
            <div class="popup_header"></div>
            

            <h2 style="margin-bottom:10px; margin-top:10px;">Вход на сайт</h2>
			 
            <form id="logform">
	           	<!-- <input style="display:none" name="fakeLogin" type="text">-->
                <input style="display:none" name="fakePassword" type="password">

         		
                <label for="login">Логин:</label><br />

                <input type="text" id="login" name="login" value="" size="20" maxlength="255"   />
                <p />
				
                 <label for="password">Пароль:</label><br />
                <input type="password" id="password" name="password" value="" size="20" maxlength="255"   autocomplete="off"  /><p />
				
                <div style="float:left;">
                <input type="checkbox" id="remember" name="remember" value="1" checked="checked" /><label for="remember">Запомнить меня</label>
                </div>
                 
                <div style="float:right;"> 
                 <a href="/restore.php" rel="nofollow">Я забыл пароль</a></li>
                 </div>
                 <div class="clr"></div>
                
                
                <input type="submit" id="log" class="button" value="Войти" />
               
                 
         	</form>              
	    </div>     
    </div> 


	<div id="popup_feedback" class="hidden" style="">
    	<div id="popup_feedback_window" class="hidden" style="">
        	 <a href="#" class="close_feedback"></a>
        	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "feedback.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
    </div>    



	<div id="popup_comment" class="hidden" style="">
    	<div id="popup_comment_window" class="hidden" style="">
        	 <a href="#" class="close_comment"></a>
        	
                     <h2>Оставьте комментарий:</h2><br />
<br />
 

                 <form id="review_send">
               
                     
                   
                     <label for="clinic_name">Ваше имя*:</label><br> 
                     <input type="text" value="" id="clinic_name" size="30" maxlength="128">
                     <p>
                     
                     <label for="clinic_email">Ваша электронная почта*<br />
 <small>не публикуется</small>:</label><br> 
                    
              
                     <input type="text" value="" id="clinic_email" size="30" maxlength="128">
                     <p>
                      
                     
                     <label for="clinic_review_txt">Комментарий*:</label><br>
                     <textarea id="clinic_review_txt" cols="50" rows="7"></textarea>
                     
                     <p>
                     
                    <small><em>Поля, отмеченные <span class="dis_orange">*</span>, обязательны для заполнения.</em></small>
<br />
              
                     <input type="submit" value="Отправить комментарий" id="clinic_review_send" >
                     
                </form>
        </div>
    </div>   
    
    
    
    <div id="popup_callback" class="hidden" style="">
    	<div id="popup_callback_window" class="hidden" style="">
        	 <a href="#" class="close_callback"></a>
        	        </div>
    </div>    





<!-- header -->
<?php echo $this->_tpl_vars['header']; ?>

<!-- endof header -->

<!--mmenu -->
<?php echo $this->_tpl_vars['hmenu1']; ?>

<!--end of mmenu -->

 
	
<?php echo $this->_tpl_vars['left']; ?>


 
	<?php echo $this->_tpl_vars['navi']; ?>


