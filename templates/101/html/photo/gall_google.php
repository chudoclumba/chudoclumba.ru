<table class="photo_all col w100"><tr>
<?

 function encrypt( $string )  
 {  
   $key = 'fvuwe';  
   $result = '';  
   for( $i = 1; $i <= strlen( $string ); $i++ )  
   {  
     $char = substr( $string, $i - 1, 1 );  
     $keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1 );  
     $char = chr( ord( $char ) + ord( $keychar ) );  
     $result .= $char;  
   }  
   return $result;  
 }  
    
 function decrypt( $string )  
 {  
   $key = 'fvuwe';  
   $result = '';  
   for( $i = 1; $i <= strlen( $string ); $i++ )  
   {  
     $char = substr( $string, $i - 1, 1 );  
     $keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1);  
     $char = chr( ord( $char ) - ord( $keychar ) );  
     $result .= $char;  
   }  
   return $result;  
}


foreach($data as $id=>$val)
{
	$photo_data = get_elements($val['photo'],'articul');
	$fotki = array();
	
	foreach($photo_data as $photo_info)
	{
		$valr = get_elements($photo_info,'param');
		$fotki[] = array('photo'=>$valr['0'], 'text'=>$valr['1']);
	}

	$ft = count($fotki);
	
?>
<td class="photo_r">
 <div class="photo_a">
 <div class="photo_a2"><a href="photo/<?=$val['id']?>"><img src="<?=SITE_URL?>thumb2.php?id=<?=urlencode(encrypt($fotki['0']['photo']))?>&x=180&y=180"  alt="" /></a></div>
</div> 
 <p class="gphoto-album-cover-title"><a class="gphoto-album-cover-link" href="photo/<?=$val['id']?>"><?=$val['title']?></a></p>
 </td>
<?
if(($id+1)%3 == 0) echo '</tr><tr>';
}

if(count($data) == 0)
{

?>
</tr></table>

<table class="photo_all col w100"><tr>
<?
if(!empty($data2['photo']))
{
$photo_data = get_elements($data2['photo'],'articul');
$fotki = array();

foreach($photo_data as $photo_info)
{
	$valr = get_elements($photo_info,'param');
	$fotki[] = array('photo'=>$valr['0'], 'text'=>$valr['1']);
}

$ft = count($fotki);

foreach($fotki as $id=>$val)
{
?>
<td class="photo_r">
 <div class="goog-icon-list-icon-img-div">
 <a onclick="return hs.expand(this)" class="highslide goog-icon-list-icon-link" href="thumb2.php?id=<?=urlencode(encrypt($val['photo']))?>&x=800&y=800"><img src="<?=SITE_URL?>thumb2.php?id=<?=urlencode(encrypt($val['photo']))?>&x=180&y=180"  alt="" /></a><br><?=$val['text']?></div>
 </td>
<?
if(($id+1)%3 == 0) echo '</tr><tr>';
}

}
}
?>
</tr></table>