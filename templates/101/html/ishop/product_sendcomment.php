<tr><td class="mcnp">
<div class="o_hd">�������� �����:</div><div>
<form method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>
<tr><td class="fb_id">���</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" name="name" id="name" value="">
<input class="fbinp" type="hidden" name="email" id="email" value="">
</td></tr>
<tr><td class="fb_id">�����</td></tr>
<tr><td class="fb_input"><textarea class="fxtxt" id="msg" name="msg" id="msg"></textarea></td></tr>
<tr><td class="fb_input" align="left"><br>
<div class="add_msg_btn"><noscript>��� ��������� ����������� ��������� ������ ���������� �������� JavaScript</noscript></div>
</td></tr>
</table>
</form>
<script>
jQuery().ready(function(){jQuery("#frmFeedback").validate({rules : {name : {required : true, minlength: 4},msg : {required : true, minlength: 4}},
		messages : {name : {required : "<span class=\"frm_err\">������� ���� ���</span>",minlength : "<span class=\"frm_err\">������� �� �����, ��� 4 �������.</span>"},
		msg : {required : "<span class=\"frm_err2\">������� ��� �����</span>",minlength : "<span class=\"frm_err2\">������� �� �����, ��� 4 �������.</span>"}}});
 	jQuery("otpravitb").click(function(){jQuery("#frmFeedback").validate();});
	$('.add_msg_btn').html('<input name="test" class="fbb" type="hidden" value="1"><button name="otpravitb" class="albutton alorange" type="submit" value="���������"><span><span><span class="upload">���������</span></span></span></button>');
});
</script>
</div></td></tr>
