<div class=""><div class="msg_cart" id="flow_flt" style="display: none"><em>�����</em></div>
<form id="filter" action="" method="post">
<input type="hidden" name="catid" value="<?=$catid?>"/>
<?=$str?>
<button class="albutton alorange" name="ch_cart" type="submit" value="��"><span><span><span class="sync">���������</span></span></span></button>
</form>
<script>
$( "#filter" ).submit(function( event ) {
  submit_handler($( "#filter" ));
  alert( "Handler for .submit() called." );
  event.preventDefault();
});
</script>
</div>