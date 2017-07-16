<div class="reg_form"><div class="col-md-12">
<form action="" id="reginw" method="post">
<div class="form-fields">
<h2>ВХОД</h2>
<p id="login_error" style="display: none;background-color : #e51a4b; font-weight: bold;font-size: 19px"></p>                           
<p>
<label>Email адрес <span class="required">*</span></label>
<input name="login" id="login" type="text">
</p>
<p>
<label>Пароль <span class="required">*</span></label>
<input name="pass" id="pass" type="password">
</p>
</div>
<div class="form-action">
<p class="lost_password"><a href="user/restore">Забыли пароль?</a><br><a href="user/registr">Регистрация</a></p>
<input type="submit" value="Вход" onclick="$('#reginw').submit();return false;">
<label>
<input type="checkbox"> Запомнить меня </label>
</div>

</form>
</div>
</div>
<script>
    jQuery().ready(function(){
        jQuery("#reginw").validate({
        	submitHandler: function(f){
        			var dta=$(f).serializeArray();
	$.ajax({
		type		: "POST",
		cache	: false,
		url		: "user/a_login",
		data		: dta,
		success: function(hh) {
			var hv=eval("(" + hh + ")");
			if (parseInt(hv.res) == 1){
				$.fancybox.close();
				$('#u_menu').html(hv.user);
				if (hv.cbtn=='double_cart')	location.href = document.location.href;
				$('#cart_btn').html(hv.cbtn);
				return false;
			} else {
				if (parseInt(hv.res) >1){
					$('#login_error').html(hv.msg);
					$('#login_error').show();
					
				}	
			}
		}
	});

        	},
            rules : {pass : {required : true, minlength: 4},
            login : {required : true, email : true}
            },
            messages : {
            	pass : {required : "<span class=\"frm_err\">Заполните поле Пароль</span>", minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа!</span>"},
				login : {required : "<span class=\"frm_err\">Заполните поле Email</span>", email : "<span class=\"frm_err\">Неверно заполнено поле Email</span>"}
            }
        });
		jQuery("#send").click(function(){jQuery("#reginw").validate();});
    });
</script>