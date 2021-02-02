<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- 달력 및 에디터호출 -->
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<style>
.kpimean{
	float:right;font-size:15px;  display:flex;
	border:3px solid #ddd
}
.kpimean>p:last-child{
	border-right:0;
	color:#333;
}
.kpimean>p{
	margin:5px 0;
	padding:0 5px;
	border-right:1px solid #ccc;
}
</style>

<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<div style="float:left;">
				<form id="items_formupdate">
                    <label for="">일자</label>
                    <input type="text" name="sdate" value="<?php echo ($str['sdate']!="")?$str['sdate']: date("Y-m-d", strtotime("-1 month", time()))?>" size="12" autocomplete="off">~
                    <input type="text" name="edate" value="<?php echo ($str['edate']!="")?$str['edate']:date("Y-m-d",time())?>" size="12" autocomplete="off">

					
                    <button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<div class="kpimean">
				<p>목표 : 78%</p>
				<p>구축 전 : 75%</p>
				<p>구축 후 : <?php echo round($mean[0]->AV_CNT,1) ?>%</p>
			</div>
		</header>

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>Work_NO</th>
						<th>BL_NO</th>
						<th>작업시간</th>
						<th>수량</th>
						<th>P.T</th>
						<th>작업효율</th>
						<th>작업완료일</th>
						<th>생산라인</th>
					</tr>
				</thead>
				<tbody>
			<?php
				foreach($List as $i=>$row){
					$num = $pageNum+$i+1;
					if($row->SEQ == 1){
			?>
					<tr>
						<td class="cen"><?= $num ?></td>			
						<td><?= $row->PLN_NO ?></td>			
						<td><?= $row->BL_NO ?></td>			
						<td class="right"><?= $row->ACT_TIME ?></td>			
						<td class="right"><?= $row->QTY ?></td>			
						<td class="right"><?= $row->PT ?></td>			
						<td class="right"><?= $row->AC_TIME ?></td>		
						<td class="cen"><?= substr($row->END_DATE,0,10) ?></td>					
						<td><?= $row->M_LINE ?></td>			
					</tr>

			<?php
				}else{
					$pageNum += -1;
			?>
					<tr style="height:40px; background:#f3f8fd; text-align:right">
						<td colspan="6"><?= $row->BL_NO ?></td>		
						<td ><?= round($row->AC_TIME,1) ?>%</td>			
						<td colspan="2"></td>			
					</tr>
			<?php
				}
			}
				if(empty($List)){
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

	</div>
</div>


<script>

$("input[name='sdate'],input[name='edate']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

</script>