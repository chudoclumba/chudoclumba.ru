$(document).ready(function(){ 
	var touch 	= $('#touch-menu');
	var menu 	= $('.menu');

	$(touch).on('click', function(e) {
		e.preventDefault();
		menu.slideToggle();
	});
	
	$(window).resize(function(){
		var w = $(window).width();
		if(w > 767 && menu.is(':hidden')) {
			menu.removeAttr('style');
		}
	});
	
});
var min = false;//изначально шапка в обычном режиме
$(document).scroll(function() {
    if($(document).scrollTop()>10&&!min)
    {
        //изменение свойств шапки
        $("#fixeddiv").addClass('as');
        min = true;//шапка в мини режиме
    }
    if($(document).scrollTop()<=10&&min)
    {
        $("#fixeddiv").removeClass('as');
        //изменение свойств шапки
        min = false;//шапка в обычном режиме
    }
});