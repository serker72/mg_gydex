var xmlHttp=createXmlHttpRequestObject();
var xmlHttp1=createXmlHttpRequestObject();

function createXmlHttpRequestObject()
{
	var xmlHttp;
	
	//��� ����� �� 6 � �����
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
		alert("������ �������� ����� ��������� �������!");
	else
		return xmlHttp;
			
}		


//������� �� �� 
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
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}


//�������� �����
function RemoveDirFunc(name){
	
	//alert("ff!");
	
	
	//wf=document.getElementById("wait");
	//wf.style.display='block';
	openWait();
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}


function handleRR(){
	//����� 4, ����� �������� ����� �������
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//���������� ����� �������
				//alert("ff!");
				ResModifyFolder();
			}
			catch(e){
				alert("������ ������ ������: "+e.toString());
			}
		}else{
			alert("�������� �� ����� ��������� ������:\n"+xmlHttp.statusText);
		}
	}	
}

function handleDel(){
	//����� 4, ����� �������� ����� �������
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//���������� ����� �������
				//alert("ff!");
				ResDel();
			}
			catch(e){
				alert("������ ������ ������: "+e.toString());
			}
		}else{
			alert("�������� �� ����� ��������� ������:\n"+xmlHttp.statusText);
		}
	}	
}

function ResModifyFolder(){
	//alert("ghghghg");
	//return true;
	//��������� ��������� �� �������
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	
	if(unescape(error_code)!="0") {
		//myDiv.innerHTML = "����� ���� ���� � ��!";
		//����� ��������� xml-�����
		
		
		var HTML="";
		HTML += "<strong><em>��������� ������!</em></strong>\n";
		myDiv.innerHTML=HTML;
		
		window.setTimeout("closeWait()", 5000);
		myDiv.style.display='none';
		
	}else{
		//�������� � ��������� �����
		ppp=xmlRoot.getElementsByTagName("path");
		pppp=ppp.item(0).firstChild.data;
		myDiv.style.display='none';
		location.href= location.pathname+"?folder="+pppp;
	}
	
}

function ResDel(){
	//alert("ghghghg");
	//return true;
	//��������� ��������� �� �������
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	
	if(unescape(error_code)!="0") {
		//myDiv.innerHTML = "����� ���� ���� � ��!";
		//����� ��������� xml-�����
		
		
		var HTML="";
		HTML += "<strong><em>��������� ������!</em></strong>\n";
		myDiv.innerHTML=HTML;
		
		window.setTimeout("closeWait()", 5000);
		myDiv.style.display='none';
		
	}else{
		//�������� � ��������� �����
		myDiv.style.display='none';
		location.href=location.pathname+"?folder=";
	}
	
}




/*-------------------------------------------------------------------------------------------------------**/


//�������� �����
function RemoveFileFunc(name){
	
	//wf=document.getElementById("wait");
	//wf.style.display='block';
	openWait();
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}


function handleDelFile(){
	//����� 4, ����� �������� ����� �������
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//���������� ����� �������
				//alert("ff!");
				ResDelFile();
			}
			catch(e){
				alert("������ ������ ������: "+e.toString());
			}
		}else{
			alert("�������� �� ����� ��������� ������:\n"+xmlHttp.statusText);
		}
	}	
}

function ResDelFile(){
	//alert("ghghghg");
	//return true;
	//��������� ��������� �� �������
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	/*pp=xmlRoot.getElementsByTagName("path");
	path=pp.item(0).firstChild.data;
	alert(path);*/
	
	if(unescape(error_code)!="0") {
		//myDiv.innerHTML = "����� ���� ���� � ��!";
		//����� ��������� xml-�����
		
		
		var HTML="";
		HTML += "<strong><em>��������� ������!</em></strong>\n";
		myDiv.innerHTML=HTML;
		
		window.setTimeout("closeWait()", 5000);
		myDiv.style.display='none';
		
	}else{
		myDiv.style.display='none';
		location.reload();
	}
	
}



//�������������� �����
//������� �� �� 
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
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}



function handleRF(){
	//����� 4, ����� �������� ����� �������
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//���������� ����� �������
				//alert("ff!");
				ResModifyFile();
			}
			catch(e){
				alert("������ ������ ������: "+e.toString());
			}
		}else{
			alert("�������� �� ����� ��������� ������:\n"+xmlHttp.statusText);
		}
	}	
}


function ResModifyFile(){
	//alert("ghghghg");
	//return true;
	//��������� ��������� �� �������
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	
	
	if(unescape(error_code)!="0") {
		//myDiv.innerHTML = "����� ���� ���� � ��!";
		//����� ��������� xml-�����
		
		
		var HTML="";
		HTML += "<strong><em>��������� ������!</em></strong>\n";
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



//��������� �����
function MarkFile(name){
	
	openWait();
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}

//������ ��������� �����
function UnmarkFile(name){
	
	openWait();
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}



function handleMark(){
	//����� 4, ����� �������� ����� �������
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//���������� ����� �������
				//alert("ff!");
				ResMark();
			}
			catch(e){
				alert("������ ������ ������: "+e.toString());
			}
		}else{
			alert("�������� �� ����� ��������� ������:\n"+xmlHttp.statusText);
		}
	}	
}


function ResMark(){
	//alert("ghghghg");
	//return true;
	//��������� ��������� �� �������
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

//��������� ������ ������
function SelectArr(arr){
	if(arr.length==0) return false;
	//alert(arr.length);
	//coord=new Array(); coord[0]=10; coord[1]=10;
	openWait();
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}

function handleMarkAll(){
	//����� 4, ����� �������� ����� �������
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//���������� ����� �������
				//alert("ff!");
				ResMarkAll();
			}
			catch(e){
				alert("������ ������ ������: "+e.toString());
			}
		}else{
			alert("�������� �� ����� ��������� ������:\n"+xmlHttp.statusText);
		}
	}	
}

function ResMarkAll(){
	//alert("ghghghg");
	//return true;
	//��������� ��������� �� �������
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

//������ ��������� ������ ������
function UnSelectArr(){
	openWait();
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}

function handleUnMarkAll(){
	//����� 4, ����� �������� ����� �������
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//���������� ����� �������
				//alert("ff!");
				ResUnMarkAll();
			}
			catch(e){
				alert("������ ������ ������: "+e.toString());
			}
		}else{
			alert("�������� �� ����� ��������� ������:\n"+xmlHttp.statusText);
		}
	}	
}

function ResUnMarkAll(){
	//alert("ghghghg");
	//return true;
	//��������� ��������� �� �������
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


//�������� ������ ������
function delSelected(){
	//alert('qqq');
	
	openWait();
	
	//��������� ������
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
			alert("���������� ����������� � ��������!:\n"+e.toString());
		}
	}
}

function handleDelAll(){
	//����� 4, ����� �������� ����� �������
	if(xmlHttp.readyState==4){
		if(xmlHttp.status==200){
			try{
				//���������� ����� �������
				//alert("ff!");
				ResDelAll();
			}
			catch(e){
				alert("������ ������ ������: "+e.toString());
			}
		}else{
			alert("�������� �� ����� ��������� ������:\n"+xmlHttp.statusText);
		}
	}	
}

function ResDelAll(){
	//alert("ghghghg");
	//return true;
	//��������� ��������� �� �������
	response=xmlHttp.responseXML;
	
	xmlRoot=response.documentElement;
	errorl=xmlRoot.getElementsByTagName("errcode");
	error_code=errorl.item(0).firstChild.data;
	
	myDiv=document.getElementById("wait");
	

		
	myDiv.style.display='none';
	location.reload();
	
}