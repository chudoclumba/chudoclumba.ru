<table class="w100 col">
 <tr>
<? foreach($photos as $id=>$value) { 
	
	$photo = $this->db->get_rows("SELECT filename FROM ".TABLE_PRD_FOTO." WHERE product_id=".$value['id']."");

	$photo_i = (!empty($photo['0']['filename'])) ? '
	<a href="photo/'.$value['id'].'">
	<img alt="" src="thumb.php?id='.$photo['0']['filename'].'&x=100&y=100" /></a>' : '';
?>
<td class="photka_outer">
	
	<table class="photka col" align="center">
	 <tr>
	  <td>
	<?=$photo_i?>
	  </td>
	 </tr>
	</table>  
	</td>
<?	
	if(($id-1) %3 == 1) echo '</tr><tr>';
}
?>
</tr></table>