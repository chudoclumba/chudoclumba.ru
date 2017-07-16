<?
$res1=$this->db->get(TABLE_BANNERS,array('enabled'=>1,'page'=>$page,'place'=>$place));
$res2=$this->db->get(TABLE_BANNERS,array('enabled'=>1,'page'=>$page,'place'=>$place+1));
if (count($res1)>0 || count($res2)>0) {
	$link=(!empty($res1[0]['vlink']))?'<a href="'.$res1[0]['vlink'].'">':'';
	$linkend=(!empty($res1[0]['vlink']))?'</a>':'';
?>
<!--advertise area start-->
<div class="advertise-area mt10">
    <div class="col-md-6 col-sm-6 col-xs-12">
<?  if (count($res1)>0) {?>
        <div class="single-add vina-banner">
   		<?=$link?><img src="thumb.php?id=<?=$res1[0]['foto']?>&x=<?=$res1[0]['width']?>&y=<?=$res1[0]['height']?>&crop" alt=""><?=$linkend?>
        </div>
<?}?>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
<?  if (count($res2)>0) {
	$link=(!empty($res2[0]['vlink']))?'<a href="'.$res2[0]['vlink'].'">':'';
	$linkend=(!empty($res2[0]['vlink']))?'</a>':'';
	?>
        <div class="single-add vina-banner">
   		<?=$link?><img src="thumb.php?id=<?=$res2[0]['foto']?>&x=<?=$res2[0]['width']?>&y=<?=$res2[0]['height']?>&crop" alt=""><?=$linkend?>
        </div>
<?}?>
    </div>
</div>
<!--advertise area end-->
<?}