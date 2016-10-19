<?php /* Smarty version 2.6.22, created on 2016-10-19 15:56:14
         compiled from news/items_news.html */ ?>
    <div id="recent_news_block">
     <?php $_from = $this->_tpl_vars['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['rn'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['rn']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['rn']['iteration']++;
?>  
        <div class="recent_news_slide">
            <div class="news_controls">
                <?php if ($this->_tpl_vars['item']['has_images']): ?>
                <a href="<?php echo $this->_tpl_vars['item']['path']; ?>
#pictures"><img src="/images/ico-photo.png" alt="к новости прикрепленны изображения" width="48" height="47" /></a>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['item']['has_files']): ?>
                <a href="<?php echo $this->_tpl_vars['item']['path']; ?>
#files"><img src="/images/ico_files.png" alt="к новости прикрепленны файлы" width="48" height="47" /></a>
                <?php endif; ?>
            	 
            </div>
            <div class="news_preview">
                
                <?php if ($this->_tpl_vars['item']['has_images']): ?>
                <div id="news_preview_slider" class="cycle-slideshow"
                    data-cycle-fx="scrollHorz"
                    data-cycle-timeout="2000"
                    data-cycle-speed="500"
                    data-cycle-slides="> div.news_preview_slide"
                    data-cycle-pager="> .npc-pager"
                    data-cycle-pager-template=" <a href='#' class='npc-pager-button'>&nbsp;</a> ">
                    
                    <div class="npc-pager"></div>
                    
                    <div class="news_preview_slide" style="background-image:url(/<?php echo $this->_tpl_vars['item']['photo_small']; ?>
)"></div>
                    <?php $_from = $this->_tpl_vars['item']['images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['image']):
?>
                    <div class="news_preview_slide" style="background-image:url(<?php echo $this->_tpl_vars['image']['small_url']; ?>
)"></div>
                    <?php endforeach; endif; unset($_from); ?>
                    
                    
                </div>
                <?php else: ?>
                <div class="news_preview_slide" style="background-image:url(/<?php echo $this->_tpl_vars['item']['photo_small']; ?>
)"></div>
                <?php endif; ?>
                
                
            </div>
            <div class="news_meta">
                <div class="news_meta_data">Дата: <span class="news_meta_value"><?php echo $this->_tpl_vars['item']['newsdate']; ?>
</span></div>
                
               <!-- <div class="news_meta_data">Комментариев: <span class="news_meta_value"><?php echo $this->_tpl_vars['item']['num_comments']; ?>
</span></div>-->
            </div>
            
            <div class="news_title">
                <h3><?php echo $this->_tpl_vars['item']['name']; ?>
</h3>
            </div>
            
            <div class="news_anons">
                <?php echo $this->_tpl_vars['item']['small_txt']; ?>

            </div>
            <a href="<?php echo $this->_tpl_vars['item']['path']; ?>
" class="news_more">Узнать больше</a>
            
        </div>
        <?php endforeach; endif; unset($_from); ?>
        
         
         
		<div class="clr"></div>
    </div>
    
    
    
    <?php echo $this->_tpl_vars['pages']; ?>