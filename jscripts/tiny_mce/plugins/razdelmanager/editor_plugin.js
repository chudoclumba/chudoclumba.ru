(function() {
	tinymce.PluginManager.requireLangPack('razdelmanager');
	tinymce.create('tinymce.plugins.razdelmanagerPlugin', {

		init : function(ed, url) {
			ed.addCommand('mcerazdelmanager', function() {
				ed.windowManager.open({
					file : url + '/dialog.php',
					width : 520 + ed.getLang('razdelmanager.delta_width', 0),
					height : 420 + ed.getLang('razdelmanager.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url
				});
			});
		}
	});

	tinymce.PluginManager.add('razdelmanager', tinymce.plugins.razdelmanagerPlugin);
})();
