<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


<!-- 달력 및 에디터호출 -->
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<div style="float:left;">
				<form id="items_formupdate">
					<label for="">소요일</label>
					<input type="text" name="sdate" value="<?php echo ($str['sdate']!="")?$str['sdate']:date("Y-m-d",time())?>">~
					<input type="text" name="edate" value="<?php echo ($str['edate']!="")?$str['edate']:date("Y-m-d",time())?>">
					
					<?php
					if(!empty($GJ_GB)){
					?>
						<label for="gjgb">공정구분</label>
						<select name="gjgb" style="padding:4px 10px; border:1px solid #ddd;">
							<option value="">ALL</option>
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

					<label for="">B/L NO</label>
					<input type="text" name="bno" value="<?php echo $str['bno']?>">

					<label for="">자재코드</label>
					<input type="text" name="item" value="<?php echo $str['item']?>">
					
					
					<?php
					if(!empty($M_LINE)){

					?>
						<label for="mline">생산라인</label>
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


					<button class="search_submit"><i class="material-icons">search</i></button>

				</form>
			</div>
			<span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>순번</th>
						<th>BL_NO</th>
						<th>제품</th>
						<th>수량</th>
						<th>단위</th>
						<!--th>출고구분</th-->
						<th>자재코드</th>
						<th>자재명</th>
						<th>소요량</th>
						<th>소요일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($transList as $i=>$row){
					$num = $pageNum+$i+1;
					$useYn = ($row->USE_YN == "Y")?"사용":"미사용";
				?>

					<tr id="poc_<?php echo $row->TIDX;?>" class="pocbox">
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->BL_NO; ?></td>
						<td><?php echo $row->ITEM_NAME; ?></td>
						<td><?php echo $row->QTY; ?></td>
						<td><?php echo $row->UNIT; ?></td>
						<!--td class="cen"><?php echo $row->KIND; ?></td-->
						<td><?php echo $row->COMPONENT; ?></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td class="right"><?php echo number_format($row->OUT_QTY); ?></td>
						<td class="cen"><?php echo substr($row->TRANS_DATE,0,10); ?></td>
						<!--td class="cen"><button type="button" class="mod mod_stock" data-idx="<?php echo $row->IDX;?>">수정</button></td-->
					</tr>

				<?php
				}
				if(empty($transList)){
				?>

					<tr>
						<td colspan="9" class="list_none">제품정보가 없습니다.</td>
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


<script type="text/javascript">
//<!--
	$("input[name='sdate'],input[name='edate']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});


$(".limitset select").on("change",function(){
	$(window).unbind("beforeunload");
var qstr = "<?php echo $qstr ?>";	
	location.href="<?php echo base_url('bom/trans/')?>"+qstr+"&perpage="+$(this).val();
	
});


$(".print_head").on("click",function(){
	$(window).unbind("beforeunload");
var qstr = "<?php echo $qstr ?>";	
	if(confirm('자재소모현황를 엑셀다운로드 하시겠습니까?') !== false){
		location.href = "<?php echo base_url('bom/trans_excel')?>"+qstr;
	}
});


//-->
</script>