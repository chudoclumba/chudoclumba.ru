$(document).ready(function(){

	$("#mtbl").bind("ajaxSend", function(){
		$('#loader').css({'display' : 'block'});
	}).bind("ajaxComplete", function(){
		$('#loader').css({'display' : 'none'});
		$("#myTable").trigger("update"); 
		$("#myTable").trigger("sorton",[[[1,0]]]);
	});
});

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
	$('.tbl_center').css({'height' : $('#line3').height()});
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
	thid.value = '...';
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=set_params' + '&param_name=' + par_name,
		cache: false,
		success: function(html){
			thid.style.background = (html == 1) ? '#fff' : '#DFFFE0';
			thid.value = (html == 1) ? 'Выключить' : 'Включить';
		}
	});
}


function get_selects(rv)
{
	var str = '';
	$("input[name^='" + rv + "[']").each(function(){
		if($(this).attr('checked') == true && $(this).attr('name'))
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
	$('outer_frame').update('');
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