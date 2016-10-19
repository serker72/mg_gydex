<?php /* Smarty version 2.6.22, created on 2016-10-19 18:24:39
         compiled from feedback.html */ ?>


<h2 class="popup_header">GYDEX.знает</h2>
<div id="support_form" style="">


<div style="float:left; margin-right:20px;">
 
<label for="fio">Имя: <span class="dis_orange">*</span></label><br />
<input type="text" id="fio" size="60" maxlength="255" value="" class="dis_feedback" />
<br /><br />


<label for="city">Город: <span class="dis_orange">*</span></label><br />
<input type="text" id="city" size="40" maxlength="255" value="" class="dis_feedback" />
<br /><br />


<label for="phone">Телефон: <span class="dis_orange">*</span></label><br />
<input type="text" id="phone" size="40" maxlength="255" value="" class="dis_feedback" />
<br /><br />
  



<label for="email">Электронный адрес: <span class="dis_orange">*</span></label><br />
<input type="text" id="email" size="60" maxlength="255" value="" class="dis_feedback" />
<br /><br />

</div>
<div style="float:left; margin-right:20px;"> 


<label for="message">Сообщение: <span class="dis_orange">*</span></label><br />
<textarea cols="50" rows="7" id="message" class="dis_feedback"></textarea>
<br /><br />
 
</div>
<br clear="all" />


<div style="float:left; margin-right:20px;"> 
<label>Защитный код: <span class="dis_orange">*</span></label><br />
 

<img src="/js/captcha.php?seed=1" id="captcha_img" />
<input type="text" size="5" maxlength="25" id="captcha" class="dis_feedback" value="" style="width:175px !important;" />
</div>


<div style="float:left; margin-right:20px;"><br>

<input id="mess" type="button" value="Отправить заявку" />

</div>
<br clear="all">


<small><em>Поля, отмеченные <span class="dis_orange">*</span>, обязательны для заполнения.</em></small>
<br />
 

<p id="feedback_complete" style="display:none;">Спасибо, что обратились в службу поддержки. 
GYDEX.знает и ответит в течение 24 часов!<br></p>



 
<p />

</div>
 
<script type="text/javascript">
$(function(){
	 $("#city").select2(
	 
	 {
    
	multiple: false,
	
	minimumInputLength:2,
	width:"resolve",
	 
    ajax: {
            url: "/js/city_select.php",
            dataType: 'json',
            quietMillis: 100,
            data: function (term, page) {
                return {
                    term: term, //search term
                    page_limit: 10 // page size
                };
            },
            results: function (data, page) {
                return { results: data.results };
            }

        },
        
	 }
	 ); 
	 
	 
	 
	 
	
	$("#mess").bind("click", function(){
		var can_send=true;
		
		if(can_send&&$("#fio").val().length<3){
			can_send=can_send&&false;
			alert("Заполните поле Ваше имя!");
			$("#fio").focus();				
		}
		
		if(can_send&&$("#email").val().length<5){
			can_send=can_send&&false;
			alert("Заполните поле Электронный адрес!");
			$("#email").focus();				
		}
		
		if(can_send&&$("#city").val().length<5){
			can_send=can_send&&false;
			alert("Заполните поле Город!");
			$("#city").focus();				
		}
		
		 
		
		if(can_send&&$("#message").val().length<5){
			can_send=can_send&&false;
			alert("Заполните поле Сообщение!");
			$("#message").focus();				
		}
		
		if(can_send&&$("#captcha").val().length<3){
			can_send=can_send&&false;
			alert("Заполните поле Защитный код!");
			$("#captcha").focus();				
		}
		
		if(can_send){
			$.ajax({
				async: false,
				url: "/js/send_claim.php",
				type: "POST",
				data:{
					"action":"test_captcha_simple",
					"captcha":$("#captcha").val(),
					"seed":"1"
					 
					/*"recaptcha_challenge_field": $('#recaptcha_challenge_field').val(),
					"recaptcha_response_field": $('#recaptcha_response_field').val()*/
					 
				},
				beforeSend: function(){
				 
				},
				success: function(data){
				
				  if(data!=0){
					 
					 
					   alert("Неверно введен Защитный код!");	
					   $("#captcha").focus();		
					  //   Recaptcha.reload();
				  	 	can_send=can_send&&false;
				  } 
				},
				error: function(xhr, status){
				
					
				}	 
			});
				
			
		}
		
		
		if(can_send) $.ajax({
				async: true,
				url: "/js/send_claim.php",
				type: "POST",
				data:{
					"action":"send_message",
					"message": $('#message').val(),
					"fio": $('#fio').val(),
					"city": $('#city').val(),
					"phone": $('#phone').val(),
					 
					"email": $('#email').val(),
					 
				},
				beforeSend: function(){
				 	$("#support_form input").prop("disabled", true);
					$("#support_form select").prop("disabled", true);
					$("#support_form textarea").prop("disabled", true);
				},
				success: function(data){
				 // $("#is_confirmed_confirmer").html(data);
				 	$("#support_form input[type=text]").val('');
					$("#support_form textarea").val('');
					
					$('#city').select2("val", "");
					
					
				 	$("#support_form input").prop("disabled", false);
					$("#support_form select").prop("disabled", false);
					$("#support_form textarea").prop("disabled", false);
					
					$('#feedback_complete').show('fast'); 
					 
				 	 
					window.setTimeout("$('#feedback_complete').toggle('display'); $('.close_feedback').trigger('click');",7000);
					
					 
				  	//Recaptcha.reload();
				},
				error: function(xhr, status){
					$("#support_form input").prop("disabled", false);
					$("#support_form textarea").prop("disabled", false);
					$("#support_form select").prop("disabled", false);
					
				}	 
			});
			
		
		
	});
	
});
</script>

