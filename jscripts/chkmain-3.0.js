function ClearWish(){
	$.ajax({
		url: 'wishlist/clear',
		cache: false,
		success: function (html)
		{
			$('#w_table').html(html);
		}
		});
}
function ClearWishItem(id){
	$.ajax({
		url: 'wishlist/removeprd/'+id,
		cache: false,
		success: function (html)
		{
			var er =JSON.parse(html);
			if (parseInt(er.res) == 1) {
				$('#wl_'+id).remove();
				$("#wsum").children('span').html(er.sum);
				
			}
		}
		});
}
function ClearCartItem(id){
	$.ajax({
		url: 'cart/removeprd/'+id,
		cache: false,
		success: function (html)
		{
			var er =JSON.parse(html);
			if (parseInt(er.res) == 1) {
				if (parseInt(er.sum) == 0){
					$('#cart_frm').html('<div class="area-title bdr"><h2>ВАША КОРЗИНА, к сожалению, пуста...</h2></div>');
					$('#cart_btn').html(er.cart);
				} else {
					$('#cp_'+id).remove();
					$('.sum').html(er.sum);
					$(".sumsk").html(er.sale);
					$(".sumall").html(er.asum);
					$('#cart_btn').html(er.cart);
				}
			}
		}
		});
}
function Move_to_wish(id){
	$.ajax({
		
		url: 'wishlist/movefcart/'+id+'/'+$('#cnt_'+id).val(),
		cache: false,
		success: function (html)
		{
			var er =JSON.parse(html);
			if (parseInt(er.res) == 1) {
				if (parseInt(er.sum) == 0){
					$('#cart_form').html('Корзина пуста...');
					$('#fixeddiv').html(er.cart);
				} else {
					$('#cp_'+id).remove();
					$("#ctsum").children('span').html(er.sum);
					$("#ctsale").children('span').html(er.sale);
					$("#ctasum").children('span').html(er.asum);
					$('#fixeddiv').html(er.cart);
				}
			}
		}
		});
}
function SetWishCnt(el) {
	tel=el.parentElement.id;
	id=tel.substr(3,tel.length-3);
	oval = el.value;
	if (el.value=='...'){
		return false;
	}
	el.value = '...';
	$.ajax({
			url: 'wishlist/setcnt/' + id+'/'+oval,
			cache: false,
			success: function(html){
				var er =JSON.parse(html);
				if (parseInt(er.res) == 1) {
					$("#wsum").children('span').html(er.sum);
					el.value=er.cnt;
					$(el).next('span').html(er.psum);
				}
			}
		});
}
function SetCartCnt(el) {
	tel=el.parentElement.parentElement.id;
	id=tel.substr(3,tel.length-3);
	oval = el.value;
	if (el.value=='...'){
		return false;
	}
	if (!(oval>0)) {$.fancybox('Введите число больше 0!');return false;}
	el.value = '...';
	$.ajax({
			url: 'cart/setcnt/' + id+'/'+oval,
			cache: false,
			success: function(html){
				var er =JSON.parse(html);
				if (parseInt(er.res) == 1) {
					$('.sum').html(er.sum);
					$(".sumsk").html(er.sale);
					$(".sumall").html(er.asum);
					$('#cart_btn').html(er.cart);
					el.value=er.cnt;
					$(el).parent().next().html(er.psum);
				}
			}
		});
}

function ClrDbg(){
	$.ajax({
		url: 'ajax/clrdbg',
		cache: false,
		success: function (html)
		{
			$('#debug').html('');
		}
		});
	
}


function vs_remove(id)
{
	$.ajax({
		url: 'ishop/show_remove_prd_vs/' + id,
		cache: true,
		success: function (html)
		{
			$('.outer_content').html(html);
		}
		});
}

