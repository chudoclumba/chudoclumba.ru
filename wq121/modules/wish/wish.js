
function prd_in_cart(el,inid)
{
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '850',src: 'modules/cart/ajax.php?act=prdincart&id='+inid, 
		 wrapperClassName: 'titlebar', headingText: 'Товар в корзинах '+inid} );

}

function Clear_wish(id)
{
	if (confirm('Очистить wishlist '+id+' ?')) {
	$.ajax({
		url: 'modules/wish/ajax.php?act=clearwish' + '&id=' + id,
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
