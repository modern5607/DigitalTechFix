<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>



<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<div style="float:left;">
				<form id="items_formupdate">
					
					<label for="mid">아이디</label>
					<input type="text" name="mid" id="mid" value="<?php echo $str['mid']?>" size="6" />
					
					<label for="mname">이름</label>
					<input type="mname" name="mname" id="mname" value="<?php echo $str['mname']?>" size="6" />

					<label for="level">권한</label>
					<select name="level" style="padding:3px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
					<?php for($i=1; $i<=3; $i++){ ?>
						<option value="<?php echo $i?>" <?php echo ($str['level'] == $i)?"selected":"";?>><?php echo $i?></option>
					<?php } ?>
					</select>
					
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn print add_member"><i class="material-icons">add</i>신규등록</span-->
			<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span--> 
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>아이디</th>
						<th>권한</th>
						<th>이름</th>
						<th>부서</th>
						<th>직급</th>
						<th>전화</th>
						<th>휴대폰</th>
						<th>이메일</th>
						<th>입사일</th>
						<th>상태</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($memberList as $i=>$row){ ?>
				<tr>
					<td><?php echo $row->ID; ?></td>
					<td class="cen"><?php echo $row->LEVEL; ?></td>
					<td class="cen"><?php echo $row->NAME; ?></td>
					<td class="cen"><?php echo $row->PART; ?></td>
					<td class="cen"><?php echo $row->GRADE; ?></td>
					<td class="cen"><?php echo $row->TEL; ?></td>
					<td class="cen"><?php echo $row->HP; ?></td>
					<td><?php echo $row->EMAIL; ?></td>
					<td class="cen"><?php echo $row->FIRSTDAY; ?></td>
					<td class="cen"><?php echo ($row->STATE == 1) ? "사용" : "사용안함"; ?></td>
					<td class="cen">
						<span class="mod register_update" data-idx="<?php echo $row->IDX;?>">수정</span>
					</td>
				</tr>
		

				<?php
				}
				if(empty($memberList)){
				?>

					<tr>
						<td colspan="15" class="list_none">회원정보가 없습니다.</td>
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




<div id="pop_container">
	
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>






<script>

function memberformChk(f){
	
	var pwd  = $("input[name='PWD']").val();
	var chkP = $("input[name='PWD_CHK']").val();
	var id   = $("input[name='ID']").val();

	if(id == ""){
		alert("아이디를 입력하세요");
		$("input[name='ID']").focus();
		return false;
	}

	if(pwd == ""){
		alert("비밀번호를 입력하세요");
		$("input[name='PWD']").focus();
		return false;
	}

	if(pwd != chkP){
		alert("비밀번호를 확인해주세요");
		$("input[name='PWD']").focus();
		return false;
	}

	return
	
}

function idchk(id){
	$.post("<?php echo base_url('register/ajax_chk_memberid')?>",{id:id},function(data){
		$(".chk_msg").text(data.msg);
		
		setTimeout(function(){
			$(".chk_msg").text("");
			if(data.state == 2){
				$("input[name='ID']").val("");
				$("input[name='ID']").focus();
			}
		},2000);
		
	},"JSON");
}


$(document).on("change","#ID",function(){
	var id = $(this).val();
	idchk(id);
});



$(".add_member").on("click",function(){

	$(".ajaxContent").html('');

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url:"<?php echo base_url('register/ajax_set_memberinfo')?>",
		type : "post",
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
			
		}
		
	});


});

$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	location.reload();
	
});






$(".register_update").on("click",function(){
	var idx = $(this).data("idx");

	$(".ajaxContent").html('');

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url:"<?php echo base_url('mdm/ajax_set_memberinfo')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
		}
		
	});
});


</script>