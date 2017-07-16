<?
header('Content-type:text/xml; charset:utf-8');
?>
<?if(1 != 1) {?>
<<??>?xml version="1.0" encoding="utf-8"?<??>>
<gallery frameColor="0xFFFFFF" frameWidth="15" imagePadding="20" displayTime="6" enableRightClickOpen="false">
<?

$photos = get_elements($data['photo'],'articul');
foreach($photos as $id=>$val) {
$photo = get_elements($val,'param');
list($width,$height)=getimagesize(ROOT_DIR.$photo['0']);
?>
<image>
   <url><?=SITE_URL?><?=$photo['0']?></url>
   <caption><![CDATA[<?=iconv('windows-1251','utf-8',$photo['1'])?>]]></caption>
   <width><?=$width?></width>
   <height><?=$height?></height>
</image>
<? } ?>
</gallery>
<?} else {?>
<tiltviewergallery>
	<photos>
<?

$photos = get_elements($data['photo'],'articul');
foreach($photos as $id=>$val) {
$photo = get_elements($val,'param');
list($width,$height)=getimagesize(ROOT_DIR.$photo['0']);
?>
		<photo imageurl="<?=SITE_URL?><?=$photo['0']?>" linkurl="<?=SITE_URL?><?=$photo['0']?>">
			<title><?=iconv('windows-1251','utf-8',$photo['1'])?></title>
			<description><?=iconv('windows-1251','utf-8',$photo['1'])?></description>
		</photo>
<? } ?>
	</photos>
</tiltviewergallery>		
<? } ?>