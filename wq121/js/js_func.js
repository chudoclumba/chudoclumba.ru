
$(document).ready(function(){
	$("#mtbl").bind("ajaxSend", function(){
		$('#loader').css({'display' : 'block'});
	}).bind("ajaxComplete", function(){
		$('#loader').css({'display' : 'none'});
		$("#myTable").trigger("update"); 
		$("#myTable").trigger("sorton",[[[1,0]]]);
	});
    $('#ffrm').submit(function (){
        return glfindsubm();
    });
});


function ztcl(event){
	event = event || window.event
	var clickedElem = event.target || event.srcElement
	tel=clickedElem.parentElement.id;
	id=tel.substr(8,tel.length-8);
     if (hasClass(clickedElem, 'vo')){
		view_order(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'vu')){
		view_user(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'idel')){
		delete_order(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'ild')){
		order_swload(clickedElem);
		return;
	}
}

jQuery.preloadImages = function() {
 for(var i = 0; i < arguments.length; i++) {
  jQuery("<img>").attr("src", arguments[i]);
 }
};

$.preloadImages(
  '/wq121/template/images/button_left.png',
  '/wq121/template/images/button_right.png',
  '/wq121/template/images/silk/ico_wish.png',
  '/wq121/template/images/silk/feed_star.png',
  '/wq121/template/images/silk/page_world.png',
  '/wq121/template/images/silk/flower_daisy.png',
  '/wq121/template/images/silk/chart_organisation.png',
  '/wq121/template/images/silk/user.png',
  '/wq121/template/images/silk/clipboard.png',
  '/wq121/template/images/silk/cart_full.png',
  '/wq121/template/images/silk/money_dollar.png',
  '/wq121/template/images/silk/cog.png',
  '/wq121/template/images/silk/mail.png',
  '/wq121/template/images/silk/text_list_bullets.png',
  '/wq121/template/images/silk/cross.png',
  '/wq121/template/images/silk/house.png'
 );


function prd_in_cart(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '850',src: 'modules/cart/ajax.php?act=prdincart&id='+inid, 
		 wrapperClassName: 'titlebar', headingText: 'Товар в корзинах '+inid} );

}
function prd_in_wish(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '850',src: 'modules/wish/ajax.php?act=prdinwish&id='+inid, 
		 wrapperClassName: 'titlebar', headingText: 'Товар в wishlist '+inid} );

}




function LoadSqllog(el){
	$.ajax({url: 'modules/ishop/ajax.php?&act=asql_log',cache: false,
		success: function(html){
			if (html.length >0){
				$('#dsqll').html(html);
				$($(el).next("div")[0]).show();
				$($(el).next("div")[0]).children('div').children('div').show();
			}
		}
	});
}

function restore_file(el){
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	$.ajax({url: 'modules/ishop/ajax.php?&act=restore_file' + '&value=' +escape(id)  ,cache: false,
		success: function(html){
			$('#mc3').html(html);
		}
	});

}
function delete_restfile(el){
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	$.ajax({url: 'modules/ishop/ajax.php?&act=delete_restfile' + '&value=' +escape(id)  ,cache: false,
		success: function(html){if (parseInt(html)==1){	$('#rst_row_'+id).remove();}}
	});

}


function on_off_user(el){
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	$.ajax({url: 'modules/ishop/ajax.php?&act=user_on' + '&id=' + id ,cache: false,
		success: function(html){
			if (html == 'active'){$(el).removeClass('eunv');}
			if (html == 'locked'){if (!($(el).hasClass('eunv'))){$(el).addClass('eunv');}}}
	});
}
function delete_pay(id)
{
	if (confirm('Удалить запись '+id+' ?')) {
	$.ajax({
		url: 'modules/payments/ajax.php?act=deletepay' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				if($('#' + id)) $('#' + id).remove();
			}
		}
	});
	}
}
function delete_user(el)
{
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	if (confirm('Вы действительно хотите удалить ?'+id)) {
	$.ajax({
		url: 'modules/ishop/ajax.php?act=delete_user' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				$(el.parentElement.parentElement).remove();
			}
		}
	});
	}
}

