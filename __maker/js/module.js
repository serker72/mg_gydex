var xmlHttp=createXmlHttpRequestObject();
var xmlHttp1=createXmlHttpRequestObject();

function createXmlHttpRequestObject()
{
	var xmlHttp;
	
	//все кроме ИЕ 6 и ранее
	try
	{
			xmlHttp=new XMLHttpRequest();
			flagie=0;
	}
	catch(e)
	{
			//IE 6
			var xmlHttpVersions=new Array("MSXML2.XMLHTTP.6.0",
										  "MSXML2.XMLHTTP.5.0",
										  "MSXML2.XMLHTTP.4.0",
										  "MSXML2.XMLHTTP.3.0",
										  "MSXML2.XMLHTTP",
										  "Microsoft.XMLHTTP");
			flagie=1;										  
			// perebor prog_id
			for(var i=0; i<xmlHttpVersions.length&&!xmlHttp; i++){
				try{
					//
					xmlHttp=new ActiveXObject(xmlHttpVersions[i]);
				}catch(e){}
			}
	}
			
	//vernem object ili owibka
	if(!xmlHttp)
		alert("Ошибка создания этого мудреного объекта!");
	else
		return xmlHttp;
			
}		


//нажатие на ОК 
function ModifyFolder(){
	pt=document.getElementById("folderer");
	
	
	action=document.getElementById("action");
	if(action.value==1){
		fname=document.getElementById("foldername");
		oldname=document.getElementById("oldname");
		fpath=document.getElementById("folderpath");
		if((fname.value.length==0)||(fname.value==oldname.value)) return false;
	}
	
	if(action.value==0){
		fname=document.getElementById("foldername");
		fpath=document.getElementById("folderpath");
		if(fname.value.length==0) return false;
	}
	
	pt.className="reninvis";
	
	
	//wf=document.getElementById("wait");
	//wf.style.display='block';
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
			if(action.value==1){
				var params="action="+encodeURIComponent(action.value)+"&path="+encodeURIComponent(fpath.value)+"&oldname="+encodeURIComponent(oldname.value)+"&name="+encodeURIComponent(fname.value);
				xmlHttp.open("POST", "js/modfolder.php", true);
				//xmlHttp.open("GET", "js/modfolder.php?"+params, true);
				xmlHttp.onreadystatechange=handleRR;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert("ff!");
			}
			if(action.value==0){
				var params="action="+encodeURIComponent(action.value)+"&path="+encodeURIComponent(fpath.value)+"&name="+encodeURIComponent(fname.value);
				xmlHttp.open("POST", "js/modfolder.php", true);
				//xmlHttp.open("GET", "js/modfolder.php?"+params, true);
				xmlHttp.onreadystatechange=handleRR;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert("ff!");
			}
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}


//удаление папки
function RemoveDirFunc(name){
	
	//alert("ff!");
	
	
	//wf=document.getElementById("wait");
	//wf.style.display='block';
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
				var params="action=2"+"&path="+encodeURIComponent(name);
				xmlHttp.open("POST", "js/modfolder.php", true);
				xmlHttp.onreadystatechange=handleDel;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert("ff!");
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}


function handleRR(){
	//когда 4, можно прочесть ответ сервера
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//обработать ответ сервера
				//alert("ff!");
				ResModifyFolder();
			}
			catch(e){
				alert("Ошибка чтения ответа: "+e.toString());
			}
		}else{
			alert("Проблемы во время получения данных:\n"+xmlHttp.statusText);
		}
	}	
}

function handleDel(){
	//когда 4, можно прочесть ответ сервера
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//обработать ответ сервера
				//alert("ff!");
				ResDel();
			}
			catch(e){
				alert("Ошибка чтения ответа: "+e.toString());
			}
		}else{
			alert("Проблемы во время получения данных:\n"+xmlHttp.statusText);
		}
	}	
}

function ResModifyFolder(){
	//alert("ghghghg");
	//return true;
	//прочитать сообщение от сервера
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	
	if(unescape(error_code)!="0") {
		//myDiv.innerHTML = "Такой юзер есть в БД!";
		//будем разбирать xml-ответ
		
		
		var HTML="";
		HTML += "<strong><em>Произошла ошибка!</em></strong>\n";
		myDiv.innerHTML=HTML;
		
		window.setTimeout("closeWait()", 5000);
		myDiv.style.display='none';
		
	}else{
		//перейдем в созданную папку
		ppp=xmlRoot.getElementsByTagName("path");
		pppp=ppp.item(0).firstChild.data;
		myDiv.style.display='none';
		location.href= location.pathname+"?folder="+pppp;
	}
	
}

function ResDel(){
	//alert("ghghghg");
	//return true;
	//прочитать сообщение от сервера
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	
	if(unescape(error_code)!="0") {
		//myDiv.innerHTML = "Такой юзер есть в БД!";
		//будем разбирать xml-ответ
		
		
		var HTML="";
		HTML += "<strong><em>Произошла ошибка!</em></strong>\n";
		myDiv.innerHTML=HTML;
		
		window.setTimeout("closeWait()", 5000);
		myDiv.style.display='none';
		
	}else{
		//перейдем в созданную папку
		myDiv.style.display='none';
		location.href=location.pathname+"?folder=";
	}
	
}




