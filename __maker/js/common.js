//открытие нового окна
function winop(imgurl, width, height, winname)
{
    var title = "window";
    im = window.open(imgurl, winname,'top=40,left=40,width='+width+',height='+height+', scrollbars=yes, menu=no,status=no,resizable=no');
    im.focus();
   /* im.document.open ("text/html");
    im.document.write ('<html><title>'+title+'</title><body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 bgcolor=#ffffff><table width=100% height=100% border=0 cellpadding=0 cellspacing=0><tr><td align=center><img src='+imgurl+' width='+width+' height='+height+'></td></tr></table></body></html>');
    im.document.close ();*/
}

function OpenHelp(filename){
	$.ajax({
			  async: true,
			  url: "js/help.php",
			  type: "POST",
			  data:{
				  "action":"help",
				  "file":filename,
				  "title":document.title,
				  "description":"%{$description}%"
			  },
			  beforeSend: function(){
				$("#dialog_help_inner").html('<img src="/img/newdis/wait.gif" width="32" height="32" alt="подождите, пожалуйста..." />');	
			  },
			  success: function(data){
				$("#dialog_help_inner").html(data);	
				
			  },
			  error: function(xhr, status){
				  //alert("Ошибка добавления вопроса.");	
			  }	 
		  });
		  $("#dialog_help").dialog("open");
}

$(function(){
	
	$("#dialog_help").dialog({
			autoOpen: false,
			modal: true,
			width: 900,
			height: 550,
			stack: true,
			title: "Справка по текущему разделу",
			buttons:{
				"ОК":function(){
					$("#dialog_help").dialog("close");
				}
			}
	});
	
});