function vs_vs(html)
{
	alert(html);
}
function showinp()
{
	$('#regfrm').css({'display' : 'block'});
}
function submit_handler(ds){
	$.ajax({
		url: 'user/logup',
		cache: false,
		type:'POST',
		data: $(ds).serialize(),
		success: function (html){
			$('#reg_form_in').html(html);
		}
	});
	return false;
}
function offsetmPosition(element)
{
	var offsetLeft = 0, offsetTop = 0;
	do {
		offsetLeft += element.offsetLeft;
		offsetTop  += element.offsetTop;
	}
	while (element = element.offsetParent);
	return  offsetTop;
}
function rmcmp(id)
{
	ret='';
	$.ajax({async: false,
		url: 'ajax?ttype=rmcmp&id='+id,
		cache: false,
		success: function(html)
		{
			ret=html;
//			$('#cmp_'+id).html(html);
//			$( "#compare_link" ).css({"display":"block","top":$("#cmp_"+id).position().top+30+'px',"left":$("#cmp_"+id).position().left+30+'px'});
		}
		});
	if (ret=='0'){
		window.close();
	}
	return true;
}
function addcmp(id)
{
	ht=$('#cmp_'+id).html();
	if (ht=='Добавлено в сравнение'){
		window.open('ishop/prd_vs');
	} else {
		$.ajax({async: false,
		url: 'ajax?ttype=addcmp&id='+id,
		cache: false,
		success: function(html)
		{
			$('#cmp_'+id).html(html);
			$( "#compare_link" ).css({"display":"block","top":$("#cmp_"+id).position().top+30+'px',"left":$("#cmp_"+id).position().left+30+'px'});
		}
		});
	}
	return false;
}