function save_order1(order_id, value, tip)
{
	$.ajax({url: 'modules/ishop/ajax.php?act=save_order1' + '&order_id=' + order_id + '&value=' + value + '&tip=' + tip,
		cache: false,
		success: function(html){}});
}


function glfindsubm(){
	if ($('#glfind').val().length>0){
		$.ajax({
			url: 'modules/ishop/ajax.php?act=gl_find',
			cache: false,
			type:'POST',
			data: $('#ffrm').serialize(),
			success: function (html){
				if ($('#mc4').html().length==0){
					$('#mc4').html($('#mc3').html());
				}
				$('#mc3').html(html);
			}
		});
	}
	return false;
}

function show_restore(){
		$.ajax({
			url: 'modules/ishop/ajax.php?act=restore_cat_show',
			cache: false,
			type:'GET',
			success: function (html){
				if ($('#mc4').html().length==0){
					$('#mc4').html($('#mc3').html());
				}
				$('#mc3').html(html);
			}
		});
	return false;
}


function swap_pan(){
	if($('editor1')) tinyMCEDeinit('editor1');
	if($('editor2')) tinyMCEDeinit('editor2');		
	$('#mc3').html($('#mc4').html());
	$('#mc4').html('');
}

function processEmbeddedScripts(html) {
    var tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    var scriptNodesList = tempDiv.getElementsByTagName('script');
    if (scriptNodesList.length > 0) {
        var wholeScript = '';
        for (var i = 0; i < scriptNodesList.length; i++) {
            var scriptCode = scriptNodesList.item(i);
            wholeScript = wholeScript + scriptCode.innerHTML + ' ';
        }
        var scriptElement = document.createElement('script');
        scriptElement.type = 'text/javascript';
        scriptElement.text = wholeScript;
        document.body.appendChild(scriptElement);
    }
}
 
function excludeEmbeddedScripts(html) {
    return html.replace(/<script(.|\s)*?\/script>/g, '');
}

function view_userorders(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.htmlExpand(null, { 
			objectType: 'ajax', width: '940',src: 'modules/ishop/ajax.php?act=view_userorders&userid='+inid, 
		 wrapperClassName: 'titlebar',anchor: 'auto',cacheAjax: false,preserveContent:true, headingText: 'Заказы клиента '+inid} );
}

function dodrop(event, dir)
{
	var dt = event.dataTransfer;
	var files = dt.files;

	var count = files.length;
	for (var i = 0; i < files.length; i++) {
		$.post("load.php", { content: files[i].getAsDataURL(), name: files[i].name, size: files[i].size, dir: dir},
		function(html){
			
		});
	}
}

function resize(){
/*	th=$('#line3').height();
	$('.tbl_center').css({'height' : th});
	$('.tbl_mcc1').css({'height' : th});
	tn=$('#thbtn').height();
	$('#thmf').css({'height' : th-tn});*/
	
}

function addslashes(str) {
str=str.replace(/\'/g,'\\\'');
str=str.replace(/\"/g,'\\"');
str=str.replace(/\\/g,'\\\\');
str=str.replace(/\0/g,'\\0');
return str;
}
function stripslashes(str) {
str=str.replace(/\\'/g,'\'');
str=str.replace(/\\"/g,'"');
str=str.replace(/\\\\/g,'\\');
str=str.replace(/\\0/g,'\0');
return str;
}

function set_param(par_name, thid)
{
	$(thid).html('...');
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=set_params' + '&param_name=' + par_name,
		cache: false,
		success: function(html){
			$(thid).html((html == 1) ? 'Включено' : 'Выключено');
			$('#ch_'+par_name).prop('checked',(html == 1));
		}
	});
}


function get_selects(rv)
{
	var str = '';
	$("input[name^='" + rv + "[']").each(function(){
		if($(this).attr('checked') == 'checked' && $(this).attr('name'))
		{
			var nm = $(this).attr('name');
			var nn = nm.replace(rv +  '[', '');
			var nn = nn.replace(']', '');
			str += '_' + nn;
		}
	});
	return str;
}



