
<div id="pageTitle">
	<h1><?php echo $title;?></h1>
</div>
	
<div class="bdcont">
	
	<div class="bc__box">

		<header>
			<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
			<form>
				<input type="hidden" name="d_code" value="" />
				<input type="hidden" name="d_name" value="" />
				<input type="hidden" name="d_use" value="" />
				<label>code</label>
				<input type="text" name="code" value="<?php echo $str['code']; ?>" />
				<label>name</label>
				<input type="text" name="name" value="<?php echo $str['name']; ?>" />
				<label>사용유무</label>
				<select name="use" style="padding:4px 10px; border:1px solid #ddd;">
					<option value="">전체</option>
					<option value="Y" <?php echo ($str['use'] == "Y")?"selected":""; ?>>사용</option>
					<option value="N" <?php echo ($str['use'] == "N")?"selected":""; ?>>미사용</option>
				</select>
				<button class="btn"><i class="material-icons">search</i></button>
			</form>
			</div>
			<span class="btn add add_head"><i class="material-icons">add</i>추가</span>
			<span class="btn print print_head"><i class="material-icons">get_app</i>인쇄</span>
		</header> 

		<div id="cocdHead" class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>code</th>
						<th>name</th>
						<th>사용유무</th>
						<th>비고</th>
						<th class="pHide"></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($headList as $i=>$row){
					$overClass = ($H_IDX == $row->IDX)?" style='background:#fffed0'":"";
				?>

					<tr <?php echo $overClass; ?>>
						<td><?php echo $row->CODE; ?></td>
						<td><a href="<?php echo base_url('mdm/index/'.$qstr.'&hid='.$row->IDX);?>" class="link_s1"><?php echo $row->NAME; ?></a></td>
						<td class="cen"><?php echo ($row->USE_YN == "Y")?"사용":"미사용";?></td>
						<td><?php echo $row->REMARK;?></td>
						<td class="pHide"><button type="button" class="mod mod_head" data-idx="<?php echo $row->IDX;?>">수정</button></td>
					</tr>

				<?php
				}
				?>
				</tbody>
			</table>
		</div>

	</div>
		

</div>

<div class="bdcont">
	<div class="bc__box">
		<header>
		<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form>
			<input type="hidden" name="code" value="<?php echo $str['code']?>" />
			<input type="hidden" name="name" value="<?php echo $str['name']?>" />
			<input type="hidden" name="use" value="<?php echo $str['use']?>" />
			<input type="hidden" name="hid" value="<?php echo $H_IDX?>" />
			<label>code</label>
			<input type="text" name="d_code" value="<?php echo $str['d_code']; ?>" />
			<label>name</label>
			<input type="text" name="d_name" value="<?php echo $str['d_name']; ?>" />
			<label>사용유무</label>
			<select name="d_use" style="padding:4px 10px; border:1px solid #ddd;">
				<option value="">전체</option>
				<option value="Y" <?php echo ($str['d_use'] == "Y")?"selected":""; ?>>사용</option>
				<option value="N" <?php echo ($str['d_use'] == "N")?"selected":""; ?>>미사용</option>
			</select>
			<button class="btn"><i class="material-icons">search</i></button>
		</form>
		</div>
		<?php if($de_show_chk){ //hid값이 없는경우는 노출안됨 ?>
			<span class="btn add add_detail" data-hidx="<?php echo $H_IDX;?>"><i class="material-icons">add</i>추가</span>
			<a href="<?php echo base_url('mdm/index');?>" class="btn">전체</a>
		<?php } ?>
			<span class="btn print print_detail"><i class="material-icons">get_app</i>엑셀다운로드</span>
		</header>
		
		<div id="cocdDetail" class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>head-code</th>
						<th>code</th>
						<th>name</th>
						<th>사용유무</th>
						<th>비고</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($detailList as $i=>$row){
				?>

					<tr>
						<td><?php echo $row->H_CODE; ?></td>
						<td><?php echo $row->CODE; ?></td>
						<td><?php echo $row->NAME; ?></td>
						<td class="cen"><?php echo ($row->USE_YN == "Y")?"사용":"미사용";?></td>
						<td><?php echo $row->REMARK; ?></td>
						<td><button type="button" class="mod mod_detail" data-idx="<?php echo $row->IDX;?>">수정</button></td>
					</tr>

				<?php
				}
				if(empty($detailList)){
				?>
					<tr>
						<td colspan="6" style="padding:30px 0;">상위공통코드를 선택하세요</td>
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
	
	$(".print_head").on("click",function(){
		$(".pHide").hide();
		PrintElem("#cocdHead");
	});

	/*$(".print_detail").on("click",function(){
		PrintElem("#cocdDetail");
	});*/

	$(".add_head").on("click",function(){
		
		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('mdm/ajax_cocdHead_form')?>",
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
		})

	});


	$(".add_detail").on("click",function(){
		
		var hidx = $(this).data("hidx");
		
		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('mdm/ajax_cocdDetail_form')?>",
			type     : "POST",
			dataType : "HTML",
			data     : {mode:"add",hidx:hidx},
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


	$(".mod_head").on("click",function(){
		
		var idx = $(this).data("idx");

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('mdm/ajax_cocdHead_form')?>",
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

	$(".mod_detail").on("click",function(){
		
		var idx = $(this).data("idx");

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('mdm/ajax_cocdDetail_form')?>",
			type     : "POST",
			dataType : "HTML",
			data     : {mode:"mod",idx:idx},
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

	
	$(".print_detail").on("click",function(){
		var HIDX = "<?php echo $H_IDX?>";
		var H_IDX = (HIDX != "")?"/"+HIDX:"";
		if(confirm('Detail List를 엑셀다운로드 하시겠습니까?') !== false){
			location.href = "<?php echo base_url('mdm/excelDown')?>"+H_IDX;
		}
	});


	$(document).on("click","h2 > span.close",function(){

		$(".ajaxContent").html('');
		$("#pop_container").fadeOut();
		$(".info_content").css("top","-50%");
		
	});

</script>