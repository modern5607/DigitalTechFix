<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!--
<script src="/common/js/jquery-1.10.2.js"></script>
<script src="/common/js/jquery-ui.js"></script>
<script src="/common/js/common.js"></script>
-->
		
<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>


<!DOCTYPE html>
<html>
  <head>
	<title>Insert title here</title>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   
    <meta charset="utf-8">
    <title>Line_Controls_Chart</title>
    
	<style type ="text/css">
       #mid_content{
       width:48%; 
       float:left;
       margin:5px;
	   vertical-align: top;
       }
       #mid_content2{
       width:48%; 
       float:right;
       margin:5px;
	   vertical-align: top;
       }
	   #side_rigth{
		width:45%;
		float:left;
		margin:5px;
		vertical-align: top;
		}
    </style>
    
 
    <!-- jQuery -->
    	<script src="https://code.jquery.com/jquery.min.js"></script>
    <!-- google charts -->
       <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  </head>
	<body>
	         <div id = "mid_content" style="height:80%; border:2px solid gray;">
                     <div id="headTitle" style="font-size:1.5em; width: 100%; background:#8C8C8C; color: #fff; padding:10px 20px 20px 20px;">
                     	<th>설비가동률</th>
                     </div>
                 <!-- 라인 차트 생성할 영역 -->
                     <div id="lineChartArea" style="height:80%; padding:20px 20px 0px 0px;"></div>
                 <!-- 컨트롤바를 생성할 영역 -->
                     <div id="AreaText"  style="height:20%; padding:20px 20px 0px 0px;">
                    <div class="bdcont_100">
                        <div class="bc__box100">
                            <header>
                                <div style="float:left;">
                                    <form id="items_formupdate">
                                        <label for="kpigb">KPI 구분</label>
                                        <select name="kpigb" id= "kpigb" style="padding:4px 10px; border:1px solid #ddd; ">
                                            <option value="AA">:: 선택 ::</option>
                                            <option value="SB" <?php echo ($str['kpigb'] == "SB")?"selected":""?>>설비가동률</option>
                                            <option value="GJ" <?php echo ($str['kpigb'] == "GJ")?"selected":""?>>공정불량률</option>
                                        </select>
                                        <button class="search_submit"><i class="material-icons">search</i></button>
                                    </form>
                                </div>
                            </header>
                            </div>
                            <div class="tbl-content">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>순 번</th>
                                            <th>일 자</th>
                                            <th>목 표</th>
                                            <th>달성치</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                <?php
                                 if($this->data['kpigb'] = 'GG'){
                                ?>
                                    <?php
                                    foreach($viewList as $i=>$row){
                                        $num = $i+1;
                                    ?>
                            
                                        <tr>
                                            <td class="cen"><?php echo $num;?></td>
                                            <td class="cen"><?php echo $row->INSERT_DATE;?></td>
                                            <td class="right"><strong><?php echo $row->PL_KPI; ?></strong></td>
                                            <td class="right"><strong><?php echo $row->AC_KPI; ?></strong></td>
                                            
                                        </tr>
                            
                                        <?php
                                    }
                                 }
                                    if(empty($viewList)){
                                    ?>
                            
                                        <tr>
                                            <td colspan="12" class="list_none">제품정보가 없습니다.</td>
                                        </tr>
                            
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            <div id = "mid_content" style="height:80%; border:2px solid gray;">
                     <div id="headTitle" style="font-size:1.5em; width: 100%; background:#8C8C8C; color: #fff; padding:10px 20px 20px 20px;">
                     	<th>공정불량률</th>
                     </div>
                 <!-- 라인 차트 생성할 영역 -->
                     <div id="controlsArea" style="height:80%; padding:20px 20px 0px 0px;"></div>
                 <!-- 컨트롤바를 생성할 영역 -->
                     <div id="AreaText2"  style="height:20%; padding:20px 20px 0px 0px;">
                    <div class="bdcont_100">
                        <div class="bc__box100">
                            <header>
                                <div style="float:left;">
                                    <form id="items_formupdate">
                                        <label for="kpigb">KPI 구분</label>
                                        <select name="kpigb" id= "kpigb" style="padding:4px 10px; border:1px solid #ddd; ">
                                            <option value="AA">:: 선택 ::</option>
                                            <option value="SB" <?php echo ($str['kpigb'] == "SB")?"selected":""?>>설비가동률</option>
                                            <option value="GJ" <?php echo ($str['kpigb'] == "GJ")?"selected":""?>>공정불량률</option>
                                        </select>
                                        <button class="search_submit"><i class="material-icons">search</i></button>
                                    </form>
                                </div>
                            </header>
                            </div>
                            <div class="tbl-content">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>순 번</th>
                                            <th>일 자</th>
                                            <th>목 표</th>
                                            <th>달성치</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                <?php
                                 if($this->data['kpigb'] = 'GG'){
                                ?>
                                    <?php
                                    foreach($viewList1 as $i=>$row){
                                        $num = $i+1;
                                    ?>
                            
                                        <tr>
                                            <td class="cen"><?php echo $num;?></td>
                                            <td class="cen"><?php echo $row->INSERT_DATE;?></td>
                                            <td class="right"><strong><?php echo $row->PL_KPI; ?></strong></td>
                                            <td class="right"><strong><?php echo $row->AC_KPI; ?></strong></td>
                                            
                                        </tr>
                            
                                        <?php
                                    }
                                 }
                                    if(empty($viewList1)){
                                    ?>
                            
                                        <tr>
                                            <td colspan="12" class="list_none">제품정보가 없습니다.</td>
                                        </tr>
                            
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
    	</body>
  <script>
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawVisualization);

		function drawVisualization() { 

			 var data = new google.visualization.DataTable();
			 var data2 = new google.visualization.DataTable();
			 var chartDateformat     = 'MM/dd';
			 var chartLineCount    = 10;
			 var controlLineCount    = 10;
			 
			  //그래프에 표시할 컬럼 추가
			  data.addColumn('datetime' , '날짜');
			  data.addColumn('number'   , '목표');
			  data.addColumn('number'   , '달성');

			  data2.addColumn('datetime' , '날짜');
			  data2.addColumn('number'   , '목표');
			  data2.addColumn('number'   , '달성');

			  //그래프에 표시할 데이터

			  var dataRow  = [];
			  var dataRow2 = [];
	 
				<?php
					foreach($viewList as $row){
				?>
					var year  = <?php echo $row->YE; ?>;
					var month = <?php echo $row->MO; ?> -1;
					var date  = <?php echo $row->DA; ?>;
			
					dataRow = [new Date(year, month , date), <?php echo (int)$row->AC_KPI; ?>, <?php echo (int)$row->PL_KPI; ?>];
					data.addRow(dataRow);
	
				<?php
					}
				?>


				<?php
					foreach($viewList1 as $row){
				?>
					var year  = <?php echo $row->YE; ?>;
					var month = <?php echo $row->MO; ?> -1;
					var date  = <?php echo $row->DA; ?>;
			
					dataRow2 = [new Date(year, month), <?php echo (int)$row->AC_KPI; ?>, <?php echo (int)$row->PL_KPI; ?>];
					data2.addRow(dataRow2);
	
				<?php
					}
				?>


			var options = {
					title : '설비가동률', width: '100%', height: 500, chartArea : {'width': '75%','height' : '80%'},
					vAxis: {title: 'Cups'},
					hAxis: {format: chartDateformat,slantedText: true, slantedTextAngle: 30, 
																	gridlines:{count:chartLineCount,units: {
																	  years : {format: ['yyyy년']},
																	  months: {format: ['MM월']},
															 		  days  : {format: ['dd일']},
																	  hours : {format: ['HH시']}}
																	},textStyle: {fontSize:12}},
					seriesType: 'bars',
					series: {0:{color: '#BDBDBD', visibleInLegend: true},
            				 1:{color: 'red', visibleInLegend: true}},
				};

			var options1 = {
					title : '공정불량률', width: '100%', height: 500, chartArea : {'width': '75%','height' : '80%'},
					vAxis: {title: 'Cups'},
					hAxis: {format: chartDateformat,slantedText: true, slantedTextAngle: 30, 
																	 gridlines:{count:chartLineCount,units: {
																	  years : {format: ['yyyy년']},
																	  months: {format: ['MM월']},
															 		  days  : {format: ['dd일']},
																	  hours : {format: ['HH시']}}
																	},textStyle: {fontSize:12}},
					seriesType: 'bars',
					series: {0:{color: '#BDBDBD', visibleInLegend: true},
            				 1:{color: 'red', visibleInLegend: true}},
				};
			
			var chart = new google.visualization.ComboChart(document.getElementById('lineChartArea'));
			chart.draw(data, options);

			var control = new google.visualization.ComboChart(document.getElementById('controlsArea'));
			control.draw(data2, options1);
		}

 </script>
</html>
 
 
<!-- 
  <script>
	  var chartDrowFun = {
		chartDrow : function(){
			var chartData = '';
			//날짜형식 변경하고 싶으시면 이 부분 수정하세요.
			var chartDateformat     = 'yyyy년MM월dd일';
			//라인차트의 라인 수
			var chartLineCount    = 10;
			//컨트롤러 바 차트의 라인 수
			var controlLineCount    = 20;
	 
	 
			function drawDashboard() {
	 
			  var data = new google.visualization.DataTable();
			  //그래프에 표시할 컬럼 추가
			  data.addColumn('datetime' , '날짜');
			  data.addColumn('number'   , '목표');
			  data.addColumn('number'   , '달성치');
		
				 
			  //그래프에 표시할 데이터
			  var dataRow = [];
	 
				<?php
					foreach($viewList as $row){
				?>
					var year  = <?php echo $row->YE; ?>;
					var month = <?php echo $row->MO; ?> -1;
					var date  = <?php echo $row->DA; ?>;
			
					dataRow = [new Date(year, month), <?php echo (int)$row->PL_KPI; ?>, <?php echo (int)$row->AC_KPI; ?>];
					data.addRow(dataRow);
	
				<?php
					}
				?>
			
	 
			  
			 
				var chart = new google.visualization.ChartWrapper({
				  chartType   : 'LineChart',
				  containerId : 'lineChartArea', //라인 차트 생성할 영역
				  options     : {
								  isStacked   : 'percent',
								  focusTarget : 'category',
								  height          : 500,
								  width              : '100%',
								  legend          : { position: "top", textStyle: {fontSize: 13}},
								  pointSize        : 5,
								  tooltip          : {textStyle : {fontSize:12}, showColorCode : true,trigger: 'both'},
								  hAxis              : {format: chartDateformat, gridlines:{count:chartLineCount,units: {
																	  years : {format: ['yyyy년']},
																	  months: {format: ['MM월']},
															 		  days  : {format: ['dd일']},
																	  hours : {format: ['HH시']}}
																	},textStyle: {fontSize:12}},
					vAxis              : {minValue: 80,viewWindow:{min:0},gridlines:{count:-1},textStyle:{fontSize:12}},
					animation        : {startup: true,duration: 1000,easing: 'in' },
					annotations    : {pattern: chartDateformat,
									textStyle: {
									fontSize: 15,
									bold: true,
									italic: true,
									color: '#871b47',
									auraColor: '#d799ae',
									opacity: 0.8,
									pattern: chartDateformat
								  }
								}
				  }

				});
	 
				var control = new google.visualization.ControlWrapper({
				  controlType: 'ChartRangeFilter',
				  containerId: 'controlsArea',  //control bar를 생성할 영역
				  options: {
					  ui:{
							chartType: 'LineChart',
							chartOptions: {
							chartArea: {'width': '60%','height' : 80},
							  hAxis: {'baselineColor': 'none', format: chartDateformat, textStyle: {fontSize:12},
								gridlines:{count:controlLineCount,units: {
									  years : {format: ['yyyy년']},
									  months: {format: ['MM월']},
									  days  : {format: ['dd일']},
									  hours : {format: ['HH시']}}
								}}
							}
					  },
						filterColumnIndex: 0
					}
				});
	 
				var date_formatter = new google.visualization.DateFormat({ pattern: chartDateformat});
				date_formatter.format(data, 0);
	 
				var dashboard = new google.visualization.Dashboard(document.getElementById('Line_Controls_Chart'));
				window.addEventListener('resize', function() { dashboard.draw(data); }, false); //화면 크기에 따라 그래프 크기 변경
				dashboard.bind([control], [chart]);
				dashboard.draw(data);
	 
			}
			  google.charts.setOnLoadCallback(drawDashboard);
	 
		  }
		}

	
	$(document).ready(function(){
	  google.charts.load('current', {'packages':['line','controls']});
	  chartDrowFun.chartDrow(); //chartDrow() 실행
	});

$(".search_submit").on("click",function(){
	location.href="<?php echo base_url('main/index')?>"+qstr;
});

  </script>
</html> -->
 

 