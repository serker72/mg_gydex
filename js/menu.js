$(document).ready(function(){
// скрываем все подуровни
$('.menu li ul').hide ();
// во всех пунктах с подпунктами ищем и добавляем к нему класс arrow (стрелочку)
$('li li:has(li)').find('a:first').addClass ('arrow');
// описываем событие li:hover
 
$('.menu li').hover (
// over
function () {
// для красоты li добавим класс с другим фоном
$(this).addClass('active');
// отображаем скрытый список
$('ul:first', this).show();
},
// out
function () {
// нужно убрать добавлений класс
$(this).removeClass('active');
// скрываем список
$('ul:first', this).hide ();
}
);
});