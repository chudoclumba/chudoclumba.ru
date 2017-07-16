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
