//открытие нового окна
function winop(imgurl, width, height, winname)
{
    var title = "window";
    im = window.open(imgurl, winname,'top=40,left=40,width='+width+',height='+height+', scrollbars=yes, menu=no,status=no,resizable=no');
    im.focus();
   
}
