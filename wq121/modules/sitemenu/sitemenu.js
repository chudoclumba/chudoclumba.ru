function btn_state(el){
	cd=$("input[name^='box']:checked").length;
	$('#btncop').prop('disabled',!(cd>0));
	$('#btnmv').prop('disabled',!(cd>0));
	$('#btndel').prop('disabled',!(cd>0));
}

function on_off(el){
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	type=tel.substr(0,3);
	url='part_on';
	if ($(el).hasClass('vsb')){
		url='part_show';
	}	
	$.ajax({url: 'modules/sitemenu/ajax.php?&act=' + url + '&id=' + id ,cache: false,
		success: function(html){
			if (html == 'Включен'){$(el).removeClass('edis');}
			if (html == 'Выключен'){if (!($(el).hasClass('edis'))){$(el).addClass('edis');}}
			if (html == 'Видимый'){$(el).removeClass('eunv');}
			if (html == 'Скрытый'){if (!($(el).hasClass('eunv'))){$(el).addClass('eunv');}}}
	});
}


function mtclx(event){
	event = event || window.event
	var clickedElem = event.target || event.srcElement;
	tel=clickedElem.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
    if (hasClass(clickedElem, 'check')){
		btn_state(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'idel')){
    	if (confirm('Вы действительно хотите удалить cтраницу?')) {
			del_this_page(id);
		}
		return;
	}
    if (hasClass(clickedElem, 'iop')){
		if (tel.indexOf('cat_row_')==0){
			gotourl('include.php?place=sitemenu#update_html('+id+')');
		}
		return;
	}
    if (hasClass(clickedElem, 'dir_icon')){
		if (tel.indexOf('cat_row_')==0){
			if (!(event.shiftKey)){
				gotourl('include.php?place=sitemenu#open_sub_cats('+id+',1)');
			}else{
				gotourl('include.php?place=sitemenu#update_html('+id+')');
			}
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


function open_sub_cats(id)
{
	var module_html = $('#mc3');
	if(module_html)
	{
		$.ajax({
			url: 'modules/sitemenu/ajax.php?act=get_cats' + '&id=' + id,
			cache: false,
			success: function(html){
				$('#mc3').html(html);
				var pagename = $("option[value='" + $('#curent_cat').val() +"']").html()
				document.title = pagename;
				$('.fr1').html(pagename);
			}
		});
	}
}


function del_comments(prd_id)
{
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=del_comments&id=' + prd_id,
		cache: true,
		success: function(html){
			$('#prd_comm_' + prd_id).remove();
		}
	});
}


function apply_in_Sub(val)
{
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=update_titles' + '&id=' + val + '&title=' + escape($("#pagetitle").val()),
		cache: false,
		success: function(html){

		}
	});
}

function update_html(id)
{
	var module_html = $('#mc3');
	if(module_html)
	{
		$.ajax({
			url: 'modules/sitemenu/ajax.php?act=update_html' + '&id=' + id,
			cache: false,
			success: function(html){
				$('#mc3').html(html);
				document.title = 'Редактирование раздела "' + $('#title').val() +  '"';
				$('.fr1').html('Редактирование раздела "' + $('#title').val() +  '"');
			}
		});
	}

	/*tinyMCEDeinit('editor1');
	$(".cat_ttl").each(function() {

		$(this).newWindow({
			 windowTitle:$(this).html(),
			 ajaxURL: 'modules/sitemenu/ajax.php?act=update_html' + '&id=' + $(this).attr('cat_id'),


			 //onDragBegin: function(){alert("Drag begin")},
			 //onDragEnd: function(){alert("Drag end")},
			 //onResizeBegin: function(){alert("Resize begin")},
			 //onResizeEnd: function(){alert("Resize end")},
			 //onAjaxContentLoaded: function(){alert("Ajax Content Loaded")},
			 width:600,height:400
		});

	});
	tinyMCEInit('editor1');*/

}

function save_html()
{
	var str = $("form[name='forma']").serialize();
	$.post($("form[name='forma']").attr('action') + '&ajax=1', str, function(data) {
		alert(data);
		/*$(".get_opros").fadeOut("medium");*/
	});
}

function return_g()
{
	gotourl('include.php?place=sitemenu#open_sub_cats(' + $('#pid').val() + ')');
}


function return_g2(value, passwrd)
{
	gotourl('include.php?place=sitemenu#update_html(' + value + ','+ passwrd +')');
}


function Coping(dvalue)
{
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=copy_cats' + '&ids=' + get_selects('box') + '&id=' + dvalue,
		cache: false,
		success: function(html){
			$('#module_html').html(html);
		}
	});
}

function Moving(dvalue)
{
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=move_cats' + '&ids=' + get_selects('box') + '&id=' + dvalue,
		cache: false,
		success: function(html){
			$('#module_html').html(html);
		}
	});
}

function copy_to(b, c)
{
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=copy_to' + '&ids=' + b + '&id=' + $('#curent_cat').val() + '&kolvo=' + $("#kolvo").val(),
		cache: false,
		success: function(html){
			gotourl('include.php?place=sitemenu#open_sub_cats(' + $('#curent_cat').val() + ')');
		}
	});
}

function move_to(b, c)
{
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=move_to' + '&ids=' + b + '&id=' + $('#curent_cat').val(),
		cache: false,
		success: function(html){
			gotourl('include.php?place=sitemenu#open_sub_cats(' + $('#curent_cat').val() + ')');
		}
	});
}

function Deleting()
{
	var str = get_selects('box');
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=delete_cats' + '&ids=' + str,
		cache: false,
		success: function(html){

			if(parseInt(html) > 0)
			{
				var h = explode('_', str);
				for(var t=0; t<h.length;t++)
				{
					if($('#cat_row_' + h[t]))  $('#cat_row_' + h[t]).remove();
				}
			}
		}
	});
}

function add_this_page(id)
{
	if($('#mc3'))
	{
		$.ajax({
			url: 'modules/sitemenu/ajax.php?act=update_html' + '&id=-1&cat=' + id,
			cache: false,
			success: function(html){
				$('#mc3').html(html);
			}
		});
	}
}

function del_this_page(id)
{
	$.ajax({
		url: 'modules/sitemenu/ajax.php?act=del_page' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				if($('#cat_' + id)) $('#cat_' + id).remove();
				if($('#cat_row_' + id)) $('#cat_row_' + id).remove();
			}
		}
	});
}



function view_this_page(id)
{
	var module_html = $('#mc3');
	if(module_html)
	{
		$.ajax({
			url: 'modules/sitemenu/ajax.php?act=view_page' + '&id=' + id,
			cache: false,
			success: function(html){
				module_html.html(html);
	/*			$('#ifrm').css({'height' : th-100});*/
			}
		});

	}
}

function set_pos(id,box)
{
	value = box.value;
	if(value != '...')
	{
		box.value = '...';

		$.ajax({
			url: 'modules/sitemenu/ajax.php?act=set_pos' + '&id=' + id + '&value=' + value,
			cache: false,
			success: function(html){
				$(box).parent('td').html(value);
			}
		});
	}
}

function in_array(a, b)
{
	for (var index = 0; index < a.length; index++)
	{
		if (a[index] == b)
	 {
			return true;
		}
	}
	return false;
}