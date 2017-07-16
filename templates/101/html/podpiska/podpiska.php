<div class="podpiska_form">
<div class="h2">Подписка на новости</div>
<form name="podpiska_form" action="<?=SITE_URL?>podpiska/add" method="post">
&nbsp;&nbsp;&nbsp;E-mail: <input class="fbinp" name="email" type="text" value="" style="width:140px"/>
</form>
<a class="pdp_sub" name="add_email" href="">Подписаться</a>
<script type="text/javascript">
$('a[name="add_email"]').click(function(){var str = $("form[name='podpiska_form']").serialize();$.post($("form[name='podpiska_form']").attr('action'), str + '&add_email=1', function(data) {alert(data);});return false;});
</script>
</div>