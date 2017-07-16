<div class="col-md-12 pb30">
<div class="area-title bdr">
<h2>Новости</h2>
</div>
<?

foreach($arts as $id=>$art){ ?>
<div class="news">
<div class="pub">Опубликовано: <?php echo date('d.m.y',$art['cdate']); ?></div>
<div class="clearfix"> <a href="<?php echo SITE_URL; ?>news/<?php echo $art['id']?>"><?php echo $art['t']; ?></a></div>
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 nopadding-left">
<a class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$art['photo']?>">
<img id="big_f" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$art['photo']?>&amp;x=250&amp;y=250&amp;crop"/>
</a></div>
<div class="col-lg-9 col-md-9 col-sm-6 col-xs-12 col-padd">
<?=strip_tags($art['txt'])?></div><div class="clearfix"></div></div>
<?php } ?>
<?=get_pages(array ('class' => 'prd_pages_bottom',
					'count_pages' => $c_pages, 
					'curr_page'=> $page, 
					'link' => 'news/all/'))?>
</div>						