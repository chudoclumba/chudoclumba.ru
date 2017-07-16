<?
header('Content-type:text/html; charset:utf-8');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title><?=htmlspecialchars($data['pagetitle'],ENT_COMPAT | ENT_XHTML,'cp1251')?></title>
<meta name="keywords" content="<?php echo htmlspecialchars($data['metakey'],ENT_COMPAT | ENT_XHTML,'cp1251')?>">
<meta name="description" content="<?php echo htmlspecialchars($data['metadesc'],ENT_COMPAT | ENT_XHTML,'cp1251')?>">
<script type="text/javascript" src="<?=SITE_URL?>jscripts/swfobject.js"></script>
<style type="text/css">	
	/* hide from ie on mac \*/
	html {
		height: 100%;
		overflow: hidden;
	}	
	#flashcontent {
		height: 100%;
	}
	/* end hide */
	body {
		height: 100%;
		margin: 0;
		padding: 0;
		background-color: #181818;
		color:#ffffff;
		font-family:sans-serif;
		font-size:40;
	}	
	a {	
		color:#cccccc;
	}
</style>
</head>
<body>
	<div id="flashcontent">AutoViewer requires JavaScript and the Flash Player. <a href="http://www.macromedia.com/go/getflashplayer/">Get Flash here.</a> </div>	
	<script type="text/javascript">
		var fo = new SWFObject("<?=SITE_URL?>flash/TiltViewer.swf", "autoviewer", "100%", "100%", "8", "#181818");		
				
		//Optional Configuration
		fo.addVariable("langOpenImage", "Открыть в новом окне");
		fo.addVariable("langAbout", "О ");	
		fo.addVariable("useFlickr", "false");
		// XML GALLERY OPTIONS
		// To use local images defined in an XML document, use this block		
		fo.addVariable("useFlickr", "false");
		fo.addVariable("xmlURL", "gallery.xml");
		fo.addVariable("maxJPGSize","640");
		
		//GENERAL OPTIONS		
		fo.addVariable("useReloadButton", "false");
		fo.addVariable("columns", "3");
		fo.addVariable("rows", "3");
		
		fo.addVariable("showFlipButton", "false");
		//fo.addVariable("showLinkButton", "true");		
		//fo.addVariable("linkLabel", "View image info");
		//fo.addVariable("frameColor", "0xFF0000");
		//fo.addVariable("backColor", "0xDDDDDD");
		//fo.addVariable("bkgndInnerColor", "0xFF00FF");
		//fo.addVariable("bkgndOuterColor", "0x0000FF");				
		//fo.addVariable("langGoFull", "Go Fullscreen");
		//fo.addVariable("langExitFull", "Exit Fullscreen");
		//fo.addVariable("langAbout", "About");		
		
		fo.addVariable("xmlURL", "<?=SITE_URL?>photo/xml/<?=$data['id']?>");					
		
		fo.write("flashcontent");	
		
	</script>	
</body>
</html>