/*-------------------------------------------------------------------------------------------------------**/


//удаление файла
function RemoveFileFunc(name){
	
	//wf=document.getElementById("wait");
	//wf.style.display='block';
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
				var params="action=2"+"&path="+encodeURIComponent(name);
				xmlHttp.open("POST", "js/modfile.php", true);
				xmlHttp.onreadystatechange=handleDelFile;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert(name);
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}


function handleDelFile(){
	//когда 4, можно прочесть ответ сервера
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//обработать ответ сервера
				//alert("ff!");
				ResDelFile();
			}
			catch(e){
				alert("Ошибка чтения ответа: "+e.toString());
			}
		}else{
			alert("Проблемы во время получения данных:\n"+xmlHttp.statusText);
		}
	}	
}

function ResDelFile(){
	//alert("ghghghg");
	//return true;
	//прочитать сообщение от сервера
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	/*pp=xmlRoot.getElementsByTagName("path");
	path=pp.item(0).firstChild.data;
	alert(path);*/
	
	if(unescape(error_code)!="0") {
		//myDiv.innerHTML = "Такой юзер есть в БД!";
		//будем разбирать xml-ответ
		
		
		var HTML="";
		HTML += "<strong><em>Произошла ошибка!</em></strong>\n";
		myDiv.innerHTML=HTML;
		
		window.setTimeout("closeWait()", 5000);
		myDiv.style.display='none';
		
	}else{
		myDiv.style.display='none';
		location.reload();
	}
	
}



//переименование файла
//нажатие на ОК 
function ModifyFile(){
	pt=document.getElementById("filerer");
	
	
	action=document.getElementById("fileaction");
	if(action.value==1){
		fname=document.getElementById("filename");
		oldname=document.getElementById("oldfilename");
		fpath=document.getElementById("filepath");
		if((fname.value.length==0)||(fname.value==oldname.value)) return false;
	}
	
	
	pt.className="reninvis";
	
	
	//wf=document.getElementById("wait");
	//wf.style.display='block';
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
			if(action.value==1){
				var params="action="+encodeURIComponent(action.value)+"&path="+encodeURIComponent(fpath.value)+"&oldname="+encodeURIComponent(oldname.value)+"&name="+encodeURIComponent(fname.value);
				xmlHttp.open("POST", "js/modfile.php", true);
				//xmlHttp.open("GET", "js/modfolder.php?"+params, true);
				xmlHttp.onreadystatechange=handleRF;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert(fpath.value);
			}
			
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}



function handleRF(){
	//когда 4, можно прочесть ответ сервера
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//обработать ответ сервера
				//alert("ff!");
				ResModifyFile();
			}
			catch(e){
				alert("Ошибка чтения ответа: "+e.toString());
			}
		}else{
			alert("Проблемы во время получения данных:\n"+xmlHttp.statusText);
		}
	}	
}


function ResModifyFile(){
	//alert("ghghghg");
	//return true;
	//прочитать сообщение от сервера
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	
	if(unescape(error_code)!="0") {
		//myDiv.innerHTML = "Такой юзер есть в БД!";
		//будем разбирать xml-ответ
		
		
		var HTML="";
		HTML += "<strong><em>Произошла ошибка!</em></strong>\n";
		myDiv.innerHTML=HTML;
		
		window.setTimeout("closeWait()", 5000);
		myDiv.style.display='none';
		
	}else{
		/*pp=xmlRoot.getElementsByTagName("path");
	path=pp.item(0).firstChild.data;
	alert(path);	*/	
		
		myDiv.style.display='none';
		location.reload();
	}
	
}



//выделение файла
function MarkFile(name){
	
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
				var params="action=3"+"&name="+encodeURIComponent(name);
				xmlHttp.open("POST", "js/modfile.php", true);
				//xmlHttp.open("GET", "js/modfolder.php?"+params, true);
				xmlHttp.onreadystatechange=handleMark;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert(name);
			
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}

//снятие выделения файла
function UnmarkFile(name){
	
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
				var params="action=4"+"&name="+encodeURIComponent(name);
				xmlHttp.open("POST", "js/modfile.php", true);
				//xmlHttp.open("GET", "js/modfolder.php?"+params, true);
				xmlHttp.onreadystatechange=handleMark;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert(fpath.value);
			
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}



function handleMark(){
	//когда 4, можно прочесть ответ сервера
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//обработать ответ сервера
				//alert("ff!");
				ResMark();
			}
			catch(e){
				alert("Ошибка чтения ответа: "+e.toString());
			}
		}else{
			alert("Проблемы во время получения данных:\n"+xmlHttp.statusText);
		}
	}	
}


