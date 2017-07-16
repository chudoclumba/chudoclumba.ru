<? if(count($msgs) > 0) { ?>
 <div class="o_hd">Комментарии:</div>
<? foreach($msgs as $msg) { ?>
		<div class="o_box">
		 <div class="o_name"><?=$msg['name']?></div>
		 <div class="o_msg"><?=$msg['msg']?></div>
		</div>
<? } ?>
<? } ?>
 <div class="o_hd">Добавить комментарий:</div>
 <div>
<form method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>
<tr><td class="fb_id">Имя</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" name="name" id="name" value=""></td></tr>
<tr><td class="fb_id">Комментарий</td></tr>
<tr><td class="fb_input"><textarea class="fxtxt" id="msg" name="msg" id="msg"></textarea></td></tr>
<tr><td class="fb_input" align="left"><input name="otpravitb" class="fbb" type="submit" value="Отправить"></td></tr>
</table>
</form>
<script>
jQuery().ready(function(){
	jQuery("#frmFeedback").validate({
		rules : {
			name : {required : true, minlength: 4},
			msg : {required : true, minlength: 4}
		},
		messages : {
			name : {
				required : "<span class=\"frm_err\">Введите ваше Имя</span>",
				minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа.</span>"
			},
			msg : {
				required : "<span class=\"frm_err2\">Введите ваш Комментарий</span>",
				minlength : "<span class=\"frm_err2\">Введите не менее, чем 4 символа.</span>"
			}
		}
	});
	jQuery("otpravitb").click(function(){
		jQuery("#frmFeedback").validate();
	});
});
</script>
 </div>