<?php /* Smarty version 2.6.22, created on 2016-10-19 18:21:40
         compiled from vmenu.html */ ?>
<a href="razds.php" class="mmenulink">Список разделов верхнего уровня</a><p>

<table width="*" border="0" cellspacing="1" cellpadding="5">
<?php unset($this->_sections['mysec']);
$this->_sections['mysec']['name'] = 'mysec';
$this->_sections['mysec']['loop'] = is_array($_loop=$this->_tpl_vars['items']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mysec']['show'] = true;
$this->_sections['mysec']['max'] = $this->_sections['mysec']['loop'];
$this->_sections['mysec']['step'] = 1;
$this->_sections['mysec']['start'] = $this->_sections['mysec']['step'] > 0 ? 0 : $this->_sections['mysec']['loop']-1;
if ($this->_sections['mysec']['show']) {
    $this->_sections['mysec']['total'] = $this->_sections['mysec']['loop'];
    if ($this->_sections['mysec']['total'] == 0)
        $this->_sections['mysec']['show'] = false;
} else
    $this->_sections['mysec']['total'] = 0;
if ($this->_sections['mysec']['show']):

            for ($this->_sections['mysec']['index'] = $this->_sections['mysec']['start'], $this->_sections['mysec']['iteration'] = 1;
                 $this->_sections['mysec']['iteration'] <= $this->_sections['mysec']['total'];
                 $this->_sections['mysec']['index'] += $this->_sections['mysec']['step'], $this->_sections['mysec']['iteration']++):
$this->_sections['mysec']['rownum'] = $this->_sections['mysec']['iteration'];
$this->_sections['mysec']['index_prev'] = $this->_sections['mysec']['index'] - $this->_sections['mysec']['step'];
$this->_sections['mysec']['index_next'] = $this->_sections['mysec']['index'] + $this->_sections['mysec']['step'];
$this->_sections['mysec']['first']      = ($this->_sections['mysec']['iteration'] == 1);
$this->_sections['mysec']['last']       = ($this->_sections['mysec']['iteration'] == $this->_sections['mysec']['total']);
?>
	
	<tr align="left" valign="top">
	<td style="background-color: Silver;">
	<a href="razds.php?id=<?php echo $this->_tpl_vars['items'][$this->_sections['mysec']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['items'][$this->_sections['mysec']['index']]['name']; ?>
</a><br>
	</td>
	</tr>

<?php endfor; endif; ?>
</table>
<p>

<a href="viewotzyv.php"  class="mmenulink">Отзывы</a> <p>

<a href="viewads.php"  class="mmenulink">Баннеры</a> <p>


<a href="photofolder.php" target="_blank"  class="mmenulink">Загрузка фото</a><p>

<hr />

<a href="ed_file.php?id=3"  class="mmenulink">Текст шапки сайта 1</a> <a href="ed_file.php?id=3&nonvisual=1"  class="mmenulink">html</a><p>

<a href="ed_file.php?id=8"  class="mmenulink">Текст шапки сайта 2</a> <a href="ed_file.php?id=8&nonvisual=1"  class="mmenulink">html</a><p>

<a href="ed_file.php?id=4"  class="mmenulink">Текст главной страницы 1</a> <a href="ed_file.php?id=4&nonvisual=1"  class="mmenulink">html</a><p>

<a href="ed_file.php?id=5"  class="mmenulink">Текст  главной страницы 2</a> <a href="ed_file.php?id=5&nonvisual=1" class="mmenulink">html</a><p>


<a href="ed_file.php?id=1"  class="mmenulink">Подвал 1</a> <a href="ed_file.php?id=1&nonvisual=1" class="mmenulink">html</a><p>

<a href="ed_file.php?id=2" class="mmenulink">Подвал 2</a> <a href="ed_file.php?id=2&nonvisual=1" class="mmenulink">html</a><p>

<a href="ed_file.php?id=6&nonvisual=1" class="mmenulink">Ключевые слова</a><p>

<a href="ed_file.php?id=7&nonvisual=1" class="mmenulink">Описание сайта</a><p>

<hr />
