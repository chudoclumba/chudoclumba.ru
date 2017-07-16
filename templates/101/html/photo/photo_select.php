<table>
 <tr>
  <td><?=$title?></td>
  <td></td>
 </tr>
 <tr>
  <td><img alt="" src="thumb.php?id=<?=$photo?>&x=400&y=400" /></td>
  <td class="top">
   <table class="w100 col">
    <tr>
<? foreach($photos as $id=>$value)
				{
					
					$photo = $this->db->get_rows("SELECT filename FROM ".TABLE_PRD_FOTO." WHERE product_id=".$value['id']."");
				
					$photo_i = (!empty($photo['0']['filename'])) ? '
					<a href="photo/'.$value['id'].'">
					<img alt="" src="thumb.php?id='.$photo['0']['filename'].'&x=100&y=100" /></a>' : '';
	?>
	<td class="photka_outer top">
	<table class="photka col" align="center">
	 <tr>
	  <td>
	<?=$photo_i?>
	  </td>
	 </tr>
	</table>  
	</td>
	<?		
	 if(($id) %2 == 1) echo  '</tr><tr>';
	}
	?>				
    </tr>
   </table>
  </td>
 </tr>
</table>