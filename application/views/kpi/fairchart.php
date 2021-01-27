<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
		
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
       margin:10px;
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
            </div>

    	</body>
  <script>
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawVisualization);

		function drawVisualization() { 

			 var data = new google.visualization.DataTable();
			 var chartDateformat     = 'MM/dd';
			 var chartLineCount    = 10;
			 var controlLineCount    = 10;
			 
			  //그래프에 표시할 컬럼 추가
			  data.addColumn('datetime' , '날짜');
			  data.addColumn('number'   , '목표');
			  data.addColumn('number'   , '달성');

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
			
			var chart = new google.visualization.ComboChart(document.getElementById('lineChartArea'));
			chart.draw(data, options);
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
 

 