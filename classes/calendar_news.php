<?
require_once('calendar.php');
require_once('newsgroup.php');
require_once('resoursefile.php');
//����� ��� ������ � ���������� � ���������

class CalendarNews extends Calendar{

	//����� ���� ����
	protected $tr_class;
	//����� ���� ������
	protected $td_class;
	//����� ���� ������ ���������
	protected $td_class_holid;
	//����� ��� ��������
	protected $updiv_class;	
	protected $alldiv_class;		
	//����� ��� ������
	protected $link_class;
	//����� ��� ������
	protected $nextprlink_class;	
	//����� ��� ������ �������� ���
	protected $current_class;
	//����� ��� ������ ���������� ���
	protected $chosen_style;
	//����� ��� ���������
	protected $holiday_class;
	//����� ��� �-�� �������
	protected $order_class;	
	//������ ��� ������ ���� ������
	protected $next_symb;
	//������ ��� ������ ���� ������
	protected $prev_symb;
	//��� �������� ��� ������
	protected $pagename;
	//��� ��������� ����
	protected $date_param_name;
	//������ ������ ����������
	protected $params;
	//��������� ����
	protected $init_date;
	//���������� ��� ���������
	protected $calen;
	//������� ���������
	protected $matr;
	//������
	protected $monthes;
	
	//��� ������ ��� �������� ��������
	protected $ng;
	//���������� �� ��������
	protected $rf;
	//���� ���������
	protected $lang_id;
	
	public function __construct($lang_id=LANG_CODE){
		$this->init($lang_id);
	}
	
	protected function init($lang_id){
		$this->lang_id=$lang_id;
		$this->tr_class='calen_tr';
		$this->td_class='calen_td';
		$this->td_class_holid='calen_td_holid';
		$this->updiv_class='updiv';		
		$this->alldiv_class='updiv';				
		$this->order_class='orddiv';						
		$this->link_class='calen_a';
		$this->nextprlink_class='';		
		$this->current_class='current_a';
		$this->chosen_style='font-size: +2pt;';
		$this->holiday_class='holid_a';
		$this->next_symb='&gt;&gt;';
		$this->prev_symb='&lt;&lt;';	
		$this->pagename='page.php';	
		$this->date_param_name='pdate';
		$this->params='';
		$this->init_date=date('Y-m-d');
		
		$this->ng=new NewsGroup();
		$this->rf=new ResFile(ABSPATH.'cnf/resources.txt');
		$this->monthes[1]=$this->rf->GetValue('news_block.php','jan',$lang_id);
		$this->monthes[2]=$this->rf->GetValue('news_block.php','feb',$lang_id);
		$this->monthes[3]=$this->rf->GetValue('news_block.php','mar',$lang_id);
		$this->monthes[4]=$this->rf->GetValue('news_block.php','apr',$lang_id);
		$this->monthes[5]=$this->rf->GetValue('news_block.php','may',$lang_id);
		$this->monthes[6]=$this->rf->GetValue('news_block.php','june',$lang_id);
		$this->monthes[7]=$this->rf->GetValue('news_block.php','july',$lang_id);
		$this->monthes[8]=$this->rf->GetValue('news_block.php','aug',$lang_id);
		$this->monthes[9]=$this->rf->GetValue('news_block.php','sept',$lang_id);
		$this->monthes[10]=$this->rf->GetValue('news_block.php','oct',$lang_id);
		$this->monthes[11]=$this->rf->GetValue('news_block.php','nov',$lang_id);
		$this->monthes[12]=$this->rf->GetValue('news_block.php','dec',$lang_id);
		
		
	}
	
