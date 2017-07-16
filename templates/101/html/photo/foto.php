<?php if(count($ft) > 0) { ?>
<div><?php echo $ft['0']['text']?></div>
<?}?>

<table width="100%"><tr>
<?
$t = 1;
foreach($fotos as $foto)
{
	if($foto['pid'] == '0')
	{
		$link = '<a href="foto/'.$foto['id'].'">';
	}
	else
	{
		$link = '<a class="highslide" onclick="return hs.expand(this)" href="/data/photo/'.$foto['foto'].'">';
	}
	?>
	<td style="text-align:center; padding:20px;"><?php echo $link?><img alt="" src="thumb.php?id=data/photo/<?php echo $foto['foto']?>&x=300" /><br>
	<?php echo $foto['name']?></a>
	 </td>

<?  if($t%3 == 0) { ?>
		</tr><tr>
<?  }
	$t++;
}

?>
</tr></table>