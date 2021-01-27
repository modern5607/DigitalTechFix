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
                    <label for="">일자</label>
                    <input type="text" name="sdate" value="<?php echo ($str['sdate']!="")?$str['sdate']: date("Y-m-d", strtotime("-1 month", time()))?>" size="12" autocomplete="off">~
                    <input type="text" name="edate" value="<?php echo ($str['edate']!="")?$str['edate']:date("Y-m-d",time())?>" size="12" autocomplete="off">

					
                    <button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
		</header>

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>일 자</th>
						<th>목표</th>
						<th>달성치</th>
						<th>달성률(%)</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($List as $i=>$row){
                    $num = $pageNum+$i+1;
                    $pl = $row->PL_KPI;
                    $ac = $row->AC_KPI;
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
                        <td class="cen"><?php echo $row->INSERT_DATE;?></td>
                        <td class="right"><?= $pl; ?></td>
                        <td class="right"><strong><?= $ac; ?></strong></td>
                        <td><strong><?= round($ac/$pl*100) ?>%</strong></td>
					</tr>

				<?php
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

$(".limitset select").on("change",function(){
	$(window).unbind("beforeunload");
var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('kpi/equip2/')?>"+qstr+"&perpage="+$(this).val();
	
});
</script>