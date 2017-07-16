<div class="row">
<div class="col-lg-3 col-md-2 col-sm-1 hidden-xs">
</div>
<div class="col-lg-12 col-md-8 col-sm-8 col-xs-12">

<form method="post" action="" id="frmFeedback" enctype="multipart/form-data" accept-charset="UTF-8">
<div class="form-fields">
 <? if (isset($header) && !empty($header)) echo "<h2>$header</h2>";
$fp='';
if(isSet($data['posted'])) echo '<div class="err">Некорректно заполнены поля</div>'; 
if(isSet($errmsg)) echo '<div class="err">'.$errmsg.'</div>';
//style="width:$val['2']px"
foreach($form_data as $id=>$val) { 
	$txt_d = (!empty($txt_data[$id])) ? htmlspecialchars($txt_data[$id],ENT_COMPAT | ENT_XHTML,'UTF-8') : '';
	if (!empty($txt_data['p'.$id])) {
		$txt_d = htmlspecialchars($txt_data['p'.$id],ENT_COMPAT | ENT_XHTML,'UTF-8');
	}
    if($val['1'] == 'text') {?>
<p><label for="p<?=$id?>"><?=$val['0']?><?=($val['3']>=1)?' <span class="required">*</span>':''?></label>
<input  type="text" id="p<?=$id?>" name="p<?=$id?>" value="<?=$txt_d?>"></p>
<? } elseif($val['1'] == 'data2') {?>
<p><label for="p<?=$id?>"><?=$val['0']?><?=($val['3']>=1)?' <span>*</span>':''?></label>
<input style="width:30px" type="text" id="p<?=$id?>_1" name="p<?=$id?>_1" value="<?=$txt_d?>">
<input style="width:90px" type="text" id="p<?=$id?>_2" name="p<?=$id?>_2" value="<?=$txt_d?>">
<input style="width:60px" type="text" id="p<?=$id?>_3" name="p<?=$id?>_3" value="<?=$txt_d?>">
</p>
<? } elseif($val['1'] == 'password'){ ?>
<p><label for="p<?=$id?>"><?=$val['0']?><?=($val['3']>=1)?' <span class="required">*</span>':''?></label>
<input  type="password" id="p<?=$id?>" name="p<?=$id?>" value="<?=$txt_d?>"></p>
<?	} elseif($val['1'] == 'passport') {?>
<p><label for="p<?=$id?>"><?=$val['0']?><?=($val['3']==1)?' <span>*</span>':''?></label>
серия: <input style="width:50px" type="text" id="p<?=$id?>_1" name="p<?=$id?>_1" value="<?=$txt_d?>">
номер: <input style="width:90px" type="text" id="p<?=$id?>_2" name="p<?=$id?>_2" value="<?=$txt_d?>"><br>

<div style="padding-top:4px;">
дата выдачи: <input style="width:149px" type="text" id="p<?=$id?>_4" name="p<?=$id?>_3" value="<?=$txt_d?>">

</div>
<div style="padding-top:4px;">
<table class="col"><tr><td class="p0">кем выдан:&nbsp;</td><td class="p0"><textarea style="width:224px; height:60px;" id="p<?=$id?>_3" name="p<?=$id?>_3"><?=$txt_d?></textarea></td></tr></table><br>
</div>
</p>
<? } elseif($val['1'] == 'phone') {?>
<p><label for="p<?=$id?>"><?=$val['0']?><?=($val['3']>=1)?' <span>*</span>':''?></label>
<input style="width:150px" class="tel" type="tel" id="p<?=$id?>" name="p<?=$id?>" value="<?=$txt_d?>">
</p>
<? } elseif($val['1'] == 'data3') {?>
<tr><td<?=$style?> class="fb_id"><?=$val['0']?><?=($val['3']>=1)?' <span>*</span>':''?></td><?=$trtr?>
<td class="fb_input">
<select name="p<?=$id?>_1">
<?for($i = 1; $i <= 31; $i++ ){

	$chk = ($i == date('d')) ? ' selected="selected"' : '';
?>
<option <?=$chk?> value="<?=$i?>"><?=$i?></option>
<?}?>
</select>
<select name="p<?=$id?>_2">
<?
global $mes;
foreach($mes as $id2=>$t){
$chk = (($id2) == date('m')-1) ? ' selected="selected"' : '';
?>
<option <?=$chk?> value="<?=$t?>"><?=$t?></option>
<?}?>
</select>
<select name="p<?=$id?>_3">
<?for($i = date('Y'); $i <= date('Y')+5; $i++ ){
$chk = ($i == date('Y')) ? ' selected="selected"' : '';
?>
<option <?=$chk?> value="<?=$i?>"><?=$i?></option>
<?}?>
</select>

</td></tr>

<? } elseif($val['1'] == 'email') {?>
<p><label for="p<?=$id?>"><?=$val['0']?><?=($val['3']==1)?' <span>*</span>':''?></label>
<input type="email" id="p<?=$id?>" name="p<?=$id?>" value="<?=$txt_d?>"></p>
<? } elseif($val['1'] == 'file') {?>
<p><label for="p<?=$id?>"><?=$val['0']?></label>
<input type="file" id="p<?=$id?>" name="p<?=$id?>[]" value=""> <input class="nbtn" onclick="add_new()" type="button" value="+" /><div class="new_p"></div></p>
<script>
function add_new()
{
	if($('.new_p').length < 5)
	{
	$(".nbtn").remove();
	$('.new_p:last').after('<input type="file" id="p<?=$id?>" name="p<?=$id?>[]" value=""> <input  class="nbtn"  onclick="add_new()" type="button" value="+" /><div class="new_p"></div>').appendTo();
	}
}
</script>
<? } elseif($val['1'] == 'select') {?>
<p><label for="p<?=$id?>"><?=$val['0']?><?=($val['4']==1)?' <span>*</span>':''?></label>
<select id="p<?=$id?>" name="p<?=$id?>">
<?
foreach($val['3'] as $did=>$dval)
{
?>
<option value="<?=$dval?>"><?=$dval?></option>
<?}?>
</select></p>
<? } elseif($val['1'] == 'select_text') {?>
<p><label for="p<?=$id?>"><?=$val['0']?></label>
<select id="p<?=$id?>" name="p<?=$id?>">
<?
foreach($val['3'] as $did=>$dval)
{
?>
<option value="<?=$dval?>"><?=$dval?></option>
<?}?>
</select><br>
<textarea style="display:none; width:<?=$val['2']?>px" class="fxtxt" id="p<?=$id?>_1" name="p<?=$id?>_1"></textarea></p>
<script>
$("#p<?=$id?>").change(function(){
if($(this).val() == 'да')
	$("#p<?=$id?>_1").css({'display' : ''});
else
	$("#p<?=$id?>_1").css({'display' : 'none'});
})
</script>


<? } elseif($val['1'] == 'checkboxes') {?>
<tr><td<?=$style?> class="fb_id"><?=$val['0']?></td><?=$trtr?>
<td class="fb_input">
<?
foreach($val['3'] as $did=>$dval)
{
?>
<input name="p<?=$id?>[]" value="<?=$dval?>" type="checkbox" /> - <?=$dval?><br>
<?}?>
</td></tr>

<? } elseif($val['1'] == 'data') {?>
<tr><td<?=$style?> class="fb_id"><?=$val['0']?><?=($val['3']==1)?' <span>*</span>':''?></td><?=$trtr?>
<td class="fb_input"><input style="width:<?=$val['2']?>; margin:3px 0px;" type="text" id="p<?=$id?>" name="p<?=$id?>" value="<?=date('d/m/Y', time())?>">
<script>
$('#p<?=$id?>').datePicker();
</script>
</td></tr>
<? } elseif($val['1'] == 'checkbox') {?>
<p><label for="p<?=$id?>"><input type="checkbox" class="icheck" id="p<?=$id?>" name="p<?=$id?>" value="1" <?=($txt_d==1) ? 'checked' : '' ?>>&nbsp;<?=$val['0']?><?=($val['3']>=1)?' <span>*</span>':''?></label>
</p>
<? } elseif($val['1'] == 'capcha') {?>
<div class="form-capcha clearfix"><img class="floatleft" src="<?=SITE_URL?>kcaptcha/?<?php echo session_name()?>=<?php echo session_id()?>"><p><label for="p<?=$id?>"><?=$val['0']?><?=($val['3']>0)?' <span>*</span>':''?></label><input autocomplete="off" type="text" id="p<?=$id?>" name="p<?=$id?>" value=""></p>
</div>
<? } elseif($val['1'] == 'textarea') {?>
<p><label for="p<?=$id?>"><?=$val['0']?><?=($val['3']>=1)?' <span>*</span>':''?></label>
<textarea  class="fxtxt" id="p<?=$id?>" name="p<?=$id?>"><?=$txt_d?></textarea></p>
<? } 
 }
