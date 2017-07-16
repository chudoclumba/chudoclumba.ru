
function Clear_Oldcarts(el)
{
	$.ajax({
		url: 'modules/cart/ajax.php?act=clearoldcart',
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				el.children[0].children[0].children[1].innerHTML='Успешно!';
			}
		}
	});
}

function Clear_cart(id)
{
	if (confirm('Очистить корзину '+id+' ?')) {
	$.ajax({
		url: 'modules/cart/ajax.php?act=clearcart' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				if($('#c_row_cnt_' + id))  $('#c_row_cnt_' + id).html('');
			}
		}
	});
	}
}
function Delete_cart(id)
{
	if (confirm('Удалить корзину '+id+' ?')) {
	$.ajax({
		url: 'modules/cart/ajax.php?act=deletecart' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				if($('#c_row_' + id)) $('#c_row_' + id).remove();
			}
		}
	});
	}
}
