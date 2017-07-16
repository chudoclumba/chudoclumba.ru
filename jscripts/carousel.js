$(".carousel-button-right").live('click',function(){right_carusel();});
$(".carousel-button-left").live('click',function(){left_carusel();});
function left_carusel(){var block_width = ($('.carousel-block').width() + 10)*2;
$(".carousel-items").css({"left":"-"+block_width+"px"}); 
$(".carousel-items .carousel-block").eq(-1).clone().prependTo(".carousel-items"); 
/*$(".carousel-items").animate({left: "0px"}, 200);*/
$(".carousel-items .carousel-block").eq(-1).remove(); 
$(".carousel-items .carousel-block").eq(-1).clone().prependTo(".carousel-items"); 
$(".carousel-items").animate({left: "0px"}, 500); 
$(".carousel-items .carousel-block").eq(-1).remove();}
function right_carusel(){var block_width = ($('.carousel-block').width() + 10)*2;
$(".carousel-items").animate({left: "-"+ block_width +"px"}, 500); 
setTimeout(function(){$(".carousel-items .carousel-block").eq(0).clone().appendTo(".carousel-items"); 
$(".carousel-items .carousel-block").eq(0).remove(); 
$(".carousel-items .carousel-block").eq(0).clone().appendTo(".carousel-items"); 
$(".carousel-items .carousel-block").eq(0).remove(); 
$(".carousel-items").css({"left":"0px"});}, 600);}