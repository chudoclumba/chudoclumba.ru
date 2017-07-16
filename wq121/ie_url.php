<?php

if(!empty($_SERVER['QUERY_STRING']))
{
	$r = str_replace('_and_', '#', $_SERVER['QUERY_STRING']);

?>
	<script type="text/javascript">
	window.parent.location.href = "<?php echo $r; ?>";
	</script>
<?php

}