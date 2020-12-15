<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="mheader">
	<!--span class="left material-icons">keyboard_arrow_left</span-->
	<span class="material-icons right">reorder</span>
	<?php echo $title;?>
	
</div>

<div class="mbody">

	<div class="bdcont_100">
		<div class="bc__box100">
			
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>BL NO</th>
							<th>수주수량</th>
							<th>수주일자</th>
							<th>납기일자</th>
							<th>지연일</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($actList as $i=>$row){
						$num = $pageNum+$i+1;
						$today = date("Y-m-d",time());
						
						$sday  = new DateTime($today);
						$nday  = new DateTime($row->PLN_DATE);

						$xxx = $sday->diff($nday);
						
					?>

						<tr>
							<td><?php echo $row->BL_NO; ?></td>
							<td class="right"><?php echo number_format($row->QTY); ?></td>
							<td class="cen"><?php echo substr($row->ACT_DATE,0,10); ?></td>
							<td class="cen"><?php echo substr($row->PLN_DATE,0,10); ?></td>
							<td class="cen"><?php echo $xxx->d; ?></td>
						</tr>

					<?php
					}
					if(empty($actList)){
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

			<div class="pagination">
				<?php echo $this->data['pagenation'];?>
				<?
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

</div>


<div id="pop_container">
	
	<div class="info_content">
		<div class="ajaxContent">			
			
			<h2>
				엑셀업로드
				<span class="material-icons close">clear</span>
			</h2>
			<div class="formContainer">
				
				<form name="codeHead" id="codeHead" method="post" action="<?php echo base_url('excel/upload_act')?>" enctype="multipart/form-data" onsubmit="return xlsxupload(this)">
					<input type="hidden" name="table" value="T_TEMP">
					<div class="register_form">
						<fieldset class="form_1">
							<legend>이용정보</legend>
							<table>
								<tbody>
									<tr>
										<th><label class="l_id">코드</label></th>
										<td>
											<input type="file" name="xfile" id="xfile" value=""  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
										</td>
									</tr>
									<tr>
										<th><label class="l_id">시작행선택</label></th>
										<td>
											<input type="text" name="rownum" id="rownum" value="" class="form_input" size="5" />
										</td>
									</tr>
									<tr>
										<td colspan="2">

											<p>확장자(.xlsx)만 등록가능합니다.</p>
											<p>데이터 시작열을 입력해주세요</p>

										</td>
									</tr>
									
								</tbody>
							</table>
						</fieldset>
						
						<div class="bcont">
							<input type="submit" class="submitBtn blue_btn" value="입력"/>
						</div>
						
					</div>

				</form>

			</div>

		</div>
	</div>

</div>




<script>
var IDX = "<?php echo $idx?>";



$(".write_xlsx").on("click",function(){
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
});


$(document).on("click","h2 > span.close",function(){

	//$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('act/a2/')?>"+qstr+"&perpage="+$(this).val();
	
});



/*자재정보삭제*/
$(".del_mater").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('bom/ajax_delete_materials/')?>",{idx:idx},function(data){
		alert(data.text);
		if(data.set == 1) location.reload();
	},"JSON");
});



$("select[name='seq']").on("change",function(){
	var code = $(this).val();
	switch(code){
		case "COMPONENT":
			$("input[name='set']").hide();
			$(".setdate").show();
			break;
		case "COMPONENT_NM":
			$("input[name='set']").show();
			$(".setdate").hide();
			break;
	}
});









$("input[name='insert1'],input[name='insert2']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});


function xlsxupload(f){
	
	var file = $("#xfile").val();

	if(!file){
		alert("xlsx파일을 등록하세요");
		return false;
	}

	return;


}

</script>