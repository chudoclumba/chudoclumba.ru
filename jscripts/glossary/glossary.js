/**
 * @author Stepan Reznikov (stepan@design.ru)
 * @copyright Art.Lebedev Studio (http://www.artlebedev.ru)
 */

$(function () {

	/**
	 * Кеш для сохранения ранее запрошенных терминов
	 */
	var cache = {};

	// Добавляем элементы для вывода контента термина
	$(document.body).append('<div id="glossary_definition"><div class="close clickable"></div><div class="content"></div>');

	var block = $('#glossary_definition');
	var close = block.find('.close');
	var content = block.find('.content');

	/**
	 * Загружает контент термина
	 * @param {String} url Адрес откуда загружать контент
	 */
	function loadContent(url) {
		// Если есть в кеше, то берем из кеша
		if (cache[url]) {
			content.html(cache[url]);
			show();
		}
		// Если в кеше нет, то делаем Ajax-запрос
		else {
			content.load(url + ' .item', function () {
				show();
				cache[url] = $(this).html();
			});
		}
	}

	function show() {
		block.fadeIn('fast');
	}

	function hide() {
		block.fadeOut('fast');
	}

	$('.glossary_item').click(function (evt) {
		loadContent($(this).attr('href'));
		evt.preventDefault();
	});

	close.click(function (evt) {
		hide();
	});

});
