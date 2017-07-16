function SaveSlider(el)
{
	var frem=$('#fslide').serialize();
	var ff=el.children[0].children[0].children[0].innerHTML;
	var hj=$("#fum");
	if ($("#fum").attr('readonly')=='readonly')
	{
		$("#fslide .finput").each(function(){ $(this).removeAttr("readonly");});
		el.children[0].children[0].children[0].innerHTML='Сохранить';
		$(el.children[0].children[0].children[0]).removeClass('edit').addClass('save')
		return false;
	}
	$.ajax({
		url: 'modules/banners/ajax.php?&act=saveslider',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				el.children[0].children[0].children[0].innerHTML='Успешно!';
				$("#fslide .finput").each(function(){ $(this).attr({readonly:"readonly"});});
			}
		}
	});
	return false;
}
function SaveNewSlider(el)
{
	var frem=$('#fslide').serialize();
	var ff=el.children[0].children[0].children[0].innerHTML;
	$.ajax({
		url: 'modules/banners/ajax.php?&act=savenewsl',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				gotourl('include.php?place=banners');
			}
		}
	});
	return false;
}
function GetNewSlider(el)
{
	var html=el.innerHTML;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onAfterExpand = function (sender) {
		tinyMCEInit('editor1'); 
		return true;};
  	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '600',maxHeight:'700',src: 'modules/banners/ajax.php?&act=newslider', 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Новый баннер'} );		 
}
function nwhch(el){
	$('#foto_main').width($('input[name="width"]').val());
	$('#foto_main').height($('input[name="height"]').val());
	$('#foto_main').attr('src','//'+window.location.host+'/thumb.php?id=' + $('#url_abs_nohost').val() + '&x='+$('input[name="width"]').val()+'&y='+$('input[name="height"]').val()+'&crop');
}
function nswhch(el){
	$('#foto_main').attr('src','//'+window.location.host+'/thumb.php?id=' + $('#url_abs_nohost').val() + '&x=770&y=437');
}

function del_ban(el)
{
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	type=tel.substr(0,3);
	$.ajax({url: 'modules/banners/ajax.php?&act=del_ban&id=' + id+'&type='+type ,cache: false,
		success: function(html){
			if(parseInt(html) > 0){
				if($('#'+tel)) $('#'+tel).remove();	}}
	});

}
function SaveBanner(el)
{
	var frem=$('#fban').serialize();
	var ff=el.children[0].children[0].children[0].innerHTML;
	var hj=$("#fum");
	if ($("#fum").attr('readonly')=='readonly')
	{
		$("#fban .finput").each(function(){ $(this).removeAttr("readonly");});
		el.children[0].children[0].children[0].innerHTML='Сохранить';
		$(el.children[0].children[0].children[0]).removeClass('edit').addClass('save')
		return false;
	}
	$.ajax({
		url: 'modules/banners/ajax.php?&act=savebanner',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				el.children[0].children[0].children[0].innerHTML='Успешно!';
				$("#fban .finput").each(function(){ $(this).attr({readonly:"readonly"});});
			}
		}
	});
	return false;
}
function SaveSlBanner(el)
{
	var frem=$('#fsban').serialize();
	var ff=el.children[0].children[0].children[0].innerHTML;
	var hj=$("#fum");
	if ($("#fum").attr('readonly')=='readonly')
	{
		$("#fsban .finput").each(function(){ $(this).removeAttr("readonly");});
		el.children[0].children[0].children[0].innerHTML='Сохранить';
		$(el.children[0].children[0].children[0]).removeClass('edit').addClass('save')
		return false;
	}
	$.ajax({
		url: 'modules/banners/ajax.php?&act=saveslbanner',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				el.children[0].children[0].children[0].innerHTML='Успешно!';
				$("#fban .finput").each(function(){ $(this).attr({readonly:"readonly"});});
			}
		}
	});
	return false;
}

function view_slbanner(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onAfterExpand = function (sender) {
		tinyMCEInit('editor1'); 
		return true;};
	hs.Expander.prototype.onBeforeClose = function (sender) {
		$("#fban .finput").each(function(){ $(this).attr({readonly:"readonly"});});
		$("td .btn_c:contains('Сохранить')").html('Редактировать');
		return true;};
	hs.Expander.prototype.onAfterClose = function (sender) {

		gotourl('include.php?place=banners');
	}
   	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '600', maxHeight: '760',src: 'modules/banners/ajax.php?&act=EditSlBanner&id='+inid, 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Баннер '+inid} );

}

