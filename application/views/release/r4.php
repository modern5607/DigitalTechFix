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
					<label for="re_date">반품일</label>
					<input type="text" class="calendar" name="re_date" id="re_date" value="<?= ($str['re_date']!="")?$str['re_date']:date("Y-m-d",strtotime('-7 day'))?>" /> ~ 
					<input type="text" class="calendar" name="re_date_end" id="re_date_end" value="<?= ($str['re_date_end']!="")?$str['re_date_end']:date("Y-m-d",time())?>" />

					<?php
					if(!empty($GJ_GB)){
					?>
						<label for="gjgb">공정구분</label>
						<select name="gjgb" id="gjgb" class="form_select">
							<option value="">all</option>
						<?php
						foreach($GJ_GB as $row){
						?>
							<option value="<?= $row->D_CODE?>" <?= ($str['gjgb'] == $row->D_CODE)?"selected":"";?>><?= $row->D_NAME;?></option>
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
						<th>작업완료일</th>
						<th>공정코드</th>
						<th>공정명</th>
						<th>출고여부</th>
						<th>출고완료일</th>
						<th>반품여부</th>
						<th>반품일</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					
				<?php
				
				foreach($relList as $i=>$row){
					$num = $pageNum+$i+1;

					$chkClass = $reDate = "";
					if($row->RE_YN == "Y"){
						$chkClass = "mod_craim link_s1";
						$reDate = substr($row->RE_DATE,0,10);
					}
				?>

					<tr>
						<td class="cen"><?= $num;?></td>
						<td><?= $row->LOT_NO; ?></td>
						<td><span class="<?= $chkClass;?>" data-idx="<?= $row->TIDX;?>" data-customer="<?= $row->CUSTOMER;?>"><?= $row->BL_NO; ?></span></td>
						<td class="right"><?= number_format($row->QTY); ?></td>
						<td class="cen"><?= (empty($row->END_DATE)||$row->END_DATE=='0000-00-00 00:00:00')?"": $row->END_DATE ?></td>
						<td class="cen"><?=$row->GJ_GB ?></td>
						<td><?= $row->NAME; ?></td>
						<td class="cen"><?= $row->CG_YN; ?></td>
						<td class="cen"><?= substr($row->CG_DATE,0,10); ?></td>
						<td class="cen"><?= $row->RE_YN; ?></td>
						<td class="cen"><?= $reDate;  ?></td>
						<td class="cen"><!--button type="button" class="mod mod_material" data-idx="<?= $row->IDX;?>">수정</button--></td>
					</tr>

				<?php
				}
				if(empty($relList)){
				?>

					<tr>
						<td colspan="13" class="list_none">출력정보가 없습니다.</td>
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
	
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">			
			
			<h2>
				클래임등록
				<span class="material-icons close">clear</span>
			</h2>
			<div class="formContainer">
				
				<form name="claimform" id="claimform" method="post" action="<?= base_url('rel/claimform_update')?>" enctype="multipart/form-data" onsubmit="return claimform_update(this)">
					<input type="hidden" name="idx" id="H_IDX" value="">
					<div class="register_form">
						<fieldset class="form_1">
							<legend>이용정보</legend>
							<table>
								<tbody>
									<tr>
										<th><label class="l_id">BL_NO</label></th>
										<td>
											<span id="blNo"></span>
										</td>
									</tr>
									<tr>
										<th><label class="l_id">거래처</label></th>
										<td>
											<span id="customer"></span>
										</td>
									</tr>
									<tr>
										<th><label class="l_id">사유</label></th>
										<td>
											<textarea name="REMARK" id="REMARK" class="form_input input_100">

											</textarea>
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
var IDX = "<?= $idx?>";

$('#REMARK').summernote({
    height:100,
    lang: 'ko-KR',
	toolbar:false,
	dialogsFade: true,
	disableDragAndDrop: true, //드래그앤드랍true:비활성
    callbacks: {
        onImageUpload : function (files, editor, welEditable) {
            console.log('SummerNote image upload : ', files);
            //sendFile(files, editor, welEditable, '#summernote');
        },
        onMediaDelete : function($target, editor, welEditable) {
            /*const path = $target.attr("src");
            console.log(path);
            $.post("<?= base_url('acon/delete_editor_image')?>",{path},function(data){
				if(data != "error"){
					alert(data);
				}
            });*/
        }
    }
});


$(".mod_craim").on("click",function(){
	var idx = $(this).data("idx");
	var customer = $(this).data("customer");
	var blNo = $(this).text();

	$("#blNo").text(blNo);
	$("#customer").text(customer);
	$("#H_IDX").val(idx);

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
	$(window).unbind("beforeunload");
var qstr = "<?= $qstr ?>";
	location.href="<?= base_url('rel/r4/')?>"+qstr+"&perpage="+$(this).val();
	
});







$("input[name='re_date'],input[name='re_date_end']").datetimepicker({
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