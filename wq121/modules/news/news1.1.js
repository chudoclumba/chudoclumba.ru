function Deleteus(id)
{
	$.ajax({
		url: 'modules/news/ajax.php?act=delete' + '&id=' + id,
		cache: false,
		success: function(html){
			if(parseInt(html) > 0)
			{
				if($('#rn_' + id)) $('#rn_' + id).remove();
			}
		}
	});
}
function mbtn_state(el){
	cd=$("input[name^='box']:checked").length;
		$('#btnc').prop('disabled',!(cd>0));
		$('#btns').prop('disabled',!(cd>0));
		$('#btnd').prop('disabled',!(cd>0));
}
function CreateMD()
{
	$.ajax({
		url: 'modules/news/ajax.php?act=create_md5',
		cache: false,
		success: function(html){
			$('#mc3').html(html);
		}
	});
}
function Send_m()
{
	var str = $("#newsfrm").serialize();
	$.post( 'modules/news/ajax.php?act=send_news', str, function(data) {
		$('#mc3').html(data);
	});
	
}