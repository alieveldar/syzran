$(function() {
	$(".tvitem").bind("mouseenter", function(e){ $(this).toggleClass("hovered"); var pic=$(this).attr("pic"); var link=$(this).attr("link"); var capt=$(this).attr("capt");
	$(".TVPicture").html("<a href='"+link+"'><img src='"+pic+"' /></a>");	$(".TVLink").html("<a href='"+link+"'>"+capt+"</a>"); });
	$(".tvitem").bind("mouseleave", function(e){ $(this).toggleClass("hovered"); });
});