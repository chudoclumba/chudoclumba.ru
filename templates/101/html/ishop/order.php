<form method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>
<tr><td class="fb_id">ФИО:</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" id="name" name="name" value="<?
	if(!empty($this->sets['mod_order_reg'])) echo $this->reg->user['fio'];
?>"></td></tr>
<tr><td class="fb_id">E-mail:</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" id="em" name="em" value="<?
	if(!empty($this->sets['mod_order_reg'])) echo $this->reg->user['email'];
?>"></td></tr>
<tr><td class="fb_id">Телефон:</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" id="tel" name="tel" value="<?
	if(!empty($this->sets['mod_order_reg'])) echo $this->reg->user['tel'];
?>"></td></tr>
<tr><td class="fb_id">Адрес:</td></tr>
<tr><td class="fb_input"><textarea class="fxtxt" id="ad" name="ad"><?
	if(!empty($this->sets['mod_order_reg'])) echo $this->reg->user['adr'];
?></textarea></td></tr>
<tr><td class="fb_input" align="left">
<button name="send" type="submit" value="Отправить" class="albutton alorange"><span><span><span class="ok">Отправить</span></span></span></button></td></tr>
</table>
<input type="hidden" name="posted" value="1">
</form>
<script>
    jQuery().ready(function(){
        jQuery("#frmFeedback").validate({
            rules : {
                name : {required : true, minlength: 4},
                em : {required : true, email : true},
                tel : {required : true, minlength: 4}
            },
            messages : {
                name : {
                    required : "<span class=\"frm_err\">Введите ваше ФИО</span>",
                    minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа.</span>"
                },
                em : {
                    required : "<span class=\"frm_err\">Введите ваш E-mail</span>",
                    email : "<span class=\"frm_err\">Введите правильно ваш Email</span>"
                },
                tel : {
                    required : "<span class=\"frm_err\">Введите ваш Телефон</span>",
                    minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа.</span>"
                }
            }
        });
        jQuery("send").click(function(){
            jQuery("#frmFeedback").validate();
        });
    });
</script>