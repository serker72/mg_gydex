$(function(){
	$("div.timeline-heading").bind("click", function(){
		
		if($(this).parent().hasClass("timeline-content")) {
			$(this).parent().removeClass("timeline-content").addClass("timeline-content-a");
			 
		}else if($(this).parent().hasClass("timeline-content-a")) {
			$(this).parent().removeClass("timeline-content-a").addClass("timeline-content");
		}
	});
	
});