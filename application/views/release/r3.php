<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- 달력 및 에디터호출 -->
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<div style="float:left;">
				<form id="items_formupdate" action="<?php echo base_url('rel/r3/')?>">

				<label for="pln_date">작업계획일</label>
					<input type="text" class="calendar" name="pln_date" id="pln_date" value="<?php echo ($str['pln_date']!="")?$str['pln_date']:date("Y-m-d",time())?>" /> ~ 
					<input type="text" class="calendar" name="pln_date_end" id="pln_date_end" value="<?php echo ($str['pln_date_end']!="")?$str['pln_date_end']:date("Y-m-d",strtotime("+7 day"))?>" />
					
					<?php
					if(!empty($GJ_GB)){
					?>
						<label for="gjgb">공정구분</label>
						<select name="gjgb" id="gjgb" class="form_select">
						<option value="">ALL</option>
						<?php
						foreach($GJ_GB as $row){
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo ($str['gjgb'] == $row->D_CODE)?"selected":"";?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}
					?>
				
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<?php
			if(!empty($insertBomList)){ ?>
                <span class="btn print download"><i class="material-icons">get_app</i>출력</span>
            
            <?php }?>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span--> 
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>LOT NO</th>
						<th>BL NO</th>
						<th>수량</th>
						<th>수주일자</th>
						<th>공정코드</th>
						<th>공정명</th>
						<th>완료여부</th>
						<th>작업계획일</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					
				<?php
				foreach($relList as $i=>$row){
					$num = $pageNum+$i+1;
					$over = ($i == $overi)?"over":"";
				?>

					<tr class="pocbox <?=$over?>">
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->LOT_NO; ?></td>
						<td><span class="mod_items mlink" data-i="<?=$i?>" data-bno="<?php echo $row->BL_NO;?>" data-qty="<?php echo $row->QTY;?>"><?php echo $row->BL_NO; ?></span></td>
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="cen"><?php echo substr($row->ACT_DATE,0,10); ?></td>
						<td class="cen"><?php echo $row->GJ_CODE; ?></td>
						<td><?php echo $row->NAME; ?></td>
						<td class="cen"><?php echo ($row->FINISH == "Y")?"완료":"진행중"; ?></td>
						<td class="cen"><?php echo substr($row->PLN_DATE,0,10); ?></td>
						<td class="cen"><!--button type="button" class="mod mod_material" data-idx="<?php echo $row->IDX;?>">수정</button--></td>
					</tr>

				<?php
				}
				if(empty($relList)){
				?>

					<tr>
						<td colspan="11" class="list_none">출력정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>
		
		<div class="pagination">
			<?php echo $this->data['pagenation'];?>
			<?php
			if($this->data['cnt'] > 20){
			?>
			<div class="limitset">
				<select name="per_page">
					<option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
					<option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
					<option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
					<option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
				</select>
			</div>
			<?php
			}
			?>
		</div>

	</div>
</div>



<?php
if(!empty($insertBomList)){
?>



<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<strong></strong>
		</header> 

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" id="excel">
				<thead>
					<tr>
						<th>no</th>
						<th>자재코드</th>
						<th>자재명</th>
						<th>단위</th>
						<th>POINT</th>
						<th>소요수량</th>
						<th>자재재고</th>
						<th>차이</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($insertBomList as $i=>$row){
					$color = ($row->M_POINT < 0)?"style='color:#f00; font-weight:600;'":"";
				?>
					<tr>
						<td class="cen"><?php echo $i+1;?></td>
						<td><?php echo $row->COMPONENT; ?></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td class="cen"><?php echo $row->COMPONENT_UNIT; ?></td>
						<td class="right"><?php echo $row->POINT; ?></td>
						<td class="right"><?php echo number_format($row->T_POINT); ?></td>
						<td class="right"><?php echo number_format($row->COMPONENT_STOCK); ?></td>
						<td class="right" <?php echo $color?>><?php echo number_format($row->M_POINT); ?></td>
					</tr>

				<?php
				}
				if(empty($insertBomList)){
				?>

					<tr>
						<td colspan="13" class="list_none">제품정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>
		
		

	</div>
</div>



<?php
}	
?>
<script>

$(".write_xlsx").on("click",function(){
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
});

$(".mod_items").on("click",function(){
	var i = $(this).data("i");
	var bno = $(this).data("bno");
	var qty = $(this).data("qty");
	$(window).unbind("beforeunload");
	var qstr = "<?php echo $qstr ?>";
	
	location.href="<?php echo base_url('rel/r3/')?>"+bno+"/"+qty+"/"+i+"/"+qstr;
});


$(document).on("click","h2 > span.close",function(){

	//$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});


$(".limitset select").on("change",function(){
	$(window).unbind("beforeunload");
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('rel/r3/')?>"+qstr+"&perpage="+$(this).val();
});

$("input[name='pln_date'],input[name='pln_date_end']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});


$('.download').click(function() {
    var bno = "<?=$bno?>";
	fnExcelReport('excel', '제공품 내역['+bno+']');
});
function fnExcelReport(id, title) {
	var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
	tab_text = tab_text + '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
	tab_text = tab_text + '<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
	tab_text = tab_text + '<x:Name>Sheet1</x:Name>';
	tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
	tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
	tab_text = tab_text + "<table border='1px'>";
	var exportTable = $('#' + id).clone();
	exportTable.find('input').each(function(index, elem) {
		$(elem).remove();
	});
	tab_text = tab_text + exportTable.html();
	tab_text = tab_text + '</table></body></html>';
	var data_type = 'data:application/vnd.ms-excel';
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE ");
	var fileName = title + '.xls';
	//Explorer 환경에서 다운로드
	if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
		if (window.navigator.msSaveBlob) {
			var blob = new Blob([tab_text], {
				type: "application/csv;charset=utf-8;"
			});
			navigator.msSaveBlob(blob, fileName);
		}
	} else {
		var blob2 = new Blob([tab_text], {
			type: "application/csv;charset=utf-8;"
		});
		var filename = fileName;
		var elem = window.document.createElement('a');
		elem.href = window.URL.createObjectURL(blob2);
		elem.download = filename;
		document.body.appendChild(elem);
		elem.click();
		document.body.removeChild(elem);
	}
}


</script>