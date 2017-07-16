function _posDivs(){
	nav = navigator.appName;
	if (nav != "Netscape") 	{
		var w = document.body.clientWidth;
	}else{
		var w = window.innerWidth;	}
	if (w>1000) {
		w=Math.round((w-253-734)/2);
	}else {
		w=0;}
	$('#stmenu').css('right',w);
	var tempVar = $(document).width();
	$('#fixed').width(tempVar);
}

function show_prd_vs()
{
	$.ajax({
		url: 'ishop/show_prd_vs/1',
		cache: true,
		success: function(html){
			$('.path_p2').html('Äîáàâëåíî ê ñòðàâíåíèþ');
			$('.outer_content').html(html);
			$.scrollTo(0, 800, {queue:true});
		}
	});	
}

function vs_remove(id)
{
	$.ajax({
		url: 'ishop/show_remove_prd_vs/' + id,
		cache: true,
		success: function(html){
			$('.outer_content').html(html);
		}
	});
}

function vs_vs()
{
	alert('2');
}
function offsetÒPosition(element) {
    var offsetLeft = 0, offsetTop = 0;
    do {
        offsetLeft += element.offsetLeft;
        offsetTop  += element.offsetTop;
    } while (element = element.offsetParent);
    return  offsetTop;
}
$(document).ready(function(){
	var mleft = $('.sel').width() + 'px';
	if ($('.sel').width() > 180){mleft = '180px'};
	var mbot = $('.sub').height() - 16 + 'px';
	$('.sub').css('margin', '-17px 0 -' + mbot + ' ' + mleft).fadeIn();
	
	$(window).scroll(function (){
		var div = $(document).scrollTop(); 
 
		if(div > 200) {
			$('#fixed').fadeIn('fast');
		} else {
			$('#fixed').fadeOut('fast');
		}
	});

	_posDivs();
	$(window).resize(function(){_posDivs();});
	

	$(".main").bind("ajaxSend", function(){
		$('#loader').css({'display' : 'block'});
	}).bind("ajaxComplete", function(){
		$('#loader').css({'display' : 'none'});
	});
	
	$(".add_prd").each(function(){
		$(this).click(function(){
			$.ajax({
				url: $(this).attr('href'),
				cache: true,
				success: function(html){
					eval(html);
				}
			});
			return false;
		});
	});
	$(".add_prd_b").each(function(){
		$(this).click(function(){
			$.ajax({
				url: 'ishop/'+$(this).attr('id'),
				cache: true,
				success: function(html){
					eval(html);
				}
			});
			return false;
		});
	});
	
/*
	$("table.h100").parent('td').each(function(){
		$(this).children('table.h100').css({'height' : $(this).height()});
	});*/

}).resize(function(){_posDivs();});