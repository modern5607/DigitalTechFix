
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


$("#mback_close").on("click",function(){
	$("#m_menu").animate({
		right:"-200px"
	},function(){
		$("#m_back").fadeOut();	
	});
});


$(".mheader .right").on("click",function(){
	$("#m_back").fadeIn();	
	$("#m_menu").animate({
		right:"0px"
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


</script>


</body>
</html>