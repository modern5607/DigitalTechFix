<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- 달력 및 에디터호출 -->
<link href="<?= base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?= base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?= base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?= base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?= base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<div id="pageTitle">
<h1><?= $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<div style="float:left;">
				<form id="items_formupdate">
					
					<label for="calendar">작업일자</label>
					<input type="text" class="calendar" name="sta" value="<?= ($str['sta']!="")?$str['sta']:date("Y-m-d",time());?>" autocomplete="off" />
					
					<label for="blno">B/L NO</label>
					<input type="text" name="blno" id="blno" value="<?= $str['blno']?>" size="15" />
					
					<?php
					if(!empty($MSAB)){
					?>
						<label for="">MSAB</label>
						<select name="mscode" style="padding:4px 10px; border:1px solid #ddd;">
							<option value="">ALL</option>
						<?php
						foreach($MSAB as $row){
							$selected1 = ($str['mscode'] == $row->D_NAME)?"selected":"";
						?>
							<option value="<?= $row->D_CODE?>" <?= $selected1;?>><?= $row->D_NAME;?></option>
						<?php
						}	
						?>
						</select>
					<?php
					}
					?>

					<?php 
						if(!empty($M_LINE)){ 
					?>
					<label for="mline">생산LINE</label>
					<select name="mline" id="mline" style="padding:3px 10px; border:1px solid #ddd;">
						<option value="">ALL</option>
						<?php 
						 
							foreach($M_LINE as $mline){
								$selected = ($str['mline'] == $mline->D_CODE)?"selected":"";
						?>
						<option value="<?= $mline->D_CODE; ?>" <?= $selected?>><?= $mline->D_NAME; ?></option>
						<?php 
							}
						?>
					</select>
					<?php
					}
					?>
										
					<label for="chkbox">자동</label>
					<input type="checkbox" name="chkbox" id="chkbox" value="1" <?= ($str['chkbox'] == "1")?"checked":"";?> size="15" />
			
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<!--span class="btn print print_actpln"><i class="material-icons">get_app</i>출력하기</span-->
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span--> 
		</header>
		<div class="tbl-content1">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>LOT NO</th>
						<th>BL NO</th>
						<?php if($this->data['pos'] == "smt"){ ?>
						<th>MSAB</th>
						<?php } ?>
						<th>작업일자</th>
						<th>공정코드</th>
						<th>공정명</th>
						<th style="width: 7%;">수량</th>
						<th>P.T</th>
						<th>생산LINE</th>
						<th>거래처</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($actList as $i=>$row){
					$num = $pageNum+$i+1;
					$finishBtn = ($row->FINISH == "Y")?"xn":"yn finish_btn";
					$bg = ($row->MSAB == "MM-B")?"style='background:#f7e7e7'":"";
					$disabled = ($row->FINISH == "Y")?"disabled":"";
				?>

					<tr <?= $bg;?>>
						<td class="cen"><?= $num;?></td>
						<td><?= $row->LOT_NO; ?></td>
						<td><?= $row->BL_NO; ?></td>
						<?php if($this->data['pos'] == "smt"){ ?>
						<td class="cen"><?= $row->MSAB; ?></td>
						<?php } ?>
						<td class="cen"><?= substr($row->ST_DATE,0,10); ?></td>
						<td class="cen"><?= $row->GJ_CODE; ?></td>
						<td><?= $row->NAME; ?></td>
						<td class="cen" ><input type="text" style="text-align: right;" name="qty" data-idx="<?=$row->IDX?>" value="<?= $row->QTY?>" <?=$disabled?>> </td>
						<td class="right"><?= number_format($row->PT); ?></td>
						<td class="cen"><?= $row->M_LINE; ?></td>
						<td style="width:100px"><?= $row->CUSTOMER; ?></td>
						<td><span class="mod finish_btn <?= $finishBtn;?>" data-blno="<?= $row->BL_NO; ?> " data-finish="<?= $row->FINISH;?>" data-idx="<?= $row->IDX;?>">작업완료</span></td>
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
			<?= $this->data['pagenation'];?>
			<?php
			if($this->data['cnt'] > 20){
			?>
			<div class="limitset">
				<select name="per_page">
					<option value="20" <?= ($perpage == 20)?"selected":"";?>>20</option>
					<option value="50" <?= ($perpage == 50)?"selected":"";?>>50</option>
					<option value="80" <?= ($perpage == 80)?"selected":"";?>>80</option>
					<option value="100" <?= ($perpage == 100)?"selected":"";?>>100</option>
				</select>
			</div>
			<?php
			}	
			?>
		</div>

	</div>
</div>



<div id="pop_container">
	
	<div id="info_content" class="info_content">
		
		<div class="ajaxContent"></div>
		
	</div>

</div>



<script>
// $(window).load(
// 	$('.xn').attr('disabled', true);
// );

var gjgb = "<?= $gjgb?>";

$(".limitset select").on("change",function(){
	$(window).unbind("beforeunload");
var qstr = "<?= $qstr ?>";
	location.href= "<?= base_url($path)?>"+qstr+"&perpage="+$(this).val();
});



setInterval(() => { 
	$(chkbox).trigger("change");
}, 60000);


$(chkbox).on("change",function() {
	var val = $("input[name='chkbox']:checked").val();
	$('input[name=chkbox]').val(val);
	if (val == '1'){
		$(".search_submit").trigger("click");
	}
});

$("input[name='qty']").change(function(){
	var idx = $(this).data("idx");
	var qty = $(this).val();
	console.log("change:"+qty+" "+idx);
	$.ajax({
		type: "post",
		url: "<?=base_url("smt/change_qty")?>",
		data: {
			idx:idx,
			qty:qty
		},
		dataType: "html",
		success: function (data) {
			if(data!=1)
			{
				alert("error, 데이터 변경에 실패했습니다.")
			}
		}
	});
});	


/*
$(".print_actpln").on("click",function(){

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	var qstr = "<?= $qstr?>";
	var pageNum = "<?= $pageNum?>";
	var perpage = "<?= $perpage?>";

	$.ajax({
		url:"<?= base_url('smt/print_actpln')?>"+qstr+"&pageNum="+pageNum+"&perpage="+perpage,
		type : "get",
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
			//document.getElementById("info_content").print();
		}
		
	});


});
*/

$(".finish_btn").on("click",function(){
	var idx = $(this).data("idx");
	var blno = $(this).data("blno");
	var finish = $(this).data("finish");
	var url = "";

	if(finish =='Y'){ 
		return;
	}
	if(confirm(blno+"품목의 작업을 완료하시겠습니까?") === true){
		if(gjgb == "SMT"){
			url = "<?= base_url('smt/finish_actpln')?>"; 
		}else{
			url = "<?= base_url('ass/finish_actpln')?>"; 
		}
		$.ajax({
			url:url,
			type : "post",
			data : {idx:idx},
			dataType : "json",
			success : function(data){
				console.log(data);
				if(data.error){
					alert('잘못된 작업지시입니다.\n관리자에게 문의하세요');
				}else{
					alert(data.msg);
					location.reload();
				}
				
			}
			
		});

	}

});



 
$(document).on('click','.printxx',function() {
	var g_oBeforeBody = $('#info_content .ajaxContent .formContainer').html();
	// 프린트를 보이는 그대로 나오기위한 셋팅
	window.onbeforeprint = function (ev) {
		
		$("body").html(g_oBeforeBody);
		$("body").css("background","#fff");
		$(".tbl-content").css("overflow-y","unset");
		$(".formContainer").css("overflow-y","unset");
	};

	window.print();
	location.reload();
});



$('#items_formupdate input').keypress(function (e) {
  if (e.which == 13) {
    $('#items_formupdate').submit();
    return false;    //<---- Add this line
  }
});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	location.reload();
	
});


$(".calendar").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

</script>