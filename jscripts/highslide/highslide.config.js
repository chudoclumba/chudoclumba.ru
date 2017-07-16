/**
*	Site-specific configuration settings for Highslide JS
*/
hs.graphicsDir = '/jscripts/highslide/graphics/';
hs.showCredits = false;
hs.fadeInOut = true;
hs.easing = 'linearTween';
hs.outlineWhileAnimating = 0;
hs.minWidth = 50;
hs.minHeight = 50;
hs.blockRightClick = true;
hs.numberOfImagesToPreload = 0;
hs.captionEval = 'this.a.title';
hs.headingEval = 'this.thumb.alt';
// Add the slideshow controller
hs.addSlideshow({
	slideshowGroup: 'group1',
	interval: 5000,
	repeat: true,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: 0.6,
		position: 'top right',
		offsetX: -2,
		offsetY: -18,
		hideOnMouseOut: false
	},
	thumbstrip: {
		mode: 'float',
		position: 'rightpanel',
		relativeTo: 'image'
	}

});
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

// gallery config object
var config1 = {
	slideshowGroup: 'group1',
	numberPosition: 'heading',
	transitions: ['expand', 'crossfade']
};
