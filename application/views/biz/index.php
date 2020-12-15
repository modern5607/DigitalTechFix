<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div id="pageTitle">
	<h1><?php echo $title;?></h1>
</div>
<div class="bdcont_100">
		
		<div class="bc__box100">

			<header>
				<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
					<form>
						<label>업체명</label>
						<input type="text" name="custnm" value="<?php echo $str['custnm']; ?>" />
						<label>주소</label>
						<input type="text" name="address" value="<?php echo $str['address']; ?>" />
						<button class="btn"><i class="material-icons">search</i></button>
					</form>
				</div>
				<span class="btn add add_biz"><i class="material-icons">add</i>업체추가</span>
				<span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span>
			</header> 

			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>순번</th>
							<th>업체명</th>
							<th>주소</th>
                            <th>연락처</th>
                            <th>담당자</th>
							<th>주거래품목</th>
							<th>비고</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($bizList as $i=>$row){

					?>

						<tr>
							<td class="cen"><?php echo $row->IDX;?></td>
							<td><span class="mod_biz  link_s1" data-idx="<?php echo $row->IDX;?>"><?php echo $row->CUST_NM; ?></span></td>
                            <td><?php echo $row->ADDRESS;?></td>
							<td><?php echo $row->TEL;?></td>
							<td><?php echo $row->CUST_NAME;?></td>
							<td><?php echo $row->ITEM;?></td>
                            <td><?php echo $row->REMARK;?></td>
							<td></td>
						</tr>

					<?php
					}
					?>
					</tbody>
				</table>
			</div>

		</div>
			

	</div>


<div id="pop_container">
	
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>



<script>
$(".add_biz").on("click",function(){
		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('/biz/ajax_bizReg_form')?>",
			type     : "POST",
			dataType : "HTML",
			data     : {mode:"add"},
			success  : function(data){
				$(".ajaxContent").html(data);
			},
			error    : function(xhr,textStatus,errorThrown){
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});

});


$(".mod_biz").on("click",function(){
		
	var idx = $(this).data("idx");

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('/biz/ajax_bizReg_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"mod",IDX:idx},
		success  : function(data){
			$(".ajaxContent").html(data);
		},
		error    : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});

});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});
</script>