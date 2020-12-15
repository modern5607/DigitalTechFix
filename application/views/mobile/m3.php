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
							<th>MSAB</th>
							<th>생산예정일</th>
							<th>수량</th>
							
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($actList as $i=>$row){
						$num = $pageNum+$i+1;
						$pTime = ($row->PT*$row->QTY)/60;
					?>

						<tr class="apt_view" data-idx="<?php echo $row->IDX?>" >
							<td><span class="link_s1"><?php echo $row->BL_NO; ?></span></td>
							<td class="cen"><?php echo $row->MSAB; ?></td>
							<td class="cen"><?php echo substr($row->ST_DATE,0,10); ?></td>
							<td class="right"><?php echo number_format($row->QTY); ?></td>
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


</div>


<div id="pop_container">
	
	<div class="info_content" style="height:auto; min-width:330px;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>


<script>


$(".apt_view").on("click",function(){
	var idx = $(this).data("idx"); //actplan idx

	$(".ajaxContent").html('');

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('mobile/ajax_actplan_info')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {idx:idx},
		success  : function(data){
			$(".ajaxContent").html(data);
		},
		error    : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	})
});

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



</script>