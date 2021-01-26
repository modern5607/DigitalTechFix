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
				
				<?php
					if(!empty($GJ_GB)){
					?>
						<label for="gjgb">공정구분</label>
						<select name="gjgb" style="padding:4px 10px; border:1px solid #ddd;">
						<?php
						foreach($GJ_GB as $row){
							$selected8 = ($str['gjgb'] == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected8;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}
					?>
					
					<label for="component">자재코드</label>
					<input type="text" name="component" id="component" value="<?php echo $str['component']?>" size="15" />

					<label for="comp_name">자재명</label>
					<input type="text" name="comp_name" id="comp_name" value="<?php echo $str['comp_name']?>" size="15" />

					<label for="spec">규격</label>
					<input type="text" name="spec" id="spec" value="<?php echo $str['spec']?>" size="15" />

					
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<!-- <span class="btn print excelDown"><i class="material-icons">get_app</i>출력하기</span> -->
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>자재코드</th>
						<th>자재명</th>
						<th>규격</th>
						<th>재고량</th>
						<th>단위</th>
						<th>출고량</th>
						<th>거래처</th>
						<th>출고일</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($materialList as $i=>$row){
					$num = $pageNum+$i+1;
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><strong class="<?= $row->IDX;?> compent"><?php echo $row->COMPONENT; ?></strong></td>
						<td ><?php echo $row->COMPONENT_NM; ?></td>
						<td><?php echo $row->SPEC; ?></td>
						<td class="right"><?php echo number_format($row->STOCK); ?></td>
						<td class="cen"><?php echo $row->UNIT; ?></td>
						<td class="cen"><input type="number" name="outqty" class="<?= $row->IDX;?> outqty"></td>
						<td class="cen">
							<select name="account" class="<?= $row->IDX;?> account">
								<option value="">::선택::</option>
								<?php 
									foreach($ACCOUNT as $i => $rows){
										?>
										<option value="<?= $rows->IDX ?>"><?= $rows->CUST_NM ?></option>
										<?php
									}
								?>
							</select>
						</td>
						<td class="cen"><input type="text" name="outdate" class="outdate <?= $row->IDX;?>" style="min-width:125px;"></td>
						<td class="cen"><span class="mod stock_update" data-stock="<?= $row->STOCK;?>" data-idx="<?= $row->IDX;?>" data-gjgb="<?= $row->GJ_GB;?>">출고하기</span></td>
					</tr>

				<?php
				}
				if(empty($materialList)){
				?>

					<tr>
						<td colspan="15" class="list_none">제품정보가 없습니다.</td>
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




<script>
var IDX = "<?php echo $idx?>";
$(window).unbind("beforeunload");
var qstr = "<?php echo $qstr ?>";	

$(function(){

	if(IDX != ""){
		var offset = $("#poc_"+IDX).offset();
		/*$(".tbl-content").animate({
			scrollTop:offset.top
		},100);*/
		$(".tbl-content").scrollTop(offset.top - 200);
	}
});

$(".limitset select").on("change",function(){

	var pattern = /\&perpage=[0-9]{2,3}/g;
		
	if(qstr.indexOf('perpage') != -1){
		qstr = qstr.replace(pattern,"");		
	}
	location.href="<?php echo base_url('mat/stocklist/')?>"+qstr+"&perpage="+$(this).val();
	
});

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
            $.post("<?php echo base_url('acon/delete_editor_image')?>",{path},function(data){
				if(data != "error"){
					alert(data);
				}
            });*/
        }
    }
});


/*자재정보삭제*/
$(".del_mater").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('bom/ajax_delete_materials/')?>",{idx:idx},function(data){
		alert(data.text);
		if(data.set == 1) location.reload();
	},"JSON");
});




$("#INTO_DATE,#REPL_DATE,.outdate").datetimepicker({
	format:'Y-m-d H:i:s',
	lang:'ko-KR'
});


$(".mod_material").on("click",function(){
	var idx = $(this).data("idx");
	location.href="<?php echo base_url('bom/materials/')?>"+idx;
});


$(".add_material").on("click",function(){
	location.href="<?php echo base_url('bom/materials')?>";
});






//출고 업데이트
$(".stock_update").on("click",function(){
	var formData = new FormData($("#bizRegForm")[0]);

	var idx = $(this).data('idx');
	var gjgb = $(this).data('gjgb');
	var stock = Number($(this).data('stock'));
	var compent = $("."+idx+".compent").text();
	var outqty = $("."+idx+".outqty").val();
	var outdate = $("."+idx+".outdate").val();
	var account = $("."+idx+".account").val();
	var qty = stock-outqty;

	if(outqty <= 0){
		alert("출고량을 입력해주세요.");
		$("."+idx+".outqty").focus();
		return false;
	}
	if(outqty > stock){
		alert("출고량이 재고량보다 많습니다.");
		$("."+idx+".outqty").focus();
		return false;
	}
	if(account == ""){
		alert("거래처를 선택해주세요.");
		$("."+idx+".account").focus();
		return false;
	}
	if(outdate == ""){
		alert("출고일을 입력해주세요.");
		$("."+idx+".outdate").focus();

		return false;
	}
	if(confirm(compent+'를 출고하시겠습니까?') !== false){
		$.post("<?php echo base_url('/mat/stock_update')?>",
		{qty:qty,outqty:outqty,gjgb:gjgb,outdate:outdate,account:account,idx:idx},
		function(data){
			alert('출고되었습니다.');
			location.reload();
    	});
	}
});

$(".outqty").on("change",function(){
	if($(this).val() == ""){
		$(this).parents("tr").find(".outdate").val('');
	}else{
		<?php date_default_timezone_set('Asia/Seoul'); ?>
		$(this).parents("tr").find(".outdate").val('<?= date("Y-m-d H:i:s") ?>');
	}
});
</script>