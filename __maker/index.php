<?
session_start();
require_once('../classes/global.php');
require_once('../classes/smarty/SmartyAdm.class.php');

require_once('../classes/mmenuitem.php');
require_once('../classes/sitetree.php');



//административная авторизация
require_once('inc/adm_header.php');



//вывод из шаблона
$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;
$smarty->clear_all_assign();

$smarty->assign("SITETITLE",'Старт - '.SITETITLE);
$smarty->assign("NAVIMENU",'');

$hmenu='';
require_once('hmenu2.php');
$smarty->assign("HMENU1",$hmenu);

$_menu_id=20;
$vmenu='';
require_once('vmenu2.php');
$smarty->assign("VMENU1",$vmenu);

//меню в зависимости от наличия модулей
$custommenu='';
require_once('custommenu.php');
$smarty->assign("custommenu",$custommenu);

//логин-имя
$smarty->assign("login",$global_profile['login']);
$smarty->assign("username",$global_profile['username']);


//контекстные команды
require_once('../classes/v2/context.php');
//(  $object_id, $right_kind, $module_constant, $name, $url, $is_help , $_auth_result)
$_context=new Context;

$_context->AddContext(new ContextItem( 11, 'r', "", "Дерево сайтов", "tree.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'r', "", "Разделы и контент", "razds.php", false , $global_profile  ));
$_context->AddContext(new ContextItem( 11, 'a', "", "Создать раздел", "ed_razd.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 18, 'a', "HAS_NEWS", "Создать новость", "ed_news.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 20, 'a', "HAS_PAPERS", "Создать статью", "ed_paper.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 22, 'a', "HAS_PRICE", "Создать товар", "ed_price.php?action=0", false , $global_profile  ));
$_context->AddContext(new ContextItem( 21, 'a', "HAS_PHOTOS", "Создать фото", "ed_photo.php?action=0", false , $global_profile  ));

$_context->AddContext(new ContextItem( 11, 'r', "", "Отзывы", "viewotzyv.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 11, 'r', "", "Баннеры", "viewads.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 14, 'r', "HAS_BASKET", "Заказы", "vieworders.php", false , $global_profile  ));


$_context->AddContext(new ContextItem( 4, 'r', "", "Пользователи и права", "discr_matrix_user.php", false , $global_profile  ));

$_context->AddContext(new ContextItem( 3, 'r', "", "Права групп", "discr_matrix_group.php", false , $global_profile  ));



$_context->AddContext(new ContextItem( "", '',  "", "Справка", "common.html", true , $global_profile  ));

$context=$_context->BuildContext();
$smarty->assign('context', $context);
$smarty->assign('context_caption', 'Быстрые действия');

$smarty->display('page_top.html');
unset($smarty);

?>


	<script src="js/hc/highcharts.js"></script>
 
    <script src="/js/ui/i18n/ui.datepicker-ru.js"></script>
 	
    <? if( isset($_GET['code'])){ ?>
    <script type="text/javascript">
	$(function(){
		$("#dialog_start").dialog({
			autoOpen: false,
			modal: true,
			width: 900,
			 
			height: 620,
			buttons:{
				"ОК": function(){
					$("#dialog_start").dialog("close");	
				}
			}
		});
		$("#dialog_start").dialog("open");	
	})
	</script>
   	 <div id="dialog_start" title="Добро пожаловать!" style="display:none;">
          		<!--<div id="dialog_start_inner">-->
          
        	 <h2><a href="#">Уважаемый(ая) <nobr><?=$global_profile['username']?></nobr></a>!</h2>
             <p>
               Поздравляем! Вы используете CMS GYDEX &ndash;  ведущее решение для управления сайтом!</p>
                <p>
               CMS GYDEX позволяет легко и эффективно разворачивать и управлять  сайтами любого профиля и сложности.</p>
                <p>
               Модули CMS GYDEX,  гибкие и удобные в управлении, позволят Вам решить следующие задачи:</p>
             <ul>
               <li><strong>Рубрикатор  сайта:</strong> легкое управление структурой сайта, построение основных страниц и  меню на сайте.</li>
               <li><strong>Многоязыковая  поддержка:</strong> удобная поддержка необходимых Вам версий сайта на различных  языках.</li>
               <li>Фотогалереи и статьи: простое и надежное  средство для размещения текстовых и фото-видеоматериалов на сайте.</li>
               <li><strong>Товарный  каталог:</strong> размещение на страницах сайта полноформатного товарного каталога с  функциями многоуровневой фильтрации и отбора товаров по набору характеристик &nbsp;с любым необходимым количеством фотографий для  каждого товара. Доступна функция &laquo;рекомендуемые товары&raquo;.</li>
               <li>Интернет-магазин: модуль разработан на основе  многолетнего опыта создания и поддержки интернет-магазинов.Товары, цены,  скидки, статистика продаж &ndash; все в удобном формате и эффективно управляется.</li>
               <li><strong>Баннеры:</strong>  формирование и размещение баннеров и объявлений на страницах сайта, управление  режимами показа баннеров.</li>
               <li><strong>Модуль  обратной связи:</strong> централизованное создание и конфигурация работы форм  обратной связи на сайте: отзывы, заявки на звонок, запросы посетителей &ndash; все к  Вашим услугам.</li>
               <li><strong>Управление  правами доступа:</strong> организация доступа и разграничение прав многих  пользователей админ-панели, контроль полномочий и защита  критически важных модулей от несанкционированного доступа.</li>
               <li>М<strong>обильное администрирование &ndash; </strong> вы можете управлять вашим сайтов с телефона или планшета, удобный адаптивный  интерфейс для любого мобильного устройства. </li>
               <li><strong>Аналитика &ndash;</strong> наглядная  статистика по выбранным показателям: посещаемость, заказы, уникальные пользователи. </li>
               <li><strong>Тех.поддержка &ndash;</strong> если  у вас возникли вопросы, пишите на почту <a href="mailto:support@gydex.ru">support@gydex.ru</a>,  либо перейдите в раздел &laquo;Справка&raquo;, где представлено описание системы. </li>
             </ul>
             <p>Желаем Вам успешной работы!<br>
        С уважением, команда разработчиков  CMS GYDEX.</p>    
      
      <!--</div>-->
          </div>  
      <?
	}
	?>
        
        
                
            <h2>Сводная статистика</h2>
            
            <strong>Период:</strong><br>
            
            
            <div style="overflow:hidden;">    
                <div style="float:left; margin-right:20px;">
                    <label for="pdate1">с:</label><br>
                    <input type="text" id="pdate1" size="10" maxlength="10" value="<? echo date('d.m.Y', time()-3*24*60*60);  ?>" />
                </div>
                
            
               <div style="float:left; margin-right:20px;">
                    <label for="pdate2">по:</label><br>   
                   <input type="text" id="pdate2" size="10" maxlength="10" value="<? echo date('d.m.Y', time());  ?>" />
                </div>
                
                <div style="float:left; margin-right:20px;">
                    <br>
                    <input id="redraw_plots" type="button" value="Обновить" />
                </div>
            
           </div>
           
           <style>
           #container, #container1, #container2, #container3{
               min-width: 400px; width:auto; max-width: 700px; height: 450px; margin: 10px 10px 10px 0px; float:left;
        
           }
           </style>
             
           <div style="overflow:hidden;">      
                <div id="container" ></div>
                
                 <div id="container3"  ></div>
             
            </div>
            
            <div style="overflow:hidden;">    
                <div id="container1"  ></div>
                <div id="container2"  ></div>
            </div>
            
           
            
            <div class="clr"></div>
            
            <script type="text/javascript">
            $(function(){
                $("#pdate1").datepicker();
                $("#pdate2").datepicker();
                
                function DrawPlots(){
                
                 $.ajax({
                            async: true,
                            url: "js/stats.php",
                            type: "POST",
                            data:{
                                "action":"total_stack",
                                "pdate1": $("#pdate1").val(), // $("#new_pass_1").val(),
                                "pdate2":  $("#pdate2").val()
                            },
                            beforeSend: function(){
                                  
                            },
                            success: function(data){
                               //alert(data);
                              // console.log(data);
                                
                                var options = {
                                    chart: {
                                        renderTo: 'container',
                                        defaultSeriesType: 'column',
                                        borderWidth: 1,
                                        borderColor: '#ebebeb',
                                        style: {
                                            fontFamily: 'IstokWeb, Tahoma, Geneva, sans-serif'
                                        },
										backgroundColor: '#fafafa'
                                    },
                                    
                                    credits: {
                                        enabled: false
                                    },
                                    title: {
                                        text: 'Посещаемость сайта',
										align: 'left',
                                        style: {
                                            color:'#398fcc',
											fontSize:'16px'
											
                                        }
                                    },
                                    xAxis: {
                                        categories: []
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Человек в день'
                                        },
										 stackLabels: {
											enabled: true
											 
										}
                                    },
									
									plotOptions: {
										column: {
											stacking: 'normal',
											dataLabels: {
												enabled: true ,
												
                  								 color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
											}
										}
									},
                                    tooltip: {
                                        formatter: function() {
                                            return this.series.name+': <b>' + this.x + '</b> : <b>' + this.y + '</b>';
                                        }
                                    },
                                     
                                    colors: ['#143d69', '#398dcb', '#619fd2', '#e45223'],
                                    series: []
                                };
                                
                                var lines = data.split('\n');
            
                                // Iterate over the lines and add categories or series
                                $.each(lines, function(lineNo, line) {
                                    var items = line.split(',');
                                    
                                    // header line containes categories
                                    if (lineNo == 0) {
                                        $.each(items, function(itemNo, item) {
                                            if (itemNo > 0) options.xAxis.categories.push(item);
                                        });
                                    }
                                    
                                    // the rest of the lines contain data with their name in the first 
                                    // position
                                    else {
                                        var series = {
                                            data: []
                                        };
                                        $.each(items, function(itemNo, item) {
                                            if (itemNo == 0) {
                                                series.name = item;
                                            } else {
                                                series.data.push(parseFloat(item));
                                            }
                                        });
                                        
                                        options.series.push(series);
                                
                                    }
                                    
                                });
                                
                                // Create the chart
                                //var chart = new Highcharts.Chart(options);
                                $("#container").highcharts(options);
                               
                                
                              
                            },
                            error: function(xhr, status, m){
                                //alert("Ошибка!"+status+m); 
                            }	 
                        }); 
                        
                        
                        
                        $.ajax({
                            async: true,
                            url: "js/stats.php",
                            type: "POST",
                            data:{
                                "action":"average_time",
                                "pdate1":  $("#pdate1").val(), // $("#new_pass_1").val(),
                                "pdate2":  $("#pdate2").val()
                            },
                            beforeSend: function(){
                                  
                            },
                            success: function(data){
								//alert(data);
                             var options = {
                                    chart: {
                                        
                                        renderTo: 'container',
                                        
                                        borderWidth: 1,
                                        
                                        borderColor: '#ebebeb',
                                         
                                        style: {
                                            fontFamily: 'IstokWeb, Tahoma, Geneva, sans-serif'
                                        },
										backgroundColor: '#fafafa'
                                    },
									
									plotOptions: {
										pie: {
											dataLabels: {
												enabled: true,
												distance: -10,
												style: {
													fontWeight: 'bold',
													color: 'white',
													textShadow: '0px 1px 2px black'
												} 
											},
											 
										}
									},
                                    credits: {
                                        enabled: false
                                    },
                                    title: {
                                        text: 'Среднее время работы на сайте, %',
										align: 'left',
										
                                        style: {
                                            color:'#398fcc',
											fontSize:'16px'
                                        }
                                    },
                                    xAxis: {
                                        categories: []
                                    },
                                    yAxis: {
                                        title: {
                                            text: ' % '
                                        }
                                    },
                                    tooltip: {
                                        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
                                    },
                                    colors: ['#015fa5', '#143d69', '#398dcb', '#619fd2', '#e45223'],
                                    series: []
                                };
                                
                                var lines = data.split('\n');
            
                                // Iterate over the lines and add categories or series
								var series = {
											 type: 'pie',
											  name: 'Среднее время работы, %',
            									innerSize: '50%',
                                            data: []
                                        };
                                $.each(lines, function(lineNo, line) {
                                    var items = line.split(',');
                                    
                                   
                                     series.data.push({name:items[0],y:parseFloat(items[1])});
                                      
                                        options.series.push(series);
                                
                                     
                                    
                                });
                                
                                // Create the chart
                                //var chart = new Highcharts.Chart(options);
                                $("#container1").highcharts(options);
                               
                                
                              
                            },
                            error: function(xhr, status, m){
                                //alert("Ошибка!"+status+m); 
                            }	 
                        }); 
                        
                        
                    <? if (HAS_BASKET){?>	
                        $.ajax({
                            async: true,
                            url: "js/stats.php",
                            type: "POST",
                            data:{
                                "action":"orders",
                                "pdate1":  $("#pdate1").val(), // $("#new_pass_1").val(),
                                "pdate2":  $("#pdate2").val()
                            },
                            beforeSend: function(){
                                  
                            },
                            success: function(data){
                             var options = {
                                    chart: {
                                         type: 'bar',
                                        renderTo: 'container',
                                        defaultSeriesType: 'column',
                                        borderWidth: 1,
                                        borderColor: '#ebebeb',
                                        style: {
                                            fontFamily: 'IstokWeb, Tahoma, Geneva, sans-serif'
                                        },
										backgroundColor: '#fafafa'
                                    },
                                    credits: {
                                        enabled: false
                                    },
                                    title: {
                                        text: 'Кол-во заказов в интернет-магазине',
										align: 'left',
                                        style: {
                                            color:'#398fcc',
											fontSize:'16px'
                                        }
                                    },
                                    xAxis: {
                                        categories: []
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Заказов, шт.'
                                        }
                                    },
                                    tooltip: {
                                        formatter: function() {
                                            return this.series.name+': <b>' + this.x + '</b> : <b>' + this.y + ' </b>';
                                        }
                                    },
                                    colors: ['#015fa5', '#143d69', '#398dcb', '#619fd2', '#e45223'],
                                    series: []
                                };
                                
                                var lines = data.split('\n');
            
                                // Iterate over the lines and add categories or series
                                $.each(lines, function(lineNo, line) {
                                    var items = line.split(',');
                                    
                                    // header line containes categories
                                    if (lineNo == 0) {
                                        $.each(items, function(itemNo, item) {
                                            if (itemNo > 0) options.xAxis.categories.push(item);
                                        });
                                    }
                                    
                                    // the rest of the lines contain data with their name in the first 
                                    // position
                                    else {
                                        var series = {
                                            data: []
                                        };
                                        $.each(items, function(itemNo, item) {
                                            if (itemNo == 0) {
                                                series.name = item;
                                            } else {
                                                series.data.push(parseFloat(item));
                                            }
                                        });
                                        
                                        options.series.push(series);
                                
                                    }
                                    
                                });
                                
                                // Create the chart
                                //var chart = new Highcharts.Chart(options);
                                $("#container2").highcharts(options);
                               
                                
                              
                            },
                            error: function(xhr, status, m){
                                //alert("Ошибка!"+status+m); 
                            }	 
                        });
                        
                    <? }?>
                        $.ajax({
                            async: true,
                            url: "js/stats.php",
                            type: "POST",
                            data:{
                                "action":"sub",
                                "pdate1":  $("#pdate2").val(), // $("#new_pass_1").val(),
                                "pdate2":  $("#pdate2").val()
                            },
                            beforeSend: function(){
                                  
                            },
                            success: function(data){
                                 
                             var options = {
                                    chart: {
                                         
                                        renderTo: 'container',
                                        defaultSeriesType: 'column',
                                        borderWidth: 1,
                                        borderColor: '#ebebeb',
                                        style: {
                                            fontFamily: 'IstokWeb, Tahoma, Geneva, sans-serif'
                                        },
										backgroundColor: '#fafafa'
                                    },
                                    credits: {
                                        enabled: false
                                    },
                                    title: {
                                        text: 'Посещаемость разделов за день',
										align: 'left',
                                        style: {
                                            color:'#398fcc',
											fontSize:'16px'
                                        }
                                    },
                                    xAxis: {
                                        categories: []
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Просмотров за '+$("#pdate2").val()
                                        }
                                    },
                                    tooltip: {
                                        formatter: function() {
                                            return this.series.name+': <b>' + this.x + '</b> : <b>' + this.y + ' </b>';
                                        }
                                    },
                                    colors: ['#015fa5', '#143d69', '#398dcb', '#619fd2', '#e45223'],
                                    series: []
                                };
                                
                                var lines = data.split('\n');
            
                                // Iterate over the lines and add categories or series
                                $.each(lines, function(lineNo, line) {
                                    var items = line.split(',');
                                    
                                    // header line containes categories
                                    if (lineNo == 0) {
                                        $.each(items, function(itemNo, item) {
                                            if (itemNo > 0) options.xAxis.categories.push(item);
                                        });
                                    }
                                    
                                    // the rest of the lines contain data with their name in the first 
                                    // position
                                    else {
                                        var series = {
                                            data: []
                                        };
                                        $.each(items, function(itemNo, item) {
                                            if (itemNo == 0) {
                                                series.name = item;
                                            } else {
                                                series.data.push(parseFloat(item));
                                            }
                                        });
                                        
                                        options.series.push(series);
                                
                                    }
                                    
                                });
                                
                                // Create the chart
                                //var chart = new Highcharts.Chart(options);
                                $("#container3").highcharts(options);
                               
                                
                              
                            },
                            error: function(xhr, status, m){
                                //alert("Ошибка!"+status+m); 
                            }	 
                        });
                }
                
                
                DrawPlots();
                $("#redraw_plots").click(DrawPlots);
                
            });
            </script>
        
        
        </td>
      </tr>
    </table>

    
    
    
	
	<?
	/*require_once('../classes/v2/gydex_stat.php');
	
	$stat=new GydexStat;
	
	print_r($stat->GetTotal('2014-10-08', '2014-10-10'));
	
	print_r($stat->GetAverageTime('2014-10-08', '2014-10-10'));
	
	print_r($stat->GetOrders('2014-10-08', '2014-10-10'));
	
	print_r($stat->GetSubPerDay('2014-10-09'));*/
	
	?>
	
<?
//нижний шаблон

$smarty = new SmartyAdm;
$smarty->debugging = DEBUG_INFO;

$fmenu='';
require_once('fmenu.php');
$smarty->assign("FMENU",$fmenu);

echo $smarty->fetch('page_bottom.html');
unset($smarty);
?>