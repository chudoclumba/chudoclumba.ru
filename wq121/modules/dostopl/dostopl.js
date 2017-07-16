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

function part_on(id)
{
	var link = $('#parte_' + id);
	if(link)
	{
		link.html("........");
		$.ajax({
			url: 'modules/sitemenu/ajax.php?place=sitemenu&act=part_on' + '&id=' + id,
			cache: false,
			success: function(html){
				link.html(html);
				link.attr({'class' : (html == 'Включен')?"part_on":"part_off"});
			}
		});
	}
}

function part_show(id)
{
	var link = $('#partv_' + id);
	if(link)
	{
		link.html(".......");
		$.ajax({
			url: 'modules/sitemenu/ajax.php?place=sitemenu&act=part_show' + '&id=' + id,
			cache: false,
			success: function(html){
				link.html(html);
				link.attr({'class' : (html == 'Видимый')?"part_on":"part_off"});

				if($('#vis_icon_' + id))
				{
					$('#vis_icon_' + id).attr({'title' : (html == 'Видимый')?"Видимый":"Скрытый"});
					$('#vis_icon_' + id).attr({'class' : (html == 'Видимый')?"nmp_r3 p0":"nmp_r3_2 p0"});
				}
			}
		});
	}
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
			    alert(html);
				module_html.html(html);
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