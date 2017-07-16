<?php
$module['path'] = 'gal';
ob_start();
?>
<script type="text/javascript">
hs.graphicsDir = '/highslide/graphics/';
hs.showCredits = false;
hs.creditsPosition = 'bottom left';
hs.outlineType = 'custom';
hs.fadeInOut = true;
hs.align = 'center';
//hs.useBox = true;
//hs.width = 600;
//hs.height = 400;
hs.blockRightClick = true;
hs.numberOfImagesToPreload = 0;
hs.captionEval = 'this.a.title';
hs.easing = 'linearTween';
hs.outlineWhileAnimating = 0;
hs.minWidth = 50;
hs.minHeight = 50;
	numberPosition= 'heading';
	transitions= ['expand', 'crossfade'];

// Add the slideshow controller


// Russian language strings
hs.lang = {
	cssDirection: 'ltr',
	loadingText: 'Загружается...',
	loadingTitle: 'Нажмите для отмены',
	focusTitle: 'Нажмите чтобы поместить на передний план',
	fullExpandTitle: 'Развернуть до оригинального размера',
	creditsText: 'Использует <i>Highslide JS</i>',
	creditsTitle: 'Перейти на домашнюю страницу Highslide JS',
	previousText: 'Предыдущее',
	nextText: 'Следующее',
	moveText: 'Переместить',
	closeText: 'Закрыть',
	closeTitle: 'Закрыть (esc)',
	resizeTitle: 'Изменить размер',
	playText: 'Слайдшоу',
	playTitle: 'Начать слайдшоу (пробел)',
	pauseText: 'Пауза',
	pauseTitle: 'Приостановить слайдшоу (пробел)',
	previousTitle: 'Предыдущее (стрелка влево)',
	nextTitle: 'Следующее (стрелка вправо)',
	moveTitle: 'Переместить',
	fullExpandText: 'Оригинальный размер',
	number: 'Изображение %1 из %2',
	restoreTitle: 'Нажмите чтобы закрыть изображение, нажмите и перетащите для изменения местоположения. Для просмотра изображений используйте стрелки.'
};

</script>
<div>
	<a href="<?=SITE_URL.'data/Crossover.jpg'?>" class="highslide" onclick="return hs.expand(this)"
			style="float:right; margin: 0 0 10px 15px">
		<img src="<?=SITE_URL.'thumb.php?id=data/Crossover.jpg&x=100&y=100'?>"  alt=""
			title="Heading from the thumbnail's title attribute"/>
		<span>Enlarge picture</span>
	</a>
</div>
<?
$module['html'] = ob_get_contents();
ob_end_clean();
$out='';
$out=Getgallery1('gal1',200);
$out.=Getgallery1('gal2',200);

$module['html'].=$out;


function Getgallery1($name,$wi=200){
	$dirnm='data/gallery/'.$name;
	$cdir=scandir('../'.$dirnm);
	$dirnm.='/';
	if (false==$cdir) return '<!--error '.$dirnm.'-->';
	$out='<script type="text/javascript">
	hs.addSlideshow({slideshowGroup: \''.$name.'\',interval: 5000,repeat: true,useControls: true,fixedControls: false,
	overlayOptions: {opacity: 0.75,	position: \'top right\',offsetX: 190,offsetY: -60,hideOnMouseOut: false},
	thumbstrip: {mode: \'float\',position: \'rightpanel\',relativeTo: \'image\'}});
	'.$name.' = {slideshowGroup: \''.$name.'\',	thumbnailId: \''.$name.'\',	numberPosition: \'heading\',transitions: [\'expand\', \'crossfade\'],
	useBox : true,width:800,height:600};</script>';
	$out.='<div class="gallery"><div class="highslide-gallery">' ;
	$cnt=0;
	foreach($cdir as $key => $val){
		if ($val!=='.' && $val!=='..') {
			if ($cnt==1) $out.='<div class="hidden-container">';
			$out.='<a '.(($cnt==0) ? 'id="'.$name.'" ':'').'title="'.str_replace('.jpg','', iconv("utf-8", "cp1251",$val)).'" href="'.SITE_URL.htmlspecialchars($dirnm.iconv("utf-8", "cp1251",$val),ENT_COMPAT | ENT_XHTML,'cp1251').'" class="highslide" onclick="return hs.expand(this,'.$name.')">
			<img src="'.SITE_URL.'thumb.php?id='.htmlspecialchars($dirnm.$val,ENT_COMPAT | ENT_XHTML,'cp1251').'&x='.$wi.'&y='.$wi.'"  alt="Highslide JS"/></a>';
			$cnt++;
		}
	}
	$out.='</div></div></div>';
	return $out;
}
