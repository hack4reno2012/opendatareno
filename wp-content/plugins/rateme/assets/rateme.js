$(document).ready(function() {
	
	$(".rating span").click(function(){
		$(this).removeClass("selected");
		$(this).siblings().removeClass("selected");
		
		var num = $(this).attr("class");
		if(num == "one") num = 1;
		if(num == "two") num = 2;
		if(num == "three") num = 3;
		if(num == "four") num = 4;
		if(num == "five") num = 5;
		$("input[name=wprm_rating]").val(num);
		
		$(this).addClass("selected");
		$(this).nextAll("span").addClass("selected");
	});

});