<?
session_start();
require_once('../classes/global.php');
require_once('../classes/mmenuitem.php');
require_once('../classes/v2/delivery.class.php');
require_once('../classes/smarty/SmartyAj.class.php');

require_once('../classes/phpmailer/class.phpmailer.php');

$_di=new Delivery_Item;
$_ds=new Delivery_SubscriberItem;

$_messages_per_call=15;

//������ ����� - ���������� �� 15 ����������� ��������, � ���� is_sent=0
//�������� �.�. � �������� 4,2
//���������� �� ��������������� ������
//������� � is_sent 1
//���������, ���� �� �������� ��� �������� - ������ ������ 3 ���������� � ������ ���� ����� �������
$delivery_ids=array();
$sql='select p.id as p_id, p.delivery_id, p.is_sent,
u.*
 from delivery_subscriber as p 
 inner join delivery_user as u on u.id=p.user_id and u.is_subscribed=1
 inner join delivery as dev on dev.id=p.delivery_id
 where p.is_sent="0" and dev.schedule_pdate<"'.time().'"
 
	and dev.status_id in(2,4) 
	order by p.id asc
	limit '.$_messages_per_call;
//echo $sql;	
	
$set=new mysqlSet($sql);
$rs=$set->GetResult();
$rc=$set->GetResultNumRows();	
for($i=0; $i<$rc; $i++){
	$f=mysqli_fetch_array($rs);
	foreach($f as $k=>$v) $f[$k]=stripslashes($v);
	//print_r($f);				
	if(!in_array($f['delivery_id'], $delivery_ids)) $delivery_ids[]=$f['delivery_id'];
	
	$di=$_di->getitembyid($f['delivery_id']);
	
	$_fl=new Delivery_Fields;
	$_fl->ProcessFields($f['id'], $di);
	
	//!!!!!!!!!!!! �������� ������� � html
	
	//print_r($di);
	
	$sm=new SmartyAj;
	$sm->assign('SITEURL', SITEURL);
	$html=$sm->fetch('page_email_top.html');
	
	
	$html.=$di['html_content'];
	
	//
	if($di['has_tracking']) $html.='<img src="'.SITEURL.'/img/campaign_'.$f['id'].'_'.$f['delivery_id'].'.png" width="1" height="1" >';
	
	
	$sm=new SmartyAj;
	$html.=$sm->fetch('page_email_bottom.html');
	
	//echo htmlspecialchars($html);
	
	$mail = new PHPMailer();
	
	if($di['to_is_personal']) $mail->AddAddress($f['email'],  $di['to_field']);
	else $mail->AddAddress($f['email'],  $f['email']);
	
	$mail->SetFrom($di['from_email'], $di['from_name']);
	
	$mail->Subject = $di['topic']; 
	$mail->Body=$html;
	$mail->AltBody=$di['plain_text_content'];
	 
	  
	$mail->CharSet = "windows-1251";
	$mail->IsHTML(true);
	
	$mail->Send();
	
	
	$_ds->Edit($f['p_id'], array('is_sent'=>1, 'pdate'=>time()));
}

//��������� � �������� ������� ���������

$sql1='select * from delivery as dev 
 where  dev.schedule_pdate<"'.time().'"
	and dev.status_id in(2,4) 
	order by id asc
';
//echo $sql;	
	
$set1=new mysqlSet($sql1);
$rs1=$set1->GetResult();
$rc1=$set1->GetResultNumRows();	
for($i1=0; $i1<$rc1; $i1++){
	$g=mysqli_fetch_array($rs1);
	$d_id=$g['id'];
	
 
	//������� �������� (��������)
	$sql='select count(ds.id) from delivery_subscriber as ds
	inner join delivery as d on d.id=ds.delivery_id
	inner join delivery_user as u on u.id=ds.user_id
	where 
		ds.delivery_id="'.$d_id.'"
		and ds.is_sent=1
		and u.is_subscribed=1
		';
	
		
	$set=new mysqlSet($sql);
	$rs=$set->GetResult();	
	$f=mysqli_fetch_array($rs);
	$sent=(int)$f[0];
	
	//������� ������������� (��������)
	$sql='select count(ds.id) from delivery_subscriber as ds
	inner join delivery as d on d.id=ds.delivery_id
	inner join delivery_user as u on u.id=ds.user_id
	where 
		ds.delivery_id="'.$d_id.'"
		 
		and u.is_subscribed=1
		';
	//echo $sql;
		
	$set=new mysqlSet($sql);
	$rs=$set->GetResult();	
	$f=mysqli_fetch_array($rs);
	$total=(int)$f[0];
	
	
	
	$params=array();
	
	
	if((int)$total>(int)$sent){
		echo "more $d_id $sent vs $total ";
		$params['status_id']=2;	
		if($g['status_id']!=2) {
			$params['pdate_status_change']=time();
			$_di->Edit($d_id, $params);
		}
	}else{
		echo "sent $d_id $sent vs $total ";
		$params['status_id']=3;
		if($g['status_id']!=3){
			 $params['pdate_status_change']=time();
			 $_di->Edit($d_id, $params);
		}
	}
	
	
	
		
	
}


?>