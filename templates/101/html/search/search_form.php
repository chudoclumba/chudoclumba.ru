<div class="search_form">
<form action="<?=SITE_URL?>search/1" method="post">
<input class="src_inp"  type="text" value="<?=$str?>" name="search_str" />
<script type="text/javascript">
if( $(".src_inp").val() == "")
{
	$(".src_inp").val("�����...");
}

$(".src_inp").click(function(){
	if($(this).val() == "�����...")
	{
		$(this).val("");
	}
});

$(".src_inp").bind("load blur",function(){
	if($(this).val() == "")
	{
		$(this).val("�����...");
	}
});
</script>
<? if($type == 1) { ?><br><? } ?>
<input class="src_sub" type="submit" name="search_sub" value="" />
</form>
</div>