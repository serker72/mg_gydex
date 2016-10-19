<?
require_once('calendar.php');
require_once('ordersgroup.php');
//класс для работы с календарем и заказами

class CalendarOrd extends Calendar{

	//класс табл ряда
	protected $tr_class;
	//класс табл ячейки
	protected $td_class;
	//класс табл ячейки выходного
	protected $td_class_holid;
	//класс для описания
	protected $updiv_class;	
	protected $alldiv_class;		
	//класс для ссылок
	protected $link_class;
	//класс для ссылок
	protected $nextprlink_class;	
	//класс для ссылки текущего дня
	protected $current_class;
	//стиль для ссылки выбранного дня
	protected $chosen_style;
	//класс для выходного
	protected $holiday_class;
	//класс для к-ва заказов
	protected $order_class;	
	//символ для выбора след месяца
	protected $next_symb;
	//символ для выбора пред месяца
	protected $prev_symb;
	//имя страницы для ссылок
	protected $pagename;
	//имя параметра даты
	protected $date_param_name;
	//строка прочих аргументов
	protected $params;
	//начальная дата
	protected $init_date;
	//собственно код календаря
	protected $calen;
	//матрица календаря
	protected $matr;
	//месяцы
	protected $monthes;
	
	//экз класса для подсчета заказов
	protected $zg;
	
	public function __construct(){
		$this->init();
	}
	
	protected function init(){
			$this->table_class='calen_table';
	$this->tr_class='calen_tr';
		$this->td_class='calen_td';
		$this->td_class_holid='calen_td_holid';
		$this->updiv_class='updiv';		
		$this->alldiv_class='alldiv';				
		$this->link_class='calen_a';
		
		$this->next_class='next';
		$this->pr_class='prev';		
		
		$this->order_class='calen_order';	
		
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
		
		$this->monthes[0][1]='Январь';
		$this->monthes[0][2]='Февраль';
		$this->monthes[0][3]='Март';
		$this->monthes[0][4]='Апрель';
		$this->monthes[0][5]='Май';
		$this->monthes[0][6]='Июнь';
		$this->monthes[0][7]='Июль';
		$this->monthes[0][8]='Август';
		$this->monthes[0][9]='Сентябрь';
		$this->monthes[0][10]='Октябрь';
		$this->monthes[0][11]='Ноябрь';												
		$this->monthes[0][12]='Декабрь';		
		for($i=1;$i<=12;$i++){
			$this->monthes[1][$i]=$this->monthes[0][$i];
		}												
		
		$this->monthes[2][1]='January';
		$this->monthes[2][2]='February';
		$this->monthes[2][3]='March';
		$this->monthes[2][4]='April';
		$this->monthes[2][5]='May';
		$this->monthes[2][6]='June';
		$this->monthes[2][7]='July';
		$this->monthes[2][8]='August';
		$this->monthes[2][9]='September';
		$this->monthes[2][10]='October';
		$this->monthes[2][11]='November';												
		$this->monthes[2][12]='December';		
		
		
		$this->zg=new OrdersGroup();
	}
	
