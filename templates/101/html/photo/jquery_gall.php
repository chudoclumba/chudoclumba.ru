<script type="text/javascript" src="jscripts/jcarousel/jquery.jcarousel.pack.js"></script>
<?
		$photo_data = get_elements($data['photo'],'articul');
		$a = 0;

		$fotki = array();
		
		foreach($photo_data as $photo_info)
		{
			$val = get_elements($photo_info,'param');
			$fotki[] = $val;
		}
?>
<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel({
        vertical: true,
        scroll: 2
    });
});

</script>

<table align="center" class="gall">
 <tr>
  <td class="photo_img_osn p0 top">
   <div style="position:relative; padding-top:22px; text-align:center;">
    <div id="foto_text" style="position:absolute; left:0px; bottom:20px; width:350px;"><?=$fotki['0']['1']?></div>
	<img id="main_foto" src="thumb.php?id=<?=$fotki['0']['0']?>&x=350&y=525" /></div></td>
  <td class="photo_right p0 top">
  <ul id="mycarousel" class="jcarousel jcarousel-skin-tango">
 
   <?
   foreach($fotki as $id=>$val)
   {
   ?>
	<li><img onclick="$('#main_foto').css({'src' : 'thumb.php?id=<?=$val['0']?>&x=350&y=525'}); $('#foto_text').html('<?=$val['1']?>')" alt="<?=$val['1']?>" src="thumb.php?id=<?=$val['0']?>&x=100&y=138" /></li>
	<?
   }
   
   ?>
  </ul>
  </td>
 </tr>
</table>