	//����� ���������
	public function Draw($init_date,$pagename,$date_param_name,$params,$chosen_date,$mid){
		$this->calen='';
		$this->init_date=$init_date;
		$this->pagename=$pagename;
		$this->date_param_name=$date_param_name;
		$this->params=$params;
		
		//��������� 1� ����� ������ � ��� ���� ������
		$curr_date=time();
		
		$date = mktime(0,0,0,substr($this->init_date,5,2),substr($this->init_date,8,2),substr($this->init_date,0,4));	//��������� ����
		
		//����� ��� ������ 1�� �����
		$no_day_1 = date('w',mktime(0,0,0,substr($this->init_date,5,2),1,substr($this->init_date,0,4)));
		
		//echo $no_day_1;
		
		//����, ����� �������
		$counter=1;
		$ech_date=mktime(0,0,0,date('m',$date),$counter,date('Y',$date));
		$this->calen.='<div class="'.$this->alldiv_class.'">';
		$this->calen.='<div class="'.$this->updiv_class.'">';
		
		//������ �� ���� � ���� ������
		if(( (int)date('m',$date)==1 )||( (int)date('m',$date)==12 )){
			if( (int)date('m',$date)==1 ){
				$nextdate=date('Y',$date).'-02-01';
				$prevdate=((int)date('Y',$date)-1).'-12-01';
			}
			if( (int)date('m',$date)==12 ){
				$nextdate=((int)date('Y',$date)+1).'-01-01';
				$prevdate=date('Y',$date).'-11-01';
			}
		}else{
			$nextdate=date('Y',$date).'-'.sprintf("%'02d",((int)date('m',$date)+1)).'-'.date('d',$date);
			$prevdate=date('Y',$date).'-'.sprintf("%'02d",((int)date('m',$date)-1)).'-'.date('d',$date);;
		}
		
		$this->calen.=$this->rf->GetValue('news_block.php','today',$this->lang_id).'&nbsp;<a href="'.$this->pagename.'?'.$this->date_param_name.'='.date('Y-m-d').$this->params.'">'.date('d.m.Y').'</a>&nbsp;<br>';
		
		
		$this->calen.='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.$prevdate.$this->params.'" class="'.$this->nextprlink_class.'">'.$this->prev_symb.'</a>&nbsp;';
		
		$this->calen.=' '.$this->monthes[(int)date('m',$date)].' '.date('Y',$date).' ';
		
		$this->calen.='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.$nextdate.$this->params.'" class="'.$this->nextprlink_class.'">'.$this->next_symb.'</a><br />';
		$this->calen.='</div>';
		$this->calen.='</div>';
		
				
		for($i=1;$i<=6;$i++){
			//������
			//������� ��������� �������
			if($i==1){
				$this->matr[0][0]=$this->rf->GetValue('news_block.php','su',$this->lang_id);
				$this->matr[0][1]=$this->rf->GetValue('news_block.php','mo',$this->lang_id);					
				$this->matr[0][2]=$this->rf->GetValue('news_block.php','tu',$this->lang_id);					
				$this->matr[0][3]=$this->rf->GetValue('news_block.php','we',$this->lang_id);					
				$this->matr[0][4]=$this->rf->GetValue('news_block.php','th',$this->lang_id);					
				$this->matr[0][5]=$this->rf->GetValue('news_block.php','fr',$this->lang_id);					
				$this->matr[0][6]=$this->rf->GetValue('news_block.php','sa',$this->lang_id);		
			}
			
			for($j=0;$j<=6;$j++){
				//���
				if($i==1){
					//1� ������, ���������, ������ �� ������ ������
					if($no_day_1<=$j) {
						//�������
						//!!!
						$cou=$this->ng->CalcItemsByDateId($mid, date('Y-m-d',$ech_date), $this->lang_id);
						if($cou>0){
							$this->matr[$i][$j]='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.date('Y-m-d',$ech_date).$this->params.'" class="';
	
							if(($j==0)||($j==6)) $this->matr[$i][$j].=$this->holiday_class.'"';
							else $this->matr[$i][$j].=$this->link_class.'"';
							
							if($chosen_date==$ech_date) $this->matr[$i][$j].=' style="'.$this->chosen_style.'" ';
							$this->matr[$i][$j].='>'.date('j',$ech_date).'</a>';
						}else{
							/*if(($j==0)||($j==6)) $this->matr[$i][$j]='<span class="'.$this->holiday_class.'" >'.date('j',$ech_date).'</span>';
							else */$this->matr[$i][$j]=''.date('j',$ech_date).'';
						}
						
						$counter++;
						$ech_date=mktime(0,0,0,date('m',$date),$counter,date('Y',$date));
					}else {
						$this->matr[$i][$j]='&nbsp;&nbsp;';
					}
				}else{
					//�� 1� ������, ������ ��������� ������������ ����
					if(checkdate(date('m',$date),$counter,date('Y',$date))){
						//�������
						
						//!!!
						$cou=$this->ng->CalcItemsByDateId($mid, date('Y-m-d',$ech_date), $this->lang_id);
						if($cou>0){
							$this->matr[$i][$j]='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.date('Y-m-d',$ech_date).$this->params.'" class="';
	
							if(($j==0)||($j==6)) $this->matr[$i][$j].=$this->holiday_class.'"';
							else $this->matr[$i][$j].=$this->link_class.'"';
							
							if($chosen_date==$ech_date) $this->matr[$i][$j].=' style="'.$this->chosen_style.'" ';
							$this->matr[$i][$j].='>'.date('j',$ech_date).'</a>';
						}else{
							/*if(($j==0)||($j==6)) $this->matr[$i][$j]='<span class="'.$this->holiday_class.'" style="color: Red;">'.date('j',$ech_date).'</span>';
							else */$this->matr[$i][$j]=''.date('j',$ech_date).'';
						}
						
						$counter++;
						$ech_date=mktime(0,0,0,date('m',$date),$counter,date('Y',$date));
					}else{
						$this->matr[$i][$j]='&nbsp;&nbsp;';
					}
				}
			}
		}
		$this->MoveToLast();
		
		$this->calen.= $this->OutMatrix();
		
		return $this->calen;
	}
	
}
?>