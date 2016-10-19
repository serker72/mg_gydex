<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from index_numbers.html */ ?>
 <div id="numbers">
        <div id="numbers_inner">
        	<h2>GYDEX в цифрах</h2>
            <div class="descr">Мы предлагаем решения, которые действительно работают!</div>
            
            <div id="numbers_slider_wrapper">
            
                <div class="num-cycle-prev"></div>
                <div class="num-cycle-next"></div>
                
                <div id="numbers_slider" class="cycle-slideshow"
                data-cycle-fx="carousel"
                data-cycle-timeout="7000"
                data-cycle-speed="500"
                data-cycle-carousel-visible="4"
                data-cycle-slides="> div.numbers_block"
                data-cycle-prev=".num-cycle-prev"
                data-cycle-next=".num-cycle-next"
        
                 >
         
                
                    <?php $_from = $this->_tpl_vars['nums']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['nf'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nf']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['nf']['iteration']++;
?>
                    <div class="numbers_block">
                        <div class="number<?php echo $this->_tpl_vars['item']['class_index']; ?>
">
                        <?php if ($this->_tpl_vars['item']['low']): ?><div style="margin-top:15px;"><?php endif; ?>
                        <?php echo $this->_tpl_vars['item']['number']; ?>

                          <?php if ($this->_tpl_vars['item']['low']): ?></div><?php endif; ?>
                        </div>
                    
                        <div class="descr"> 
                            <?php echo $this->_tpl_vars['item']['name']; ?>

                        </div>
                    </div>
                    <?php endforeach; endif; unset($_from); ?>
                
                </div>
             
             </div>
        
        </div>    
    </div>