function view_banner(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onAfterExpand = function (sender) {
		tinyMCEInit('editor1'); 
		return true;};
	hs.Expander.prototype.onBeforeClose = function (sender) {
		$("#fban .finput").each(function(){ $(this).attr({readonly:"readonly"});});
		$("td .btn_c:contains('Сохранить')").html('Редактировать');
		return true;};
	hs.Expander.prototype.onAfterClose = function (sender) {

		gotourl('include.php?place=banners');
	}
   	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '600', maxHeight: '750',src: 'modules/banners/ajax.php?&act=EditBanner&id='+inid, 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Баннер '+inid} );

}
function view_slider(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onAfterExpand = function (sender) {
		tinyMCEInit('editor1'); 
		return true;};
	hs.Expander.prototype.onBeforeClose = function (sender) {
		$("#fslide .finput").each(function(){ $(this).attr({readonly:"readonly"});});
		$("td .btn_c:contains('Сохранить')").html('Редактировать');
		return true;};
	hs.Expander.prototype.onAfterClose = function (sender) {
		if (sender.contentId=='hhslide') gotourl('include.php?place=banners');
	}
   	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '600', maxHeight: '750',src: 'modules/banners/ajax.php?&act=EditSlider&id='+inid, 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Баннер '+inid,contentId:'hhslide'} );

}
function SaveNewSlBanner(el){
	var frem=$('#fsban').serialize();
	var ff=el.children[0].children[0].children[0].innerHTML;
	$.ajax({
		url: 'modules/banners/ajax.php?&act=savenewslbanner',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				gotourl('include.php?place=banners');
			}
		}
	});
	return false;
}

function SaveNewBanner(el){
	var frem=$('#fban').serialize();
	var ff=el.children[0].children[0].children[0].innerHTML;
	$.ajax({
		url: 'modules/banners/ajax.php?&act=savenew',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if(parseInt(html) > 0)
			{
				gotourl('include.php?place=banners');
			}
		}
	});
	return false;
}
function GetNewSLBanner(el){
	var html=el.innerHTML;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onAfterExpand = function (sender) {
		tinyMCEInit('editor1'); 
		return true;};
  	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '600',maxHeight:'760',src: 'modules/banners/ajax.php?&act=newslbanner', 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Новый баннер'} );		 
}

function GetNewBanner(el){
	var html=el.innerHTML;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onAfterExpand = function (sender) {
		tinyMCEInit('editor1'); 
		return true;};
  	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '600',maxHeight:'700',src: 'modules/banners/ajax.php?&act=newbanner', 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Новый баннер'} );		 
}
function on_off(el){
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	type=tel.substr(0,3);
	$.ajax({url: 'modules/banners/ajax.php?&act=banner_on&id=' + id+'&type='+type ,cache: false,
		success: function(html){
			if (html == 'Включен'){$(el).removeClass('edis');}
			if (html == 'Выключен'){if (!($(el).hasClass('edis'))){$(el).addClass('edis');}}}
	});
}


function mtban(event){
	event = event || window.event
	var clickedElem = event.target || event.srcElement;
	tel=clickedElem.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
    if (hasClass(clickedElem, 'check')){
		btn_state(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'idel')){
    	if (confirm('Вы действительно хотите удалить баннер?')) {
			del_ban(clickedElem);
		}
		return;
	}
    if (hasClass(clickedElem, 'edit')){
		if (tel.indexOf('ban_row_')==0){
			view_banner(clickedElem,id);
		}
		if (tel.indexOf('sld_row_')==0){
			view_slider(clickedElem,id);
		}
		if (tel.indexOf('slb_row_')==0){
			view_slbanner(clickedElem,id);
		}
		return;
	}
    if (hasClass(clickedElem, 'enb') || hasClass(clickedElem, 'vsb')){
		on_off(clickedElem);
		return;
	}
}
function hasClass(elem, className) {
	return new RegExp("(^|\\s)"+className+"(\\s|$)").test(elem.className)
}