function load_photo(prd_id)
{
	var url = 'modules/foto/ajax.php?act=add_adv_foto' + '&name=' + $('url_abs_nohost_adv').value + '&name2=' + $('url_abs_nohost_adv2').value + '&title=' + $('naim').value + '&id=' + prd_id;
	new Ajax.Request(url, {
		method: 'get',
		onSuccess: function(transport) {
			$('adv_photosc').update(transport.responseText);
		}
	});
}

function ad_f(id, elid)
{
	if(!($(elid).children('input').attr('class')))
	{
		elid.innerHTML = '<input name="sort[' + id + ']" onchange="set_pos(' + id + ',this)" name="sort[' + id + ']" name="sort[' + id + ']" class="part_sort" type="text" value="' + elid.innerHTML + '">';
		$("input[name='sort[" + id + "]']").select();
	}
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function doClipboard(txt) {
    if(window.clipboardData) { // IE
        window.clipboardData.setData("Text", txt);
    }
    else { // не IE
        var fc = document.getElementById("flashCopier");
        if(!fc) {
            fc = document.createElement("div");
            fc.id = "flashCopier";
            document.body.appendChild(fc);
        }
        fc.innerHTML = '<embed src="/js/clipboard.swf" FlashVars="clipboard=' + encodeURIComponent(txt) + '" width="0" height="0" type="application/x-shockwave-flash"></embed>';
    }

    alert("Ссылка скопирована в буфер обмена");
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

function scroll_top()
{
	$('html,body').animate({scrollTop: $('html').offset().top}, 1);
}
function scroll_to(id)
{
  val='#' + id;
  destination = $(val).offset().top;
  $('#'+id).addClass("hig"); 
  $('html,body').animate({ scrollTop: destination}, 1100,   function() {setTimeout(function(){$('#'+id).removeClass("hig");},1000)} ); 
  
//  $('#'+id).bind('click', function(){$('#'+id).removeClass("hig");});
}

function gotourl(url)
{
	location.href = url;
	document.title = url;
}

function paste_frame()
{
	$('#outer_frame').html('<iframe name="ajax_frame" width="1" height="1" style="display:none;"></iframe>');
}

function remove_frame()
{
	$('#outer_frame').update('');
}

function show_hide_panel(id)
{
	if($('#' + id).css('display') == 'none')
	{

		eraseCookie('hide_' + id);
		$('#' + id).show('slow');
	}
	else
	{

		$('#' + id).hide('slow');
		createCookie('hide_' + id,'1',60);
	}
}

function explode(b, s)
{
    return s.split(b);
}
function view_cattext(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
   	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '650',src: 'modules/ishop/ajax.php?act=view_cattext&id='+inid, 
		 wrapperClassName: 'titlebar',targetX: 'posip 40px', targetY: null, headingText: 'Описание группы '+inid} );

}
function view_wish(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '650',src: 'modules/wish/ajax.php?act=viewwish&id='+inid, 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'WishList '+inid} );
}

function view_cart(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '650',src: 'modules/cart/ajax.php?act=viewcart&id='+inid, 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Корзина '+inid} );
}
function view_user(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onBeforeClose = function (sender) {
		$("#fuser .fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
		$("td .btn_c:contains('Сохранить')").html('Редактировать');
		return true;};
   	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '650',src: 'include.php?place=users&ajax=1&vID='+inid, 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Клиент '+inid} );

}
function view_usersus(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onBeforeClose = function (sender) {
		$("#fuser .fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
		$("td .btn_c:contains('Сохранить')").html('Редактировать');
		return true;};
   	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '650',src: 'include.php?place=susers&ajax=1&vID='+inid, 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Пользователь '+inid} );

}

function view_order(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.maxHeight = 750;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '920',src: 'modules/ishop/ajax.php?act=view_order&id='+inid, 
		 wrapperClassName: 'titlebar', headingText: 'Заказ '+inid} );
}
function view_statd(el,dt)
{
	hs.maxHeight = 750;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '920',src: 'modules/ishop/ajax.php?act=view_statd&dt='+dt, 
		 wrapperClassName: 'titlebar', headingText: 'Товары в заказах от '+dt} );
}
function view_statdt(el,dt,prd)
{
	hs.maxHeight = 750;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '920',src: 'modules/ishop/ajax.php?act=view_statdt&dt='+dt+'&prd='+prd, 
		 wrapperClassName: 'titlebar', headingText: 'Заказы по товару ' +el.innerHTML} );
}
function view_prdbyord(el,prd)
{
	hs.maxHeight = 750;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '920',src: 'modules/ishop/ajax.php?act=view_prdbyord&id='+prd, 
		 wrapperClassName: 'titlebar', headingText: 'Заказы по товару ' +el.innerHTML} );
}

