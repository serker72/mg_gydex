//получим коорд
function GetCoords(e){
	/*if (e.pageX || e.pageY)
	  {
	    x = e.pageX;
	    y = e.pageY;
	  }
	  else if (e.clientX || e.clientY)
	  {
	    x = e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft) - document.documentElement.clientLeft;
	    y = e.clientY + (document.documentElement.scrollTop || document.body.scrollTop) - document.documentElement.clientTop;
	  }*/
	  
	  	w_w=parseInt($(window).width());
		w_h=parseInt($(document).height());
		width=300; 
		height=200; 
		
		if(w_w>width){
			left=Math.ceil((w_w-width )/2);
		 
		}else{
			left=10;
			 
		}
		
		 
		Top_modal_window = $(document).scrollTop() + $(window).height()/2-height/2;
 
	
		//$("#popup_login_window").css("left", left);
		//$("#popup_login_window").css("top", Math.ceil(Top_modal_window)+"px"); // alert(Top_modal_window);
	  
	  coord=new Array();
	  coord[0]=left;
	  coord[1]= Math.ceil(Top_modal_window);
	//  alert(coord);
	  return coord;
}

//зададим координаты объекту
function SetCoords(obname){
	r=document.getElementById(obname); 
	//alert(r.style.height);
	
	//r.style.left=(coord[0]-r.style.pixelLeft/2)+'px'; 
	r.style.left=coord[0]+'px';
	r.style.top=coord[1]+'px';
}


function openWait(){
	myDiv=document.getElementById("wait");
	

	SetCoords("wait");
	
	myDiv.style.display='block';
}


function closeWait(){
	myDiv=document.getElementById("wait");
	myDiv.style.display='none';
}

