/*****************************************************************************
 *  Сайт Machine Group
 *
 *  Author: Sergey Kerimov
 *  e-mail: serker72@gmail.com
 *****************************************************************************/

// получить значения параметров из URL строки на текущей странице
$.extend({
    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name){
        return $.getUrlVars()[name];
    }
});

function OpenCallDialog() {
    var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    
    var w1 = (w - 490)/2;
    var h1 = (h - 540)/2;
    
    $("#callDialog").css({
        'left': w1,
        'top': h1
    });
    $('#CallDialogForm').show(); 
    $('#CallDialogSubmit').show(); 
    $('#CallDialogComplete').hide(); 
    $("#callDialog").show();
    //return false;
}

function OpenPostDialog() {
    var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    
    var w1 = (w - 490)/2;
    var h1 = (h - 760)/2;
    
    $("#postDialog").css({
        'left': w1,
        'top': h1
    });
    $('#PostDialogForm').show(); 
    $('#PostDialogSubmit').show(); 
    $('#PostDialogComplete').hide(); 
    $("#postDialog").show();
    //return false;
}

// Функция, выполняемая при загрузке страницы
$(document).ready(function(){
    // Маска для ввода номера телефона
    $("#CallDialogPhone, #PostDialogPhone").inputmask("phone", {
        url: "/js/inputmask/phone-codes/phone-codes.js", 
        removeMaskOnSubmit:true
    });
            
    $("#callDialogModal_close").bind('click', function() {
        $("#callDialog").hide();
    });
            
    $("#postDialogModal_close").bind('click', function() {
        $("#postDialog").hide();
    });
    
    // Отправим сообщение
    $("#PostDialogSubmit").bind('click', function() {
        var can_send=true;

        if(can_send&&$("#PostDialogEmail").val().length<5){
                can_send=can_send&&false;
                alert("Заполните поле E-mail !");
                $("#PostDialogEmail").focus();				
        }

        if(can_send&&$("#PostDialogPhone").val().length<3){
                can_send=can_send&&false;
                alert("Заполните поле Телефон !");
                $("#PostDialogPhone").focus();				
        }

        if(can_send&&$("#PostDialogName").val().length<3){
                can_send=can_send&&false;
                alert("Заполните поле Имя !");
                $("#PostDialogName").focus();				
        }

        if(can_send&&$("#PostDialogQuestion").val().length<5){
                can_send=can_send&&false;
                alert("Заполните поле Ваш запрос !");
                $("#PostDialogQuestion").focus();				
        }
        
        if(can_send) {
            $.ajax({
                async: true,
                url: "/js/ksk_send_message.php",
                type: "POST",
                data:{
                        "action":"send_message",
                        "message": $('#PostDialogQuestion').val(),
                        "fio": $('#PostDialogName').val(),
                        "phone": $('#PostDialogPhone').val(),
                        "email": $('#PostDialogEmail').val()
                },
                beforeSend: function(){
                        $("#PostDialogForm input").prop("disabled", true);
                        $("#PostDialogForm select").prop("disabled", true);
                        $("#PostDialogForm textarea").prop("disabled", true);
                },
                success: function(data){
                 // $("#is_confirmed_confirmer").html(data);
                        $("#PostDialogForm input[type=text]").val('');
                        $("#PostDialogForm textarea").val('');

                        //$("#claim_city").select2("val", "");


                        $("#PostDialogForm input").prop("disabled", false);
                        $("#PostDialogForm select").prop("disabled", false);
                        $("#PostDialogForm textarea").prop("disabled", false);

                        $('#PostDialogForm').hide(); 
                        $('#PostDialogSubmit').hide(); 
                        $('#PostDialogComplete').show(); 


                        window.setTimeout("$('#PostDialogComplete').toggle('display'); $('#postDialogModal_close').trigger('click')",7000);

                        //Recaptcha.reload();

                },
                error: function(xhr, status){
                        $("#PostDialogForm input").prop("disabled", false);
                        $("#PostDialogForm textarea").prop("disabled", false);
                        $("#PostDialogForm select").prop("disabled", false);

                }	 
            });
        }
    });
    
    // Отправим сообщение
    $("#CallDialogSubmit").bind('click', function() {
        var can_send=true;

        if(can_send&&$("#CallDialogPhone").val().length<3){
                can_send=can_send&&false;
                alert("Заполните поле Телефон !");
                $("#CallDialogPhone").focus();				
        }

        if(can_send&&$("#CallDialogName").val().length<3){
                can_send=can_send&&false;
                alert("Заполните поле Имя !");
                $("#CallDialogName").focus();				
        }
        
        if(can_send) {
            $.ajax({
                async: true,
                url: "/js/ksk_send_message.php",
                type: "POST",
                data:{
                        "action":"send_callback",
                        "fio": $('#CallDialogName').val(),
                        "phone": $('#CallDialogPhone').val()
                },
                beforeSend: function(){
                        $("#CallDialogForm input").prop("disabled", true);
                        $("#CallDialogForm select").prop("disabled", true);
                        $("#CallDialogForm textarea").prop("disabled", true);
                },
                success: function(data){
                 // $("#is_confirmed_confirmer").html(data);
                        $("#CallDialogForm input[type=text]").val('');
                        $("#CallDialogForm textarea").val('');

                        //$("#claim_city").select2("val", "");


                        $("#CallDialogForm input").prop("disabled", false);
                        $("#CallDialogForm select").prop("disabled", false);
                        $("#CallDialogForm textarea").prop("disabled", false);

                        $('#CallDialogForm').hide(); 
                        $('#CallDialogSubmit').hide(); 
                        $('#CallDialogComplete').show(); 


                        window.setTimeout("$('#CallDialogComplete').toggle('display'); $('#callDialogModal_close').trigger('click')",7000);

                        //Recaptcha.reload();

                },
                error: function(xhr, status){
                        $("#CallDialogForm input").prop("disabled", false);
                        $("#CallDialogForm textarea").prop("disabled", false);
                        $("#CallDialogForm select").prop("disabled", false);

                }	 
            });
        }
    });
});