function view_usermorders(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '940',src: 'modules/ishop/ajax.php?act=view_usermorders&id='+inid, 
		 wrapperClassName: 'titlebar', headingText: 'Заказы по email'} );
}
function save_order(el)
{
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	$.ajax({
		url: 'modules/ishop/ajax.php?act=save_order' + '&order_id=' + id + '&value=' + el.value,
		cache: false,
		success: function(html){
		}
	});
}
function delete_order(el)
{
	if (confirm('Вы действительно хотите удалить ?')) {
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	$.ajax({
		url: 'modules/ishop/ajax.php?act=delete_order' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				$(el.parentElement.parentElement).remove();
			}
		}
	});
	}
}
function order_swload(el)
{
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	$.ajax({
		url: 'modules/ishop/ajax.php?act=order_swload' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)		
				$(el).removeClass("iunld");
			else 
				$(el).addClass("iunld");
		}
	});
}

function delete_news(el)
{
	if (confirm('Вы действительно хотите удалить ?')) {
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	$.ajax({
		url: 'modules/news/ajax.php?act=delete_news' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				$(el.parentElement.parentElement).remove();
			}
		}
	});
	}
}
function SaveUser(el)
{
	var frem=$('#fuser').serialize();
	var ff=el.children[0].children[0].children[1].innerHTML;
	var hj=$("#fum");
	if ($("#fum").attr('readonly')=='readonly')
	{
		$("#fuser").find(".fbinp").each(function(){ $(this).removeAttr("readonly");});
		$("#fuser").find("input[type='checkbox']").each(function(){ $(this).removeAttr("disabled");});
		el.children[0].children[0].children[1].innerHTML='Сохранить';
		$("#p14").mask("+7 (999) 999-9999");
		$("#p15").mask("+7 (999) 999-9999");
		return false;
	}
	$.ajax({
		url: 'include.php?place=users&action=saveuser',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				el.children[0].children[0].children[1].innerHTML='Успешно!';
				$("#fuser").find(".fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
				$("#fuser").find("input[type='checkbox']").each(function(){ $(this).attr({disabled:"disabled"});});
			}
		}
	});
	return false;
}
function SaveUsersus(el)
{
	var frem=$('#fuser').serialize();
	var ff=el.children[0].children[0].children[0].innerHTML;
	var hj=$("#fum");
	if ($("#fum").attr('readonly')=='readonly')
	{
		$("#fuser .fbinp").each(function(){ $(this).removeAttr("readonly");});
		el.children[0].children[0].children[0].innerHTML='Сохранить';
		$(el.children[0].children[0].children[0]).removeClass('edit').addClass('save')
		return false;
	}
	$.ajax({
		url: 'include.php?place=susers&action=saveuser',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				el.children[0].children[0].children[0].innerHTML='Успешно!';
				$("#fuser .fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
			}
		}
	});
	return false;
}
function SaveNewUsersus(el)
{
	var frem=$('#fuser').serialize();
	var ff=el.children[0].children[0].children[0].innerHTML;
	$.ajax({
		url: 'include.php?place=susers&action=savenewusersus',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				el.children[0].children[0].children[0].innerHTML='Успешно!';
				$("#fuser .fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
			}
		}
	});
	return false;
}

function GetNewUsersus(el)
{
	var html=el.innerHTML;
	hs.anchor = 'auto';
	hs.preserveContent = false;
   	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '650',src: 'include.php?place=susers&ajax=1&new=1', 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Новый Пользователь'} );
}