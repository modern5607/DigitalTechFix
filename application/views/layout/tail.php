
</div>
</div>




<script>
$(".mhide").on("click",function(){
	
	
	$(".menu_Content").animate({
		width:0
	},function(){
		$(".mhide").addClass('mshow');
		$(".mhide span").text("keyboard_arrow_right");
	});
	
	$("#smart_container").animate({
		paddingLeft:'15px'
	},function(){
		$(".mControl_show").show();
	});

	

});



$(".mshow").on("click",function(){
	
	

		$(".menu_Content").animate({
			width:'220px'
		},function(){
			$(".mhide").removeClass('mshow');
			$(".mhide span").text("keyboard_arrow_left");
		});
		
		$("#smart_container").animate({
			paddingLeft:'235px'
		});

		$(".mControl_show").hide();
	

});

//input autocoamplete off
$("input").attr("autocomplete", "off");

$(".search_submit").on("click",function(){
	var sta1 = $('input[name="sta1"]').val()
	var sta2 = $('input[name="sta2"]').val()
	
	if(sta1 > sta2){
		alert('검색 시작일이 종료일보다 늦을 수 없습니다.');
		return false;
	}
});
if($(".calendar").length>0){
$(".calendar").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});};
</script>


</body>
</html>