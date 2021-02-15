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
					<label for="">출고일</label>
						<input type="text" name="sdate" value="<?php echo ($str['sdate']!="")?$str['sdate']:date("Y-m-d",time())?>" size="12">~
						<input type="text" name="edate" value="<?php echo ($str['edate']!="")?$str['edate']:date("Y-m-d",time())?>" size="12">
					<label for="gjgb">공정구분</label>
						<select name="gjgb" style="padding:4px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
						<?php
						foreach($GJ_GB as $row){
							$selected8 = ($str['gjgb'] == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected8;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					
					<label for="component">자재코드</label>
					<input type="text" name="component" id="component" value="<?php echo $str['component']?>" size="15" />

					<label for="comp_name">자재명</label>
					<input type="text" name="comp_name" id="comp_name" value="<?php echo $str['comp_name']?>" size="15" />

					<label for="account">거래처</label>
						<select name="account" style="padding:4px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
						<?php
						foreach($ACCOUNT as $row){
							$selected8 = ($str['account'] == $row->IDX)?"selected":"";
						?>
							<option value="<?php echo $row->IDX?>" <?php echo $selected8;?>><?php echo $row->CUST_NM;?></option>
						<?php
						}
						?>
						</select>


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
						<th style="min-width: 100px;">출고량</th>
						<th>단위</th>
						<th>거래처</th>
						<th>출고일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($materialList)){
				foreach($materialList as $i=>$row){
					$num = $pageNum+$i+1;
					if($row->COMPONENT == "합계"){
						$qty = $row->OUT_QTY;
						$count = $row->UNIT;
					}else{
				?>
					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><strong class="compent"><?php echo $row->COMPONENT; ?></strong></td>
						<td ><?php echo $row->COMPONENT_NM; ?></td>
						<td><?php echo $row->SPEC; ?></td>
						<td class="right"><?php echo number_format($row->OUT_QTY); ?></td>
						<td class="cen"><?php echo $row->UNIT; ?></td>
						<td><?php echo $row->CUST_NM; ?></td>
						<td class="cen"><?php echo substr($row->TRANS_DATE,0,10); ?></td>
						
					</tr>

				<?php
				}}
				if($count != 0){
				?>
					<tr style="height:40px; background:#f3f8fd">
						<td class="right" colspan="4">총 합계</td>
						<td class="right"><strong><?= number_format($qty) ?></strong></td>
						<td class="right" colspan="1">총 수량</td>
						<td class="right"><strong><?= $count ?></strong></td>
						<td colspan="1"></td>
					</tr>
				<?php
				}else{
				?>
					<tr>
						<td colspan="15" class="list_none">제품정보가 없습니다.</td>
					</tr>
				<?php
				}	
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

$("input[name='sdate'],input[name='edate']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

</script>