$(document).ready(function(){
// �������� ��� ���������
$('.menu li ul').hide ();
// �� ���� ������� � ����������� ���� � ��������� � ���� ����� arrow (���������)
$('li li:has(li)').find('a:first').addClass ('arrow');
// ��������� ������� li:hover
 
$('.menu li').hover (
// over
function () {
// ��� ������� li ������� ����� � ������ �����
$(this).addClass('active');
// ���������� ������� ������
$('ul:first', this).show();
},
// out
function () {
// ����� ������ ���������� �����
$(this).removeClass('active');
// �������� ������
$('ul:first', this).hide ();
}
);
});