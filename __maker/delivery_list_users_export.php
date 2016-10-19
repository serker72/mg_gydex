<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');
require_once('../classes/v2/delivery_lists.class.php');

require_once('../classes/PHPExcel.php');


//административная авторизация
require_once('inc/adm_header.php');


 $rights_man=new DistrRightsManager;
if(!$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 26)) {
	header('Location: no_rights.php');
	die();		
}

 
if(isset($_GET['from'])) $from=abs((int)$_GET['from']);
else $from=0; 

if(isset($_GET['to_page'])) $to_page=abs((int)$_GET['to_page']);
else $to_page=ITEMS_PER_PAGE;
 
 if(!isset($_GET['id']))
	if(!isset($_POST['id'])) {
		 header('Location: index.php');
		 die();
		 
	}
	else $id = $_POST['id'];		
else $id = $_GET['id'];		
$id=abs((int)$id);

$_list=new Delivery_UserGroup;
$_razd=new Delivery_ListItem;
$razd=$_razd->GetItemById($id);

if($razd===false){
	 header('Location: index.php');
		 die();
		 
}

$rights_man=new DistrRightsManager;
if($rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'r', 26)) {
	  
	  
  
}else{
	header('Location: no_rights.php');
	 die();		
}


 
$_segments=new Delivery_SegmentGroup;

if(isset($_GET['status'])) $status=(int)$_GET['status'];
else $status=-1; 


if(isset($_GET['segment'])) $segment=(int)$_GET['segment'];
else $segment=-1; 

if(!isset($_GET['sortmode'])){
	$sortmode=1;	
}else{
	$sortmode=abs((int)$_GET['sortmode']);
}

  
 
 
 
 
 
$decorator=new DBDecorator;

$_list=new Delivery_UserGroup;




if(($status==1)||($status==0)) $decorator->AddEntry(new SqlEntry('p.is_subscribed',$status, SqlEntry::E));

$decorator->AddEntry(new UriEntry('status',$status));




if($segment>0){
	$decorator->AddEntry(new SqlEntry('p.id','select user_id from delivery_user_segment where segment_id ="'.$segment.'"', SqlEntry::IN_SQL));
			
}elseif($segment==0){
	$decorator->AddEntry(new SqlEntry('p.id','select user_id from delivery_user_segment where segment_id in (select id from delivery_segment where list_id="'.$id.'")', SqlEntry::NOT_IN_SQL));
}

$decorator->AddEntry(new UriEntry('segment',$segment));

 		


$decorator->AddEntry(new UriEntry('to_page',$to_page));
$decorator->AddEntry(new UriEntry('id',$id));




$decorator->AddEntry(new UriEntry('sortmode',$sortmode));
switch($sortmode){
		case 0:
			$decorator->AddEntry(new SqlOrdEntry('p.email',SqlOrdEntry::DESC));
		break;
		case 1:
			$decorator->AddEntry(new SqlOrdEntry('p.email',SqlOrdEntry::ASC));
		break;
		case 2:
			$decorator->AddEntry(new SqlOrdEntry('p.is_subscribed',SqlOrdEntry::DESC));
		break;
		case 3:
			$decorator->AddEntry(new SqlOrdEntry('p.is_subscribed',SqlOrdEntry::ASC));
		break;
		case 4:
			$decorator->AddEntry(new SqlOrdEntry('p.f',SqlOrdEntry::DESC));
		break;
		case 5:
			$decorator->AddEntry(new SqlOrdEntry('p.f',SqlOrdEntry::ASC));
		break;
		case 6:
			$decorator->AddEntry(new SqlOrdEntry('p.i',SqlOrdEntry::DESC));
		break;
		case 7:
			$decorator->AddEntry(new SqlOrdEntry('p.i',SqlOrdEntry::ASC));
		break;
		case 8:
			$decorator->AddEntry(new SqlOrdEntry('p.o',SqlOrdEntry::DESC));
		break;
		case 9:
			$decorator->AddEntry(new SqlOrdEntry('p.o',SqlOrdEntry::ASC));
		break;
		
		
		default:
			$decorator->AddEntry(new SqlOrdEntry('p.email',SqlOrdEntry::ASC));

 		
			$decorator->AddEntry(new SqlOrdEntry('p.id',SqlOrdEntry::DESC));
		break;	
		
	}		
 
		
 
 
 $_list->GetItemsById($id, 'delivery/users.html', 0, 1000000, $decorator, 
	$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'w', 26) ,
	$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'a', 26),
	$rights_man->CheckAccess($global_profile['login'], $global_profile['passw'], 'd', 26),
	false,'', $alls );
 
 
 
  
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator(iconv('windows-1251', 'utf-8',SITETITLE))
							 ->setLastModifiedBy(iconv('windows-1251', 'utf-8',SITETITLE))
							 ->setTitle(iconv('windows-1251', 'utf-8',"Экспорт списка подписчиков ".$razd['name']))
							 ->setSubject(iconv('windows-1251', 'utf-8',"Экспорт списка подписчиков ".$razd['name']))
							 ->setDescription(iconv('windows-1251', 'utf-8',"Экспорт списка подписчиков ".$razd['name']))
							 ->setKeywords("")
							 ->setCategory(iconv('windows-1251', 'utf-8',"Отчет"));

 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Список подписчиков '.$razd['name'].'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');



$objPHPExcel->getDefaultStyle()->getFont()
    ->setName('Arial')
    ->setSize(9);

$working_row=1;	
$objPHPExcel->setActiveSheetIndex(0)
            
            ->setCellValue('A'.$working_row, iconv('windows-1251', 'utf-8','Адрес email '))
			->setCellValue('B'.$working_row, iconv('windows-1251', 'utf-8','Фамилия'))
			->setCellValue('C'.$working_row, iconv('windows-1251', 'utf-8','Имя'))
			->setCellValue('D'.$working_row, iconv('windows-1251', 'utf-8','Отчество'));
			
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$working_row.':D'.($working_row))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);			
$styleArray = array(
	 'font' => array(
		'bold'=>true
	),
	'borders' => array(
		'allborders'=> array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
		),
	)
);
$objPHPExcel->getActiveSheet()->getStyle('A'.$working_row.':D'.($working_row))->applyFromArray($styleArray);

foreach($alls as $k=>$v){
	$working_row++;
	
	$objPHPExcel->setActiveSheetIndex(0)
            
            ->setCellValue('A'.$working_row, iconv('windows-1251', 'utf-8',$v['email']))
			->setCellValue('B'.$working_row, iconv('windows-1251', 'utf-8',$v['f']))
			->setCellValue('C'.$working_row, iconv('windows-1251', 'utf-8',$v['i']))
			->setCellValue('D'.$working_row, iconv('windows-1251', 'utf-8',$v['o']));
			
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$working_row.':D'.($working_row))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);	
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$working_row.':D'.($working_row))->getAlignment()->setWrapText(true);		
	$styleArray = array(
		 
		'borders' => array(
			'allborders'=> array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$working_row.':D'.($working_row))->applyFromArray($styleArray);
		
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);



$objWriter->save('php://output');
die();
	 
 
?>