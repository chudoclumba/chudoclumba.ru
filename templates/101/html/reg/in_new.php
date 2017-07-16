<div class="reg_form">
<div class="w100 col-md-6 col-sm-6 col-xs-12">
                    <form action="" id="reginw" method="post">
                        <div class="form-fields">
                            <h2>Login</h2>
 <p id="login_error" style="display: none;background-color : #e51a4b; font-weight: bold;font-size: 19px"></p>                           <p>
                                <label>Email адрес <span class="required">*</span></label>
                                <input name="login" id="login" type="text">
                            </p>
                            <p>
                                <label>Пароль <span class="required">*</span></label>
                                <input name="pass" id="pass" type="password">
                            </p>
                        </div>
                        <div class="form-action">
                            <p class="lost_password"><a href="#">Забыли пароль?</a></p>
                            <p class="lost_password"><a href="#">Htubcnhfwbz</a></p><input type="submit" value="Регистрация">rtyt
                            <input type="submit" value="Вход" onclick="$('#reginw').submit(); return false;">
                            <label>
                        <input type="checkbox"> Запомнить меня </label>
                        </div>
                    </form>
                    <a class="reg_form" onclick="$('#reginw').submit();">Вход</a>
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
				$('#mc3reg').html(hv.user);
				$('#fixeddiv').html(hv.cart);
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

function Subm() 
{
	if ($("#w_login").val().length < 1 || $("#w_pass").val().length < 1) {
	    $("#login_error").show();
//	    $.fancybox.resize();
	    return false;
	}
//	$.fancybox.showActivity();
	var dta=$(this).serializeArray();
	$.ajax({
		type		: "POST",
		cache	: false,
		url		: "wishlist/log_in",
		data		: dta,
		success: function(hh) {
			var hv=eval("(" + hh + ")");
			if (parseInt(hv.res) > 0){
				$.fancybox.close();
				$('.reg').html(hv.user);
				$('#fixeddiv').html(hv.cart);
				Add_to_wish($('#w_prd').val());
				return false;
			} else {
				$.fancybox(hh);
			}
		}
	});
	return false;
}	
</script>