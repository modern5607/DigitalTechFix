<h2>
	엑셀업로드
	<span class="material-icons close">clear</span>
</h2>
<div class="formContainer">

	<form name="codeHead" id="codeHead" enctype="multipart/form-data" onsubmit="return xlsxupload(this)">
		<input type="hidden" name="hidx" value="<?=$str['hidx']?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id">코드</label></th>
							<td>
								<input type="file" name="xfile" id="xfile" value="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
							</td>
						</tr>
						
						<tr>
							<th><label class="l_id">시작행선택</label></th>
							<td>
								<input type="text" name="rownum" id="rownum" value="2" class="form_input" size="5" />
							</td>
						</tr>
						<tr>
							<td colspan="2">

								<p>확장자(.xlsx)만 등록가능합니다.</p>
								<p>데이터 시작열을 입력해주세요</p>

							</td>
							<tr>
								<td colspan="2">
									<p class="result"></p>
								</td>
							</tr>
							
						</tr>

					</tbody>
				</table>
			</fieldset>

			<div class="bcont">
				<input type="button" class="submitBtn blue_btn" value="입력" />
			</div>

		</div>

	</form>

</div>



<script>

$('.submitBtn').click(function(){
	// console.log("ASDF");


	var formData = new FormData($("#codeHead")[0]);
	var result='';
	$.ajax({
		type: "post",
		url: "<?=base_url('excel/upload_bom')?>",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		dataType:"json",
		success: function (data) {
			console.log(data);
			
			for(key in data)
			{
				console.log(data[key]);
				result += data[key];
			}
			console.log(result);
			$(".result").html(result);

		}
	});

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