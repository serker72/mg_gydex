<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from common_right.html */ ?>
 


<?php echo $this->_tpl_vars['recent_news']; ?>



<?php echo $this->_tpl_vars['newsblock']; ?>



<?php echo $this->_tpl_vars['menu']; ?>


 



<!--




 <div class="dis_cons_block_4">
<p style="margin-left:-10px; margin-top:0px;">
<img src="/img/dis/icq.png" width="30" height="30" align="middle" hspace="3" border="0"  /><a name="feedback" href="#" class="moreusl2" onclick="$('#consultants2').toggle('display'); return false;">Обратная связь</a><br clear="all" />
</p>
<div id="consultants2" style="display:none;">
<label for="consultantst2">Ваше сообщение:</label><br />

<textarea id="message_consultantst2" cols="20" rows="5" style="width:160px; height:120px;"></textarea><p />
<input type="button" id="send_consultantst2"  value="Отправить" />

<input type="button" id="cancel_consultantst2"  value="Отмена" />

<script type="text/javascript">
$(function(){
	function sendIt(){
	$('#is_sent_consultants2').hide();	
	}
	
	$("#cancel_consultantst2").bind("click", function(){
		$("#consultants2").hide();
	});
	$("#send_consultantst2").bind("click", function(){
		//alert('zz');
		if($('#message_consultantst2').val().length>0){
			  $.ajax({
				async: true,
				url: "/js/send_message.php",
				type: "POST",
				data:{
					"action":"send_message",
					"message": $('#message_consultantst2').val()
				},
				beforeSend: function(){
				 
				 $('#message_consultantst2').prop("disabled",true);
				 $('#send_consultantst2').prop( "disabled",true);  
				},
				success: function(data){
				 // $("#is_confirmed_confirmer").html(data);
				 
				 	$('#consultants2').hide(); 
					$('#message_consultantst2').val('');  
					$('#is_sent_consultants2').show(); 
					 
				 	$('#message_consultantst2').prop("disabled",false);
				 	$('#send_consultantst2').prop( "disabled",false);  
					window.setTimeout("$('#is_sent_consultants2').toggle('display')", 2500);
				  
				},
				error: function(xhr, status){
					//alert(" .");
					$('#message_consultantst2').prop("disabled",false);
				 	$('#send_consultantst2').prop( "disabled",false); 
				}	 
			});
			
		}
	});
});
</script>
</div>

<div id="is_sent_consultants2" style="display:none;">
<strong>Ваше сообщение отправлено! Спасибо!</strong>
</div>

</div>





 <div class="dis_cons_block_3">
<p style="margin-left:-10px; margin-top:0px;">
<img src="/img/dis/icq.png" width="30" height="30" align="middle" hspace="3" border="0"  /><a href="#" class="moreusl2" onclick="$('#consultants').toggle('display'); return false;">Наши консультанты</a><br clear="all" />
</p>
<div id="consultants" style="display:none;">
Для связи с консультантами просим Вас заполнить <a href="#" onclick="$('#consultants2').show(); $('#message_consultantst2').focus(); return false;">форму обратной связи</a>.
</div>
 </div>
 






-->



 




<?php echo $this->_tpl_vars['basketplace']; ?>



<?php if ($this->_tpl_vars['has_auth']): ?>
<!-- блок авторизации -->
<?php if ($this->_tpl_vars['authorized']): ?>

<div class="right_container">

<div class="right_top"></div>
	<div class="right_item_container">
	<?php echo $this->_tpl_vars['well_prompt']; ?>
 <strong><?php echo $this->_tpl_vars['username']; ?>
</strong>!
	</div>
	<div class="right_bottom"></div>



<ul type="circle">
<li><a href="/profile.php"><?php echo $this->_tpl_vars['your_profile']; ?>
</a></li>
<li><a href="/vieworders.php"><?php echo $this->_tpl_vars['your_orders']; ?>
</a></li>
<li><a href="/usereffects.php?doOut=1"><?php echo $this->_tpl_vars['go_away']; ?>
</a></li>
</ul>


</div>

<?php else: ?>
<div class="right_container">

<form action="/usereffects.php" method="post" name="auther" id="auther">
	<div class="right_top"></div>
	<div class="right_item_container">
	<?php echo $this->_tpl_vars['auth_title']; ?>
 
	</div>
	<div class="right_bottom"></div>



<div class="right_text">

<strong><?php echo $this->_tpl_vars['login_prompt']; ?>
</strong>
	<input type="text" name="login" id="login" size="15" maxlength="40" value="<?php echo $this->_tpl_vars['def_log']; ?>
" onfocus="if(this.value=='<?php echo $this->_tpl_vars['def_log']; ?>
') this.value='';" onblur="if(this.value=='') this.value='<?php echo $this->_tpl_vars['def_log']; ?>
';">

<strong><?php echo $this->_tpl_vars['pass_prompt']; ?>
</strong>


	<input type="password" name="passw" id="passw" size="15" maxlength="40" value="****" onfocus="if(this.value=='****') this.value='';" onblur="if(this.value=='') this.value='****';"><br>


<input type="checkbox" name="rem_me" id="rem_me" value=""><strong><?php echo $this->_tpl_vars['remme_prompt']; ?>
</strong><br>

<input type="submit" name="doLog" id="doLog" value="<?php echo $this->_tpl_vars['log_caption']; ?>
"><br>
</form>

<a href="/profile.php"><?php echo $this->_tpl_vars['reg_prompt']; ?>
</a><br>

<a href="/restore.php"><?php echo $this->_tpl_vars['forget_prompt']; ?>
</a>
</div>
</div>
<?php endif; ?>
<?php endif; ?>


<?php if ($this->_tpl_vars['has_chk']): ?>
<!-- проверка статуса заказа -->
<div class="right_container">
<form action="/vieworders.php" name="cher" id="cher" onsubmit="q=document.getElementById('order_no'); if(q.value.length==0) return false;">
<div class="right_top"></div>
	<div class="right_item_container">
	<?php echo $this->_tpl_vars['ch_title']; ?>

	</div>
	<div class="right_bottom"></div>

	<div class="right_text">
	<input type="text" name="order_no" id="order_no" value="<?php echo $this->_tpl_vars['ch_value']; ?>
" size="15" maxlength="128"><p>
	<input type="submit" name="doChk" id="doChk" value="<?php echo $this->_tpl_vars['ch_caption']; ?>
">
	</div>
</form>
</div>
<?php endif; ?>


 