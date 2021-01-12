
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




$(document).on('keydown', function (e) { 
    if (e.keyCode == 116 || (e.ctrlKey && e.keyCode == 82)) { 
        $(window).unbind("beforeunload"); //shortcuts for F5 and CTRL+F5 and CTRL+R
	} 
}); 
$(document).on("click", "a", function () { 
    $(window).unbind("beforeunload");
}); 
$(document).on("click", "button", function () { 
    $(window).unbind("beforeunload");
}); 
$(document).on("submit", "form", function () { 
    $(window).unbind("beforeunload");
}); 
$(document).on("click", "input[type=submit]", function () { 
	$(window).unbind("beforeunload");
});
$(document).on("click", "span", function () { 
	$(window).unbind("beforeunload");
});

//이거 있어야 return이 잘나옴
$(window).load(function(){
	$('.savehidden').focus().select();
}); 

$(window).on("beforeunload",function (e){ 
	$.ajax({
		url:"<?php echo base_url('register/pageexit')?>"
	});
	return true;
});

</script>


</body>
</html>