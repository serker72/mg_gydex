$(function(){
	//popup routine
	function CenterIt(){
		w_w=parseInt($(window).width());
		w_h=parseInt($(window).height());
		width=parseInt($("#popup_window").width());
		//height=parseInt($("#popup_window").height());
		
		if(w_w>width){
			left=Math.ceil((w_w-width )/2);
		}else{
			left=10;
		}
		
	
		$("#popup_window").css("left", left);
			
	}
	 
	$("#popup_window a.close").bind("click", function(){
		$("#popup").removeClass("shown");
		$("#popup").addClass("hidden");
		
		$("#popup_window").removeClass("shown");
		$("#popup_window").addClass("hidden");
		
		
		return false;
	});
	$(window).bind('resize', function(){
		 CenterIt();
	});
	
 
	
/******************************************* login */	
	//popup routine
	function CenterLoginIt(){
		w_w=parseInt($(window).width());
		w_h=parseInt($(document).height());
		width=parseInt($("#popup_login_window").width());
		height=parseInt($("#popup_login_window").height());
		
		if(w_w>width){
			left=Math.ceil((w_w-width )/2);
		 
		}else{
			left=10;
			 
		}
		
		 
		var Top_modal_window = $(document).scrollTop() + $(window).height()/2-$("#popup_login_window").height()/2;
 
	
		$("#popup_login_window").css("left", left);
		$("#popup_login_window").css("top", Math.ceil(Top_modal_window)+"px"); // alert(Top_modal_window);
			
	}
	
	
	 
	
	$("#popup_login_window a.close_login").bind("click", function(){
		$("#popup_login").removeClass("shown");
		$("#popup_login").addClass("hidden");
		
		$("#popup_login_window").removeClass("shown");
		$("#popup_login_window").addClass("hidden");
		
		
		return false;
	});
	
	$("#logform").bind("submit", function(){
			if(($("#login").val().length==0)||($("#password").val().length==0)){
				alert("Укажите логин и пароль!");
				return false;
					
			}
			
			$("#popup_login_window1").removeClass("shown");
			$("#popup_login_window1").addClass("hidden");
			
		 
			
			$("#popup_login1").removeClass("shown");
			$("#popup_login1").addClass("hidden");
			
			
			$("#popup_login_window").removeClass("hidden");
			$("#popup_login_window").addClass("shown");
			
			CenterLoginIt();
			
			 
			
			$("#popup_login").removeClass("hidden");
			$("#popup_login").addClass("shown");
		
		

			$.ajax({
			  async: false,
			  url: "/js/pre_auth_check.php",
			  type: "POST",
			  data:{
				  "action":"pre_auth",
				  "login":$("#login").val(),
				  "password": $("#password").val()
			  },
			  beforeSend: function(){
				   $("#login_message").html("<img src=\"/img/wait.gif\" alt=\"подождите, пожалуйста...\" />");
				  $("#log").prop("disabled", true);
			  },
			  success: function(data){
				  
				  $("#login_message").html(data);
				  
				  $("#log").prop("disabled", false);
			  },
			  error: function(xhr, status){
				  //alert("Ошибка добавления вопроса.");
				   $("#log").prop("disabled", false);	
			  }	 
		  });
			
			
			return false;
		});	
 
/*************************************************************************************/
//form popup

function CenterLoginIt1(){
		w_w=parseInt($(window).width());
		w_h=parseInt($(document).height());
		width=parseInt($("#popup_login_window1").width());
		height=parseInt($("#popup_login_window1").height());
		
		if(w_w>width){
			left=Math.ceil((w_w-width )/2);
		 
		}else{
			left=10;
			 
		}
		
		 
		var Top_modal_window = $(document).scrollTop() + $(window).height()/2-$("#popup_login_window1").height()/2;
 
	
		$("#popup_login_window1").css("left", left);
		$("#popup_login_window1").css("top", Math.ceil(Top_modal_window)+"px"); //  
			
	}
	
	
	 
	
	$("#popup_login_window1 a.close_login1").bind("click", function(){
		$("#popup_login1").removeClass("shown");
		$("#popup_login1").addClass("hidden");
		
		$("#popup_login_window1").removeClass("shown");
		$("#popup_login_window1").addClass("hidden");
		
		
		return false;
	});
	
	$("a.login").bind("click", function(){
		
			$("#popup_login_window1").removeClass("hidden");
			$("#popup_login_window1").addClass("shown");
			
			CenterLoginIt1();
			
			$("#password").val('');
			 
			
			$("#popup_login1").removeClass("hidden");
			$("#popup_login1").addClass("shown");
		
		
		return false;
	});




/****************************** feedback ************************************* */
	function CenterFeedback(){
		w_w=parseInt($(window).width());
		w_h=parseInt($(document).height());
		width=parseInt($("#popup_feedback_window").width());
		height=parseInt($("#popup_feedback_window").height());
		
		if(w_w>width){
			left=Math.ceil((w_w-width )/2);
		 
		}else{
			left=10;
			 
		}
		
		 
		var Top_modal_window = $(document).scrollTop() + $(window).height()/2-$("#popup_feedback_window").height()/2;
 
	
		$("#popup_feedback_window").css("left", left);
		$("#popup_feedback_window").css("top", Math.ceil(Top_modal_window)+"px"); // alert(Top_modal_window);
			
	}
	
	
	 
	
	$("#popup_feedback_window a.close_feedback").bind("click", function(){
		$("#popup_feedback").removeClass("shown");
		$("#popup_feedback").addClass("hidden");
		
		$("#popup_feedback_window").removeClass("shown");
		$("#popup_feedback_window").addClass("hidden");
		
		
		return false;
	});
	
	
	 
	$(window).bind('resize', function(){
		 CenterFeedback();
	});
	
	$("a#feedback_caller").bind("click", function(){
		
			$("#popup_feedback_window").removeClass("hidden");
			$("#popup_feedback_window").addClass("shown");
			
			CenterFeedback();
			
			 
			
			$("#popup_feedback").removeClass("hidden");
			$("#popup_feedback").addClass("shown");
		
		
		return false;
	});
	
	
/****************************** comments ********************************************************/

/****************************** feedback ************************************* */
	function CenterComment(){
		w_w=parseInt($(window).width());
		w_h=parseInt($(document).height());
		width=parseInt($("#popup_comment_window").width());
		height=parseInt($("#popup_comment_window").height());
		
		if(w_w>width){
			left=Math.ceil((w_w-width )/2);
		 
		}else{
			left=10;
			 
		}
		
		 
		var Top_modal_window = $(document).scrollTop() + $(window).height()/2-$("#popup_comment_window").height()/2;
 
	
		$("#popup_comment_window").css("left", left);
		$("#popup_comment_window").css("top", Math.ceil(Top_modal_window)+"px"); // alert(Top_modal_window);
			
	}
	
	
	 
	
	$("#popup_comment a.close_feedback").bind("click", function(){
		$("#popup_comment").removeClass("shown");
		$("#popup_comment").addClass("hidden");
		
		$("#popup_comment_window").removeClass("shown");
		$("#popup_comment_window").addClass("hidden");
		
		
		return false;
	});
	
	
	 
	$(window).bind('resize', function(){
		 CenterComment();
	});
	
	$("a#comment_caller").bind("click", function(){
		
			$("#popup_comment_window").removeClass("hidden");
			$("#popup_comment_window").addClass("shown");
			
			CenterComment();
			
			 
			
			$("#popup_comment").removeClass("hidden");
			$("#popup_comment").addClass("shown");
		
		
		return false;
	});	
	
 
 
 
 
 
 /****************************** callback ************************************* */
	function CenterCallback(){
		w_w=parseInt($(window).width());
		w_h=parseInt($(document).height());
		width=parseInt($("#popup_callback_window").width());
		height=parseInt($("#popup_callback_window").height());
		
		if(w_w>width){
			left=Math.ceil((w_w-width )/2);
		 
		}else{
			left=10;
			 
		}
		
		 
		var Top_modal_window = $(document).scrollTop() + $(window).height()/2-$("#popup_callback_window").height()/2;
 
	
		$("#popup_callback_window").css("left", left);
		$("#popup_callback_window").css("top", Math.ceil(Top_modal_window)+"px"); // alert(Top_modal_window);
			
	}
	
	
	 
	
	$("#popup_callback a.close_callback").bind("click", function(){
		$("#popup_callback").removeClass("shown");
		$("#popup_callback").addClass("hidden");
		
		$("#popup_callback_window").removeClass("shown");
		$("#popup_callback_window").addClass("hidden");
		
		
		return false;
	});
	
	
	 
	$(window).bind('resize', function(){
		 CenterCallback();
	});
	
	$("a#callback_caller").bind("click", function(){
		
			$("#popup_callback_window").removeClass("hidden");
			$("#popup_callback_window").addClass("shown");
			
			CenterCallback();
			
			 
			
			$("#popup_callback").removeClass("hidden");
			$("#popup_callback").addClass("shown");
		
		
		return false;
	});	
	
 
 });