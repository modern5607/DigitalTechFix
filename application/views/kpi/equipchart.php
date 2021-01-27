<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>
		
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
				<div style="width: 100%; background:#8C8C8C; color: #fff;">
					<div class="bc__box100">
						<header>
							<div>
								<form id="items_formupdate">
									<label style="color:#fff" for="">일자</label>
										<input type="text" name="sdate" value="<?php echo ($str['sdate']!="")?$str['sdate']: date("Y-m-d", strtotime("-1 month", time()))?>" size="12" autocomplete="off">~
										<input type="text" name="edate" value="<?php echo ($str['edate']!="")?$str['edate']:date("Y-m-d",time())?>" size="12" autocomplete="off">

									<button class="search_submit"><i class="material-icons">search</i></button>
								</form>
							</div>
						</header>		 
					</div>
				</div>
                 <!-- 라인 차트 생성할 영역 -->
                     <div id="lineChartArea" style="height:80%; padding:20px 20px 0px 0px;"></div>
            </div>

    	</body>
	<script>
		$("input[name='sdate'],input[name='edate']").datetimepicker({
			format:'Y-m-d',
			timepicker:false,
			lang:'ko-KR'
		});
	</script>
  <script>
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawVisualization);

		function drawVisualization() { 

			 var data = new google.visualization.DataTable();
			 var chartDateformat     = 'MM-dd';
			 var chartLineCount    = <?php echo $this->data['cnt']; ?>;
			 var controlLineCount    = 10;
			 
			  //그래프에 표시할 컬럼 추가
			  data.addColumn('datetime' , '날짜');
			  data.addColumn('number'   , '목표');
			  data.addColumn('number'   , '달성');

			  //그래프에 표시할 데이터

			  var dataRow  = [];
			  var dataRow2 = [];
	 
				<?php
					foreach($List as $row){
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
					title : '설비가동률', width: '100%', height: 700, 
					chartArea : {'width': '75%','height' : '80%'},
					tooltip       : {textStyle : {fontSize:12}, showColorCode : true,},
					vAxis: {title: 'Cups', 
							viewWindow:{min:60, max:100,step:6},
							ticks:[60,70,80,90,100]},
					hAxis: {format: chartDateformat,
							// slantedText: true, slantedTextAngle: -90, 
							gridlines:{count:chartLineCount}, 
							textStyle: {fontSize:12}
							},
					seriesType: 'bars',
					series: {0:{color: '#BDBDBD', visibleInLegend: true},
            				 1:{color: 'red', visibleInLegend: true}},
				};
			
			var chart = new google.visualization.ComboChart(document.getElementById('lineChartArea'));
			chart.draw(data, options);
		}
</script>
</html>