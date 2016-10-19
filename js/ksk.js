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
    $("#callDialog").show();
    return false;
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
    $("#postDialog").show();
    return false;
}

// Функция, выполняемая при загрузке страницы
$(document).ready(function(){
    $("#callDialogModal_close").bind('click', function() {
        $("#callDialog").hide();
    });
            
    $("#postDialogModal_close").bind('click', function() {
        $("#postDialog").hide();
    });
});
