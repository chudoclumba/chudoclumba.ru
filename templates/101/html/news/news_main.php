<div class="newsmain"><div class="h2"><a href="<?=SITE_URL?>news/page/1">Наши новости</a></div>
<?foreach ($news as $id=>$res){?>

<div class="news_layout" onclick="location.href='<?=SITE_URL.'news/'.$res['id']?>'">
  <div class="news_foto">
  <? if($this->show_photo() && !empty($res['photo'])){?>
  <img alt="" src="thumb.php?id=<?=$res['photo']?>&x=65&y=65&crop" />	
<? } ?>
  </div>
  <div class="news_content">
  <div class="news_date"><?=htmlspecialchars(date("d.m.Y",$res['cdate']),ENT_COMPAT | ENT_XHTML,'cp1251')?>&nbsp;<a href="<?=SITE_URL.'news/'.$res['id'].'">'.htmlspecialchars(str_replace('&nbsp;',' ',$res['t']),ENT_COMPAT | ENT_XHTML,'cp1251')?></a></div>
  <div class="news_text"><?=substr(Strip_Tags(Trim(str_replace('&nbsp;',' ',$res['txt']))),0,$width)?>...</div>
  <div class="news_lnk"><?=$this->news_descr?></div>
  </div>
  <div style="clear:left;"></div>
</div>
<?}?>
</div>