function ResMark(){
	//alert("ghghghg");
	//return true;
	//прочитать сообщение от сервера
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
//	alert('');
	
	act=xmlRoot.getElementsByTagName("action");
	action=act.item(0).firstChild.data;
	
	nme=xmlRoot.getElementsByTagName("name");
	nm=nme.item(0).firstChild.data;
	
	mdd=document.getElementById(nm);
	
	if(action==4) mdd.className='fileitem';
	if(action==3) mdd.className='fileitem_marked';	
		
	myDiv.style.display='none';
	
	
}

//выделение многих файлов
function SelectArr(arr){
	if(arr.length==0) return false;
	//alert(arr.length);
	//coord=new Array(); coord[0]=10; coord[1]=10;
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
				var params="action=5";//+"&name="+encodeURIComponent(name);
				for(i=0;i<arr.length;i++) params+="&arr"+i+"="+arr[i];
				
				xmlHttp.open("POST", "js/modfile.php", true);
				//xmlHttp.open("GET", "js/modfolder.php?"+params, true);
				xmlHttp.onreadystatechange=handleMarkAll;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert(name);
			
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}

function handleMarkAll(){
	//когда 4, можно прочесть ответ сервера
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//обработать ответ сервера
				//alert("ff!");
				ResMarkAll();
			}
			catch(e){
				alert("Ошибка чтения ответа: "+e.toString());
			}
		}else{
			alert("Проблемы во время получения данных:\n"+xmlHttp.statusText);
		}
	}	
}

function ResMarkAll(){
	//alert("ghghghg");
	//return true;
	//прочитать сообщение от сервера
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	
	nameArray=xmlRoot.getElementsByTagName("nameitem");	
	//alert(nameArray.length);
	
	for(i=0;i<nameArray.length;i++){
		try{
			//ni=xmlRoot.getElementsByTagName("name");
			nn=nameArray.item(i).firstChild.data;
			//alert(nn);
			st=new String(nn);
			while(st.indexOf("@1")!=-1){
				st=st.substring(0,st.indexOf("@1"))+"/"+st.substring(st.indexOf("@1")+2,st.length);
			}
			//alert(st);
			
			mdd=document.getElementById(st);
			mdd.className='fileitem_marked';	
			
			mdd=document.getElementById("ch_"+st);
			mdd.checked=true;
		}catch(e){
		
		}
	}
		
	myDiv.style.display='none';
	
	
}

//снятие выделения многих файлов
function UnSelectArr(){
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
				var params="action=6";//+"&name="+encodeURIComponent(name);
				//for(i=0;i<arr.length;i++) params+="&arr"+i+"="+arr[i];
				
				xmlHttp.open("POST", "js/modfile.php", true);
				//xmlHttp.open("GET", "js/modfolder.php?"+params, true);
				xmlHttp.onreadystatechange=handleUnMarkAll;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert(name);
			
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}

function handleUnMarkAll(){
	//когда 4, можно прочесть ответ сервера
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//обработать ответ сервера
				//alert("ff!");
				ResUnMarkAll();
			}
			catch(e){
				alert("Ошибка чтения ответа: "+e.toString());
			}
		}else{
			alert("Проблемы во время получения данных:\n"+xmlHttp.statusText);
		}
	}	
}

function ResUnMarkAll(){
	//alert("ghghghg");
	//return true;
	//прочитать сообщение от сервера
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	nameArray=xmlRoot.getElementsByTagName("nameitem");	
	//alert(nameArray.length);
	
	for(i=0;i<nameArray.length;i++){
		try{
			//ni=xmlRoot.getElementsByTagName("name");
			nn=nameArray.item(i).firstChild.data;
			//alert(nn);
			st=new String(nn);
			while(st.indexOf("@1")!=-1){
				st=st.substring(0,st.indexOf("@1"))+"/"+st.substring(st.indexOf("@1")+2,st.length);
			}
			//alert(st);
			
			
			
			mdd=document.getElementById("ch_"+st);
			mdd.checked=false;
		}catch(e){
		
		}
	}
	
	myDiv.style.display='none';
	
	location.reload();
	
}


//удаление многих файлов
function delSelected(){
	//alert('qqq');
	
	openWait();
	
	//формируем запрос
	if(xmlHttp){
		//popytka connect
		try{
				var params="action=7";//+"&name="+encodeURIComponent(name);
				//for(i=0;i<arr.length;i++) params+="&arr"+i+"="+arr[i];
				
				xmlHttp.open("POST", "js/modfile.php", true);
				xmlHttp.onreadystatechange=handleDelAll;
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-1251');			
				xmlHttp.send(params);
				//alert(params);
			
		}
		catch(e){
		//error!
			alert("Невозможно соединиться с сервером!:\n"+e.toString());
		}
	}
}

function handleDelAll(){
	//когда 4, можно прочесть ответ сервера
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//обработать ответ сервера
				//alert("ff!");
				ResDelAll();
			}
			catch(e){
				alert("Ошибка чтения ответа: "+e.toString());
			}
		}else{
			alert("Проблемы во время получения данных:\n"+xmlHttp.statusText);
		}
	}	
}

function ResDelAll(){
	//alert("ghghghg");
	//return true;
	//прочитать сообщение от сервера
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	

		
	myDiv.style.display='none';
	location.reload();
	
}