function Add_to_cart(el)
{
	if (typeof el == 'string') var eid=el;
	else var eid=el.id;
	var	crd=$("#"+eid).offset();
	$.ajax({async: false,
		url: 'ishop/addcart/'+eid,
		cache: false,
		success: function(html)
		{
			var er =JSON.parse(html);
			if (parseInt(er.res) == 1) {
				$("body").append(er.msg.replace(/sid/g,"fl"+eid));
				$("#cart_btn").html(er.cbtn);
				$("#fl"+eid).offset({top:crd.top, left:crd.left})
				$("#"+eid).children(".cart_btinfo").html(er.cnt);
				$("#fl"+eid).fadeIn("slow", function() {
					setTimeout(function() {
						$("#fl"+eid).fadeOut("slow", function(){
							$("#fl"+eid).remove();})}, 1000);});
			}
			if (parseInt(er.res) == 2) {
				$("body").append(er.msg.replace(/sid/g,"fl"+eid));
				$("#fl"+eid).offset({top:crd.top, left:crd.left})
				$("#fl"+eid).fadeIn("slow", function() {
					setTimeout(function() {
						$("#fl"+eid).fadeOut("slow", function(){
							$("#fl"+eid).remove();})}, 1000);});
			}
		}
		});

	return false;
}
function Add_to_wish(el)
{
	if (typeof el == 'string') var eid=el;
	else var eid=el.id;
	var	crd=$("#"+eid).offset();
	$.ajax({async: false,
		url: 'wishlist/addprd/'+eid,
		cache: false,
		success: function(html)
		{
			var er =JSON.parse(html);
			if (parseInt(er.res) == 1) {
				$("body").append(er.msg.replace(/sid/g,"fw"+eid));
				$("#fw"+eid).offset({top:crd.top, left:crd.left})
				$("#"+eid).children(".cart_btinfo").html(er.cnt);
				$("#fw"+eid).fadeIn("slow", function() {
					setTimeout(function() {
						$("#fw"+eid).fadeOut("slow", function(){
							$("#fw"+eid).remove();})}, 1000);});
			}
			if (parseInt(er.res) == 2) {
				$("body").append(er.msg.replace(/sid/g,"fw"+eid));
				$("#fw"+eid).offset({top:crd.top, left:crd.left})
				$("#fw"+eid).fadeIn("slow", function() {
					setTimeout(function() {
						$("#fw"+eid).fadeOut("slow", function(){
							$("#fw"+eid).remove();})}, 1000);});
			}
			if (parseInt(er.res) == 3) {
				RegWish(eid);
			}
		}
		});
	return false;
}
function RegWish(id){
		$.ajax({async: false,
		url: 'wishlist/regin/'+id,
		cache: false,
		success: function(html)
		{
			$.fancybox({'content':html,	'scrolling'	: 'no',
		maxWidth	: 600,
		maxHeight	: 400,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
	'titleShow':false,'onClosed':function() {$("#login_error").hide();}});
		}
		});
	
}
function Add_c(id)
{
	var ts=id;
	ts=$('#errcrt').val();
	$.ajax({
		url: 'ajax?ctype='+ts,
		cache: false,
		success: function(html)
		{
			if(parseInt(html) > 0)
			{
				$("#flow_cart_"+id).fadeOut("slow", function() {$("#flow_cart_"+id).remove(); Add_to_cart(id);});
			}
			else
			{
				$("#flow_cart_"+id).fadeOut("slow", function() 
				{
					$("#flow_cart_"+id).remove();
					alert('Введен неверный код. Добавление в корзину невозможно.');
					location.reload(true);
				});
			}
			
		}
		});
	return false;
}
function SetCnt(g){
	var yaGoalParams = {};
	yaGoalParams[g]=1;
	if ($('#counter').length>0){
		(function(w, c) {
		    (w[c] = w[c] || []).push(function() {
		        try {
		            w.yaCounter16195645 = new Ya.Metrika({id: 16195645});
		            yaCounter16195645.reachGoal("HInput",yaGoalParams);
		        }
		        catch(e) { }
		    });
		})(window, "yandex_metrika_callbacks");

	}
} 
function UpdateHit(){
	if ($('#right_hit').length>0 && $(document).scrollTop()<400 && $('#right_hit').attr('an')==1 && pageIsVisible()){
		ajax_show=false;
		$.ajax({async: false,
			url: 'ajax/newhit',
			cache: false,
			timeout:500,
			success: function(html)
			{
				$('#right_hit').fadeOut("slow", function()
					{$('#right_hit').html(html);
					 $('#right_hit').fadeIn("slow");
					});
			}
			});
	}
	
}
function pageIsVisible() {
    return (document.visibilityState||
	document.mozVisibilityState||
	document.msVisibilityState||
	document.webkitVisibilityState)
	=='visible';
}
function ClrSch(){
	$("INPUT#main_search").width(150);$("#search_h").height(0);
	$("#search_h").find('span').each(function(){$(this).hide()})
	$.ajax({async: false,
			url: 'ajax/clearsearch',
			cache: false,
			timeout:500,
			success: function(html)
			{
				if (html.length>0)	{
					$("INPUT#main_search").val('');
					location.href=html;
				}
			}
			});
}
var ajax_show=true;
$(document).ready(function ()
{
	$(".main").bind("ajaxSend", function()
	{
		if (ajax_show) $('#loader').css({'display' : 'block'});
	}
	).bind("ajaxComplete", function()
	{
		ajax_show=true;
		$('#loader').css({'display' : 'none'});
	}
	);
/*	$("table.h100").parent('td').each(function(){
		$(this).children('table.h100').css({'height' : $(this).height()});
	});*/
	$('input.icheck').iCheck({
	    checkboxClass: 'icheckbox_flat-green',
	    radioClass: 'iradio_flat-green'});	
	var g = location.href.split("#");
	if(g[1]!=undefined) SetCnt(g[1]);
/*	if ($('#right_hit').attr('an')==1) setInterval(UpdateHit, 10000);
	$("INPUT#main_search").autocomplete({ 
        source: "ajax/mainsearch",delay:500,minLength: 3 
      });//end of autocomplite() 
    $("INPUT#main_search").bind("focus",function(){$("INPUT#main_search").width(500);$("#search_h").height(23);	$("#search_h").find('span').each(function(){$(this).show()});});
    $("INPUT#main_search").bind("focusout",function(){
    	if ($("INPUT#main_search").val().length==0){$("INPUT#main_search").width(150);$("#search_h").height(0);	$("#search_h").find('span').each(function(){$(this).hide()})
}
    	});*/

}
);