?>
<p><span>*</span> - поля обязательные для заполнения</p>
<p>Нажимая на кнопку Вы даете согласие на обработку ваших персональных данных. <u><a href="privacy_policy" target="_blank">(Политика конфиденциальности)</a></u></p>
</div>
<div class="form-action">
    <input class="floatleft" type="submit" value="<?=(!empty($btn_name)) ? $btn_name : 'Отправить' ?>">
</div>
<input type="hidden" name="posted" value="1">
</form>
<?

$rules = array();
$messages = array();
foreach($form_data as $id=>$val) {
	if(($val[1] == 'text' || $val[1] == 'capcha')&& $val[3] >= 1)
	{
		$rules[] = 'p'.$id." : {required : true, minlength: ".$val[3]."}";
		$messages[] = 'p'.$id.' : {required : "<span class=\"frm_err\">Заполните поле \"'.$val[0].'\"</span>", minlength : "<span class=\"frm_err\">Введите не менее, чем '.$val[3].' символа.</span>"}';
	}
	elseif($val[1] == 'textarea' && $val[3] >0)
	{
		$rules[] = 'p'.$id." : {required : true, minlength: $val[3]}";
		$messages[] = 'p'.$id.' : {required : "<span class=\"frm_err2\">Заполните поле \"'.$val[0].'\"</span>", minlength : "<span class=\"frm_err2\">Введите не менее, чем '.$val[3].' символа.</span>"}';
	}
	elseif($val[1] == 'phone' && $val[3] >0)
	{
		$rules[] = 'p'.$id." : {required : true, minlength: $val[3]}";
		$messages[] = 'p'.$id.' : {required : "<span class=\"frm_err2\">Заполните поле \"'.$val[0].'\"</span>", minlength : "<span class=\"frm_err2\">Введите не менее, чем '.$val[3].' символа.</span>"}';
	}
	elseif($val[1] == 'email' && $val[3] == 1)
	{
		$rules[] = 'p'.$id." : {required : true, email : true}";
		$messages[] = 'p'.$id.' : {required : "<span class=\"frm_err\">Заполните поле \"'.$val[0].'\"</span>", email : "<span class=\"frm_err\">Введите правильно ваш '.$val[0].'</span>"}';
	}
	elseif($val[1] == 'checkbox' && $val[3] == 1)
	{
		$rules[] = 'p'.$id." : {required : true}";
		$messages[] = 'p'.$id.' : {required : "<span class=\"frm_err\">'.$val[4].'</span>"}';
	}
	elseif($val[1] == 'password'){
		if (empty($fp)){
			$rules[] = 'p'.$id." : {required : true, minlength: ".$val[3]."}";
			$messages[] = 'p'.$id.' : {required : "<span class=\"frm_err\">Заполните поле \"'.$val[0].'\"</span>", minlength : "<span class=\"frm_err\">Введите не менее, чем '.$val[3].' символа.</span>"}';
			$fp='p'.$id;
		} else {
			$rules[] = 'p'.$id." : {required : true, minlength: ".$val[3].",equalTo: \"#{$fp}\"}";
			$messages[] = 'p'.$id.' : {required : "<span class=\"frm_err\">Заполните поле \"'.$val[0].'\"</span>", minlength : "<span class=\"frm_err\">Введите не менее, чем '.$val[3].' символа.</span>",equalTo:"<span class=\"frm_err\">Введите такой-же пароль, как выше.</span>"}';
		}
	}

}
?>
<script type="text/javascript">
jQuery().ready(function(){
jQuery("#frmFeedback").validate({
	errorPlacement: function(error, element) {
	// Append error within linked label
	$( element ).closest( "form" ).find( "label[for='" + element.attr( "id" ) + "']" ).append( error );},
    rules : {<?=implode(',', $rules)?>},
    messages : {<?=implode(',', $messages)?>}
});
$(".tel").mask("+7 (999) 999-9999");
});
</script>
</div></div>