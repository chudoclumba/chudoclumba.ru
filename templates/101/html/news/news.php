<table class="tbl_arts">
 <tr>
  <td class="art_title">
   <table class="art_outer_ttl">
	<tr>
	 <td class="art_ttl">&nbsp;</td>
	 <td class="art_sends">Опубликовано: <?php echo date('d.m.y', $art['cdate']); ?></td>
	</tr>
   </table>	
  </td>
 </tr>
 <tr>
  <td class="art_contents">
<? if($this->show_photo() && !empty($art['photo'])) { ?>
<a id="flink" class="highslide " onclick="return hs.expand(this)" href="<?=SITE_URL.$art['photo']?>">
<img style="float:left; margin:5px 5px 5px 0px" alt="" src="thumb.php?id=<?=$art['photo']?>&x=160&y=160" /></a>
<? } ?>
  <?php echo $art['txt']; ?></td>
 </tr>
 <tr>
  <td class="art_back"><a href="javascript:history.back(1)">вернуться к списку</a></td>
 </tr>
</table>
