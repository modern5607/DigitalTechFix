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
				<form id="items_formupdate">
					
					
					
					<label for="blno">B/L NO</label>
					<input type="text" name="blno" id="blno" value="<?php echo $str['blno']?>" size="15" />

					
					<?php
					if(!empty($M_LINE)){
					?>
						<label for="">생산라인</label>
						<select name="mline" style="padding:4px 10px; border:1px solid #ddd;">
							<option value="">ALL</option>
						<?php
						foreach($M_LINE as $row){
							$selected1 = ($str['mline'] == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected1;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}
					?>
					<label for="date1">생산예정일</label>
					<input type="text" class="calendar" name="st1" value="<?php echo ($str['st1']!="")?$str['st1']:date("Y-m-d",time())?>" />-<input type="text" class="calendar" name="st2" value="<?php echo ($str['st2']!="")?$str['st2']:date("Y-m-d",strtotime('+1 week'))?>" /> 

					<label for="d1">전체</label>
					<input type="radio" name="finish" id="d1" value="" <?php echo ($str['finish'] == "")?"checked":"";?>>
					<label for="d2">완료</label>
					<input type="radio" name="finish" id="d2" value="Y" <?php echo ($str['finish'] == "Y")?"checked":"";?>>
					<label for="d3">미완료</label>
					<input type="radio" name="finish" id="d3" value="N" <?php echo ($str['finish'] == "N")?"checked":"";?>>

					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
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
						<?php if($this->data['pos'] == "smt"){ ?>
						<th>MSAB</th>
						<?php } ?>
						<th>생산예정일</th>
						<!--th>공정코드</th>
						<th>공정명</th-->
						<th>수량</th>
						<th>완료수량</th>
						<th>P.T</th>
						<th>시간</th>
						<th>생산LINE</th>
						<!--th>거래처</th>
						<th>생산시작</th>
						<th>생산종료</th-->
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($actList as $i=>$row){
					$num = $pageNum+$i+1;
					$pTime = ($row->PT*$row->QTY)/60;
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->LOT_NO; ?></td>
						<td><?php echo $row->BL_NO; ?></td>
						<?php if($this->data['pos'] == "smt"){ ?>
						<td class="cen"><?php echo $row->MSAB; ?></td>
						<?php } ?>
						<td class="cen"><?php echo substr($row->ST_DATE,0,10); ?></td>
						<!--td class="cen"><?php echo $row->GJ_CODE; ?></td>
						<td><?php echo $row->NAME; ?></td-->
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="right"><?php echo number_format($row->ACT_QTY); ?></td>
						<td class="right"><?php echo number_format($row->PT); ?></td>
						<td class="right"><?php echo number_format(round($pTime)); ?></td>
						<td class="cen"><?php echo $row->M_LINE; ?></td>
						<!--td><?php echo $row->CUSTOMER; ?></td>
						<td class="cen"><?php echo substr($row->STA_DATE,0,10); ?></td>
						<td class="cen"><?php echo substr($row->END_DATE,0,10); ?></td-->
						<td class="cen"><!--button type="button" class="mod mod_material" data-idx="<?php echo $row->IDX;?>">수정</button--></td>
					</tr>

				<?php
				}
				if(empty($actList)){
				?>

					<tr>
						<td colspan="16" class="list_none">제품정보가 없습니다.</td>
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





<script>
var IDX = "<?php echo $idx?>";



$(".write_xlsx").on("click",function(){
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
});


$('#items_formupdate input').keypress(function (e) {
  if (e.which == 13) {
    $('#items_formupdate').submit();
    return false;    //<---- Add this line
  }
});


$(document).on("click","h2 > span.close",function(){

	//$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('smt/s3/')?>"+qstr+"&perpage="+$(this).val();
	
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









$("input[name='st1'],input[name='st2']").datetimepicker({
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