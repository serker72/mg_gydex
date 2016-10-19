<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from common_footer.html */ ?>

  
    
    <div id="footer">
    	<div id="footer_menu_wrapper">
            <div id="footer_menu">
                <ul>
                
                 <?php unset($this->_sections['hmenusec']);
$this->_sections['hmenusec']['name'] = 'hmenusec';
$this->_sections['hmenusec']['loop'] = is_array($_loop=$this->_tpl_vars['hmenu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['hmenusec']['show'] = true;
$this->_sections['hmenusec']['max'] = $this->_sections['hmenusec']['loop'];
$this->_sections['hmenusec']['step'] = 1;
$this->_sections['hmenusec']['start'] = $this->_sections['hmenusec']['step'] > 0 ? 0 : $this->_sections['hmenusec']['loop']-1;
if ($this->_sections['hmenusec']['show']) {
    $this->_sections['hmenusec']['total'] = $this->_sections['hmenusec']['loop'];
    if ($this->_sections['hmenusec']['total'] == 0)
        $this->_sections['hmenusec']['show'] = false;
} else
    $this->_sections['hmenusec']['total'] = 0;
if ($this->_sections['hmenusec']['show']):

            for ($this->_sections['hmenusec']['index'] = $this->_sections['hmenusec']['start'], $this->_sections['hmenusec']['iteration'] = 1;
                 $this->_sections['hmenusec']['iteration'] <= $this->_sections['hmenusec']['total'];
                 $this->_sections['hmenusec']['index'] += $this->_sections['hmenusec']['step'], $this->_sections['hmenusec']['iteration']++):
$this->_sections['hmenusec']['rownum'] = $this->_sections['hmenusec']['iteration'];
$this->_sections['hmenusec']['index_prev'] = $this->_sections['hmenusec']['index'] - $this->_sections['hmenusec']['step'];
$this->_sections['hmenusec']['index_next'] = $this->_sections['hmenusec']['index'] + $this->_sections['hmenusec']['step'];
$this->_sections['hmenusec']['first']      = ($this->_sections['hmenusec']['iteration'] == 1);
$this->_sections['hmenusec']['last']       = ($this->_sections['hmenusec']['iteration'] == $this->_sections['hmenusec']['total']);
?>
                <?php unset($this->_sections['hmenusec1']);
$this->_sections['hmenusec1']['name'] = 'hmenusec1';
$this->_sections['hmenusec1']['loop'] = is_array($_loop=$this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['subs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['hmenusec1']['show'] = true;
$this->_sections['hmenusec1']['max'] = $this->_sections['hmenusec1']['loop'];
$this->_sections['hmenusec1']['step'] = 1;
$this->_sections['hmenusec1']['start'] = $this->_sections['hmenusec1']['step'] > 0 ? 0 : $this->_sections['hmenusec1']['loop']-1;
if ($this->_sections['hmenusec1']['show']) {
    $this->_sections['hmenusec1']['total'] = $this->_sections['hmenusec1']['loop'];
    if ($this->_sections['hmenusec1']['total'] == 0)
        $this->_sections['hmenusec1']['show'] = false;
} else
    $this->_sections['hmenusec1']['total'] = 0;
if ($this->_sections['hmenusec1']['show']):

            for ($this->_sections['hmenusec1']['index'] = $this->_sections['hmenusec1']['start'], $this->_sections['hmenusec1']['iteration'] = 1;
                 $this->_sections['hmenusec1']['iteration'] <= $this->_sections['hmenusec1']['total'];
                 $this->_sections['hmenusec1']['index'] += $this->_sections['hmenusec1']['step'], $this->_sections['hmenusec1']['iteration']++):
$this->_sections['hmenusec1']['rownum'] = $this->_sections['hmenusec1']['iteration'];
$this->_sections['hmenusec1']['index_prev'] = $this->_sections['hmenusec1']['index'] - $this->_sections['hmenusec1']['step'];
$this->_sections['hmenusec1']['index_next'] = $this->_sections['hmenusec1']['index'] + $this->_sections['hmenusec1']['step'];
$this->_sections['hmenusec1']['first']      = ($this->_sections['hmenusec1']['iteration'] == 1);
$this->_sections['hmenusec1']['last']       = ($this->_sections['hmenusec1']['iteration'] == $this->_sections['hmenusec1']['total']);
?><?php endfor; endif; ?>
                 <!-- hmenu item -->
                    <li class="<?php if ($this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['is_current']): ?>current<?php endif; ?>"><a href="<?php echo $this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['item_url']; ?>
" class="<?php if ($this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['is_current']): ?>current<?php endif; ?>"><?php echo $this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['name']; ?>
</a>
                        <?php if ($this->_sections['hmenusec1']['total'] > 0): ?>
                        <ul>
                         <?php $_from = $this->_tpl_vars['hmenu'][$this->_sections['hmenusec']['index']]['subs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itm']):
?>
                            <li class="<?php if ($this->_tpl_vars['itm']['is_current']): ?>current<?php endif; ?>"><a href="<?php echo $this->_tpl_vars['itm']['item_url']; ?>
" class="<?php if ($this->_tpl_vars['itm']['is_current']): ?>current<?php endif; ?>"><?php echo $this->_tpl_vars['itm']['name']; ?>
</a></li>
                         <?php endforeach; endif; unset($_from); ?>
                        </ul>
                        <?php endif; ?>
                        <?php if ($this->_sections['hmenusec']['last']): ?>
                            <?php echo $this->_tpl_vars['footer1']; ?>

                    
                        <?php endif; ?>
                    </li>
                <!-- endof hmenu item -->
                <?php endfor; endif; ?>
                </ul>
            
            </div>
        </div>
        <div id="copyrights">
        	<div class="colleft">
                <div class="col1"><a href="/">GYDEX</a>   </div>
                <?php $_from = $this->_tpl_vars['mmenu4']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['m2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['m2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['m2']['iteration']++;
?>
                <div class="col1">
                    <a href="<?php echo $this->_tpl_vars['item']['url']; ?>
" class="<?php if ($this->_tpl_vars['item']['is_current']): ?>current<?php endif; ?>"><?php echo $this->_tpl_vars['item']['name']; ?>
</a>
                </div>
                <?php endforeach; endif; unset($_from); ?>
                <div class="col1">
                    <a href="/map.php"  >Карта сайта</a>
                </div>
               
            </div> 
            
            <div class="colright">
		<div class="col2">
<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t45.1;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet' "+
"border='0' width='31' height='31'><\/a>")
//--></script><!--/LiveInternet-->


<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter27996717 = new Ya.Metrika({id:27996717,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/27996717" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<br>
<a href="https://plus.google.com/103416938788627533084" rel="publisher">Google+</a> 

		</div>

            	 <div class="col2">
	  GYDEX 2010-<?php 
echo date('Y');
 ?>
				</div>
                <div class="col2">
	 
                  
                  <?php echo $this->_tpl_vars['footer2']; ?>
 


            	 </div> 
            	
            </div>
            <div class="clr"></div>
        </div>
    </div>
