<? if(count($msgs) > 0) { ?>
 <div class="o_hd">�����������:</div>
<? foreach($msgs as $msg) { ?>
		<div class="o_box">
		 <div class="o_name"><?=$msg['name']?></div>
		 <div class="o_msg"><?=$msg['msg']?></div>
		</div>
<? } ?>
<? } ?>
 <div class="o_hd">�������� �����������:</div>
 <div>
<form method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>
<tr><td class="fb_id">���</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" name="name" id="name" value=""></td></tr>
<tr><td class="fb_id">�����������</td></tr>
<tr><td class="fb_input"><textarea class="fxtxt" id="msg" name="msg" id="msg"></textarea></td></tr>
<tr><td class="fb_input" align="left"><input name="otpravitb" class="fbb" type="submit" value="���������"></td></tr>
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
				required : "<span class=\"frm_err\">������� ���� ���</span>",
				minlength : "<span class=\"frm_err\">������� �� �����, ��� 4 �������.</span>"
			},
			msg : {
				required : "<span class=\"frm_err2\">������� ��� �����������</span>",
				minlength : "<span class=\"frm_err2\">������� �� �����, ��� 4 �������.</span>"
			}
		}
	});
	jQuery("otpravitb").click(function(){
		jQuery("#frmFeedback").validate();
	});
});
</script>
 </div>