	<script type="text/javascript">
	//<![CDATA[
	// Demo NyroModal
	$(function() {
		$.nyroModalSettings({
			debug: false,
			processHandler: function(settings) {
				var url = settings.url;
				if (url && url.indexOf('http://www.youtube.com/watch?v=') == 0) {
					$.nyroModalSettings({
						type: 'swf',
						height: 355,
						width: 425,
						url: url.replace(new RegExp("watch\\?v=", "i"), 'v/')
					});
				}
			},
			endShowContent: function(elts, settings) {
				$('.resizeLink', elts.contentWrapper).click(function(e) {
					e.preventDefault();
					$.nyroModalSettings({
						width: Math.random()*1000,
						height: Math.random()*1000
					});
					return false;
				});
				$('.bgLink', elts.contentWrapper).click(function(e) {
					e.preventDefault();
					$.nyroModalSettings({
						bgColor: '#'+parseInt(255*Math.random()).toString(16)+parseInt(255*Math.random()).toString(16)+parseInt(255*Math.random()).toString(16)
					});
					return false;
				});
			}
		});
	});
	
	function cl(val)
	{
		$('#p1').val(val);
	}
	//]]>
	</script>

<p><b>Создание нового личного сообщения</b></p>
<form method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>
<tr><td class="fb_id">Номер анкеты получателя</td></tr>
<tr><td class="fb_input">
<input class="fbinps" type="text" id="p1" name="p1" value="<?=$data['p1']?>">
<a href="#test" class="nyroModal">Выбрать пользователя</a>
<div id="test" style="display: none; width: 300px;">
 <ul>
 <? foreach($users as $id=>$value) { ?>
  <li><a href="#" class="nyroModalClose" onclick="cl(<?=$value['id']?>)"><?=$value['fio']?></a></li>
 <? } ?> 
 </ul>
</div>
</td></tr>
<tr><td class="fb_id">Текст сообщения</td></tr>
<tr><td class="fb_input"><textarea class="fxtxt" id="p4" name="p4"></textarea></td></tr>
<tr><td class="fb_input" align="left"><input name="send" class="fbb" type="submit" value="Отправить"></td></tr>
</table>
<input type="hidden" name="posted" value="1">
</form>