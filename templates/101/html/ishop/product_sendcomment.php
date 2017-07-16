<tr><td class="mcnp">
<div class="o_hd">Добавить отзыв:</div><div>
<form method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>
<tr><td class="fb_id">Имя</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" name="name" id="name" value="">
<input class="fbinp" type="hidden" name="email" id="email" value="">
</td></tr>
<tr><td class="fb_id">Отзыв</td></tr>
<tr><td class="fb_input"><textarea class="fxtxt" id="msg" name="msg" id="msg"></textarea></td></tr>
<tr><td class="fb_input" align="left"><br>
<div class="add_msg_btn"><noscript>Для включения возможности оставлять отзывы необходимо включить JavaScript</noscript></div>
</td></tr>
</table>
</form>
<script>
jQuery().ready(function(){jQuery("#frmFeedback").validate({rules : {name : {required : true, minlength: 4},msg : {required : true, minlength: 4}},
		messages : {name : {required : "<span class=\"frm_err\">Введите ваше Имя</span>",minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа.</span>"},
		msg : {required : "<span class=\"frm_err2\">Введите ваш Отзыв</span>",minlength : "<span class=\"frm_err2\">Введите не менее, чем 4 символа.</span>"}}});
 	jQuery("otpravitb").click(function(){jQuery("#frmFeedback").validate();});
	$('.add_msg_btn').html('<input name="test" class="fbb" type="hidden" value="1"><button name="otpravitb" class="albutton alorange" type="submit" value="Отправить"><span><span><span class="upload">Отправить</span></span></span></button>');
});
</script>
</div></td></tr>
