<h3>
<?
	if($value['1'] == DEF_MODULE && $row['id']==DEF_ID)
	{
		$link = SITE_URL;
	}
	else
	{
		$link = SITE_URL.$value['1'].'/'.$row['id'];
	}
?>
 <a href="<?=$link?>"><?=$row[$value['2']]?></a>
</h3>
<div>
<?
$str=''.strip_tags($row[$value['3']]);
$str=mb_substr ($str,0,500,'utf8');
echo $str;

?>...<br/>
 <cite><?=$link?></cite>
</div>