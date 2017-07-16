
function prd_in_cart(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '850',src: 'modules/cart/ajax.php?act=prdincart&id='+inid, 
		 wrapperClassName: 'titlebar', headingText: 'Товар в корзинах '+inid} );

}
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