	//вывод календаря
	public function Draw($init_date,$pagename,$date_param_name,$params,$chosen_date,$mode=0){
		$this->calen='';
		$this->init_date=$init_date;
		$this->pagename=$pagename;
		$this->date_param_name=$date_param_name;
		$this->params=$params;
		
		//определим 1е число месяца и его день недели
		$curr_date=time();
		
		$date = mktime(0,0,0,substr($this->init_date,5,2),substr($this->init_date,8,2),substr($this->init_date,0,4));	//начальная дата
		
		//номер дня недели 1го числа
		$no_day_1 = date('w',mktime(0,0,0,substr($this->init_date,5,2),1,substr($this->init_date,0,4)));
		
		//echo $no_day_1;
		
		//дата, какую выводим
		$counter=1;
		$ech_date=mktime(0,0,0,date('m',$date),$counter,date('Y',$date));
		$this->calen.='<div class="'.$this->alldiv_class.'">';
		$this->calen.='<div class="'.$this->updiv_class.'">';
		
		//ссылки на пред и посл месяцы
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
		
		$this->calen.='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.$prevdate.$this->params.'" class="'.$this->pr_class.'"></a>&nbsp;';
		
		$this->calen.=' '.$this->monthes[$mode][(int)date('m',$date)].' '.date('Y',$date).' ';
		
		$this->calen.='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.$nextdate.$this->params.'" class="'.$this->next_class.'"></a><br />';
		
		
		
		if($mode==2) $this->calen.='Today:&nbsp;<a href="'.$this->pagename.'?'.$this->date_param_name.'='.date('Y-m-d').$this->params.'">'.date('d.m.Y').'</a>&nbsp;';
		else $this->calen.='Сегодня:&nbsp;<a href="'.$this->pagename.'?'.$this->date_param_name.'='.date('Y-m-d').$this->params.'">'.date('d.m.Y').'</a>&nbsp;';
		$this->calen.='<br />';
		
		/*$this->calen.='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.$prevdate.$this->params.'" class="'.$this->nextprlink_class.'">'.$this->prev_symb.'</a>&nbsp;';
		
		$this->calen.=' '.$this->monthes[$mode][(int)date('m',$date)].' '.date('Y',$date).' ';
		
		$this->calen.='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.$nextdate.$this->params.'" class="'.$this->nextprlink_class.'">'.$this->next_symb.'</a><br />';*/
		
		$this->calen.='</div>';
				
		for($i=1;$i<=6;$i++){
			//недели
			//выведем заголовок таблицы
			if($i==1){
				if($mode==2){
					$this->matr[0][0]='su';
					$this->matr[0][1]='mo';					
					$this->matr[0][2]='tu';					
					$this->matr[0][3]='we';					
					$this->matr[0][4]='th';					
					$this->matr[0][5]='fr';					
					$this->matr[0][6]='sa';		
				}else{
					$this->matr[0][0]='вс';
					$this->matr[0][1]='пн';					
					$this->matr[0][2]='вт';					
					$this->matr[0][3]='ср';					
					$this->matr[0][4]='чт';					
					$this->matr[0][5]='пт';					
					$this->matr[0][6]='сб';					
				}
			}
			
			for($j=0;$j<=6;$j++){
				//дни
				if($i==1){
					//1я неделя, проверяем, делать ли пустые клетки
					if($no_day_1<=$j) {
						//выводим

						$this->matr[$i][$j]='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.date('Y-m-d',$ech_date).$this->params.'" class="';

						if($j==0) $this->matr[$i][$j].=$this->holiday_class.'"';
						else $this->matr[$i][$j].=$this->link_class.'"';
						
						if($chosen_date==$ech_date) $this->matr[$i][$j].=' style="'.$this->chosen_style.'" ';
						$this->matr[$i][$j].='>'.date('j',$ech_date).'</a>';
						//!!!
						$this->matr[$i][$j].='<div class="'.$this->order_class.'"><br>О='.$this->zg->CalcItemsByDate(date('Y-m-d',$ech_date),0);
						$this->matr[$i][$j].='<br>К='.$this->zg->CalcItemsByDate(date('Y-m-d',$ech_date),1);						
						$this->matr[$i][$j].='<br>В='.$this->zg->CalcItemsByDate(date('Y-m-d',$ech_date),2).'</div>';						

						$counter++;
						$ech_date=mktime(0,0,0,date('m',$date),$counter,date('Y',$date));
					}else {
						$this->matr[$i][$j]='&nbsp;&nbsp;';
					}
				}else{
					//не 1я неделя, просто проверяем корректность даты
					if(checkdate(date('m',$date),$counter,date('Y',$date))){
						//выводим

						$this->matr[$i][$j]='<a href="'.$this->pagename.'?'.$this->date_param_name.'='.date('Y-m-d',$ech_date).$this->params.'" class="';
						if($j==0) $this->matr[$i][$j].=$this->holiday_class.'"';
						else $this->matr[$i][$j].=$this->link_class.'"';
						
						if($chosen_date==$ech_date) $this->matr[$i][$j].=' style="'.$this->chosen_style.'" ';
						$this->matr[$i][$j].='>'.date('j',$ech_date).'</a>';
						//!!!
						$this->matr[$i][$j].='<div class="'.$this->order_class.'"><br>О='.$this->zg->CalcItemsByDate(date('Y-m-d',$ech_date),0);
						$this->matr[$i][$j].='<br>К='.$this->zg->CalcItemsByDate(date('Y-m-d',$ech_date),1);						
						$this->matr[$i][$j].='<br>В='.$this->zg->CalcItemsByDate(date('Y-m-d',$ech_date),2).'</div>';	
						
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
		
		
		
		$this->calen.='</div>';
		return $this->calen;
	}
	
}
?>