<?php /* Smarty version 2.6.22, created on 2016-10-19 15:59:28
         compiled from news/page.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'news/page.html', 71, false),)), $this); ?>

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


<div id="page_text">	
    

	

	
	<div class="paper_text">
    
    	<div class="paper_left">
    	
        	<img src="/<?php echo $this->_tpl_vars['news']['photo_small']; ?>
" />
    	</div>
        <div class="paper_common">
        	
            <h1><?php echo $this->_tpl_vars['news']['name']; ?>
</h1>
			<strong><?php echo $this->_tpl_vars['news']['pdate']; ?>
</strong>
            
	    	<div class="simple_text">
            <?php echo $this->_tpl_vars['news']['big_txt']; ?>

            </div>
            <br>

            
            <a name="pictures"></a>
            <?php unset($this->_sections['picsec']);
$this->_sections['picsec']['name'] = 'picsec';
$this->_sections['picsec']['loop'] = is_array($_loop=$this->_tpl_vars['pictures']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['picsec']['show'] = true;
$this->_sections['picsec']['max'] = $this->_sections['picsec']['loop'];
$this->_sections['picsec']['step'] = 1;
$this->_sections['picsec']['start'] = $this->_sections['picsec']['step'] > 0 ? 0 : $this->_sections['picsec']['loop']-1;
if ($this->_sections['picsec']['show']) {
    $this->_sections['picsec']['total'] = $this->_sections['picsec']['loop'];
    if ($this->_sections['picsec']['total'] == 0)
        $this->_sections['picsec']['show'] = false;
} else
    $this->_sections['picsec']['total'] = 0;
if ($this->_sections['picsec']['show']):

            for ($this->_sections['picsec']['index'] = $this->_sections['picsec']['start'], $this->_sections['picsec']['iteration'] = 1;
                 $this->_sections['picsec']['iteration'] <= $this->_sections['picsec']['total'];
                 $this->_sections['picsec']['index'] += $this->_sections['picsec']['step'], $this->_sections['picsec']['iteration']++):
$this->_sections['picsec']['rownum'] = $this->_sections['picsec']['iteration'];
$this->_sections['picsec']['index_prev'] = $this->_sections['picsec']['index'] - $this->_sections['picsec']['step'];
$this->_sections['picsec']['index_next'] = $this->_sections['picsec']['index'] + $this->_sections['picsec']['step'];
$this->_sections['picsec']['first']      = ($this->_sections['picsec']['iteration'] == 1);
$this->_sections['picsec']['last']       = ($this->_sections['picsec']['iteration'] == $this->_sections['picsec']['total']);
?><?php endfor; endif; ?>
             <?php unset($this->_sections['fsec']);
$this->_sections['fsec']['name'] = 'fsec';
$this->_sections['fsec']['loop'] = is_array($_loop=$this->_tpl_vars['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fsec']['show'] = true;
$this->_sections['fsec']['max'] = $this->_sections['fsec']['loop'];
$this->_sections['fsec']['step'] = 1;
$this->_sections['fsec']['start'] = $this->_sections['fsec']['step'] > 0 ? 0 : $this->_sections['fsec']['loop']-1;
if ($this->_sections['fsec']['show']) {
    $this->_sections['fsec']['total'] = $this->_sections['fsec']['loop'];
    if ($this->_sections['fsec']['total'] == 0)
        $this->_sections['fsec']['show'] = false;
} else
    $this->_sections['fsec']['total'] = 0;
if ($this->_sections['fsec']['show']):

            for ($this->_sections['fsec']['index'] = $this->_sections['fsec']['start'], $this->_sections['fsec']['iteration'] = 1;
                 $this->_sections['fsec']['iteration'] <= $this->_sections['fsec']['total'];
                 $this->_sections['fsec']['index'] += $this->_sections['fsec']['step'], $this->_sections['fsec']['iteration']++):
$this->_sections['fsec']['rownum'] = $this->_sections['fsec']['iteration'];
$this->_sections['fsec']['index_prev'] = $this->_sections['fsec']['index'] - $this->_sections['fsec']['step'];
$this->_sections['fsec']['index_next'] = $this->_sections['fsec']['index'] + $this->_sections['fsec']['step'];
$this->_sections['fsec']['first']      = ($this->_sections['fsec']['iteration'] == 1);
$this->_sections['fsec']['last']       = ($this->_sections['fsec']['iteration'] == $this->_sections['fsec']['total']);
?><?php endfor; endif; ?>
            
            <?php if ($this->_sections['picsec']['total'] > 0 || $this->_sections['fsec']['total'] > 0): ?>
            <h2>Дополнительные материалы</h2>
            <?php endif; ?>
            
            <?php if ($this->_sections['picsec']['total'] > 0): ?>
            
            
            <link rel="stylesheet" href="/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
        <script type="text/javascript" src="/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
        
        <!-- Optionally add helpers - button, thumbnail and/or media -->
        <link rel="stylesheet" href="/js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
        <script type="text/javascript" src="/js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
        <script type="text/javascript" src="/js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
        
        <link rel="stylesheet" href="/js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
        <script type="text/javascript" src="/js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
            
            
            
           
            
            <?php unset($this->_sections['picsec']);
$this->_sections['picsec']['name'] = 'picsec';
$this->_sections['picsec']['loop'] = is_array($_loop=$this->_tpl_vars['pictures']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['picsec']['show'] = true;
$this->_sections['picsec']['max'] = $this->_sections['picsec']['loop'];
$this->_sections['picsec']['step'] = 1;
$this->_sections['picsec']['start'] = $this->_sections['picsec']['step'] > 0 ? 0 : $this->_sections['picsec']['loop']-1;
if ($this->_sections['picsec']['show']) {
    $this->_sections['picsec']['total'] = $this->_sections['picsec']['loop'];
    if ($this->_sections['picsec']['total'] == 0)
        $this->_sections['picsec']['show'] = false;
} else
    $this->_sections['picsec']['total'] = 0;
if ($this->_sections['picsec']['show']):

            for ($this->_sections['picsec']['index'] = $this->_sections['picsec']['start'], $this->_sections['picsec']['iteration'] = 1;
                 $this->_sections['picsec']['iteration'] <= $this->_sections['picsec']['total'];
                 $this->_sections['picsec']['index'] += $this->_sections['picsec']['step'], $this->_sections['picsec']['iteration']++):
$this->_sections['picsec']['rownum'] = $this->_sections['picsec']['iteration'];
$this->_sections['picsec']['index_prev'] = $this->_sections['picsec']['index'] - $this->_sections['picsec']['step'];
$this->_sections['picsec']['index_next'] = $this->_sections['picsec']['index'] + $this->_sections['picsec']['step'];
$this->_sections['picsec']['first']      = ($this->_sections['picsec']['iteration'] == 1);
$this->_sections['picsec']['last']       = ($this->_sections['picsec']['iteration'] == $this->_sections['picsec']['total']);
?>
            <div class="img_preview">
            <a class="fancybox" rel="group" href="<?php echo $this->_tpl_vars['pictures'][$this->_sections['picsec']['index']]['full_url']; ?>
.jpg"><img src="/img/01.gif" style="background-image:url(<?php echo $this->_tpl_vars['pictures'][$this->_sections['picsec']['index']]['small_url']; ?>
); background-position:center center; background-repeat:no-repeat; " width="315" height="200"  /></a>
            
            	<div class="img_meta">
                Тип: <?php echo ((is_array($_tmp=$this->_tpl_vars['pictures'][$this->_sections['picsec']['index']]['ext'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
 |   ~<?php echo $this->_tpl_vars['pictures'][$this->_sections['picsec']['index']]['size']; ?>
 Mb	<br>
				<?php echo $this->_tpl_vars['pictures'][$this->_sections['picsec']['index']]['width']; ?>
x<?php echo $this->_tpl_vars['pictures'][$this->_sections['picsec']['index']]['height']; ?>
 pix
                </div>
            </div>
            <?php endfor; endif; ?>
            <div class="clr"></div>
            <script type="text/javascript">
            $(function(){
                $(".fancybox").fancybox();
            });
            </script>
            
            <?php endif; ?>
            
            
            
            
            
            
            
            <a name="files"></a>
           
            <?php if ($this->_sections['fsec']['total'] > 0): ?>
            
            
            
            <?php unset($this->_sections['fsec']);
$this->_sections['fsec']['name'] = 'fsec';
$this->_sections['fsec']['loop'] = is_array($_loop=$this->_tpl_vars['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fsec']['show'] = true;
$this->_sections['fsec']['max'] = $this->_sections['fsec']['loop'];
$this->_sections['fsec']['step'] = 1;
$this->_sections['fsec']['start'] = $this->_sections['fsec']['step'] > 0 ? 0 : $this->_sections['fsec']['loop']-1;
if ($this->_sections['fsec']['show']) {
    $this->_sections['fsec']['total'] = $this->_sections['fsec']['loop'];
    if ($this->_sections['fsec']['total'] == 0)
        $this->_sections['fsec']['show'] = false;
} else
    $this->_sections['fsec']['total'] = 0;
if ($this->_sections['fsec']['show']):

            for ($this->_sections['fsec']['index'] = $this->_sections['fsec']['start'], $this->_sections['fsec']['iteration'] = 1;
                 $this->_sections['fsec']['iteration'] <= $this->_sections['fsec']['total'];
                 $this->_sections['fsec']['index'] += $this->_sections['fsec']['step'], $this->_sections['fsec']['iteration']++):
$this->_sections['fsec']['rownum'] = $this->_sections['fsec']['iteration'];
$this->_sections['fsec']['index_prev'] = $this->_sections['fsec']['index'] - $this->_sections['fsec']['step'];
$this->_sections['fsec']['index_next'] = $this->_sections['fsec']['index'] + $this->_sections['fsec']['step'];
$this->_sections['fsec']['first']      = ($this->_sections['fsec']['iteration'] == 1);
$this->_sections['fsec']['last']       = ($this->_sections['fsec']['iteration'] == $this->_sections['fsec']['total']);
?>
                <div class="img_preview"><a href="<?php echo $this->_tpl_vars['files'][$this->_sections['fsec']['index']]['full_url']; ?>
"><?php echo $this->_tpl_vars['files'][$this->_sections['fsec']['index']]['orig_name']; ?>
</a></li>
                	<div class="img_meta">
                Тип: <?php echo ((is_array($_tmp=$this->_tpl_vars['files'][$this->_sections['fsec']['index']]['ext'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
 |   ~<?php echo $this->_tpl_vars['files'][$this->_sections['fsec']['index']]['size']; ?>
 Mb	<br>
				 
                </div>
                
                </div>
            <?php endfor; endif; ?>
            <div class="clr"></div> 
            <?php endif; ?>
            
            
            <?php if ($this->_tpl_vars['news']['txt2'] != "" && $this->_tpl_vars['news']['txt2'] != "<br />"): ?>
            <h2>Дополнительная информация</h2>
            <div class="simple_text"><?php echo $this->_tpl_vars['news']['txt2']; ?>
</div>
            <?php endif; ?>
        
            
            
           
            
            
        </div>
    </div>

	
    
    
</div>    