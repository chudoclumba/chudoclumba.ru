<?

		$photo_data = get_elements($data['photo'],'articul');
		$fotki = array();
		
		foreach($photo_data as $photo_info)
		{
			$valr = get_elements($photo_info,'param');
			$fotki[] = array('photo'=>$valr['0'], 'text'=>$valr['1']);
		}
		
?>
<?
$t = array();
foreach($fotki as $id2=>$val)
{
	$t[] = '["'.$val['photo'].'",640,480,3,0,"","#"]';
}
?>
<?
if(empty($_GET['type']))
{
?>
<div id="imCel6_00">
<div id="imCel6_00_Cont">
	<div id="imObj6_00">
<div id="imSSCont_0">
<div id="imSSBackg_0">
<img id="imSSImage_0" src="<?=$fotki['0']['photo']?>" onclick="imLink(0);" alt="" />
<div id="imSSDescr_0"></div>
</div>
</div>
<img src="jscripts/gallery/res/ss_left0.gif" alt="" /><img class="imssBtn" src="jscripts/gallery/res/ss_prev0.gif" onclick="imDoTrans(0,-1)" alt="" /><img class="imssBtn" src="jscripts/gallery/res/ss_play0.gif" id="imssPlay_0" onclick="imSSPlay(0,0,0)" alt="Play" /><img class="imssBtn" src="jscripts/gallery/res/ss_next0.gif" onclick="imDoTrans(0,1)" alt="" /><img src="jscripts/gallery/res/ss_right0.gif" alt="" />
<script type="text/javascript">
imSSLoad(0,[<?=implode(',', $t)?>]);
imDoTrans(0,0);
</script>
	</div>
</div>
</div>
<?
}

if(!empty($_GET['type']) && $_GET['type'] == 'full')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>FullScreen view</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta http-equiv="Content-Language" content="ru">
<meta name="Generator" content="Incomedia WebSite X5 v7.0.11 - www.websitex5.com" />
<meta http-equiv="ImageToolbar" content="False" />
<base href="<?php echo SITE_URL?>">
<script type="text/javascript" src="<?=SITE_URL?>jscripts/gallery/res/x5engine.js"></script>
<style type="text/css">
#imSSCont_0 {border: 2px solid #C0C0C0; width: 795px; margin: auto; }
#imSSBackg_0 {width: 795px; height: 596px; background-color: #DEFCC9; overflow: hidden; position: relative; }
#imSSImage_0 {position: absolute; top: 58px; left: 77px; }
#imSSDescr_0 {font: 11px Tahoma; color: #000000; width: 795px; overflow: hidden; position: absolute; top: 480px; left: 0; }
img.imssBtn {cursor: pointer; }

</style>
</head>
<body style="margin: 0; padding: 0; background-color: #fff; overflow: hidden; " >
<table cellpadding="0" cellspacing="0" style="width:100%; height:100%; border: none; text-align: center; "><tr><td>
<div id="imSSCont_0">
<div id="imSSBackg_0">
<img id="imSSImage_0" src="data/slideshow/fs_4_0_1.jpg" onclick="imLink(0);" alt="" />
<div id="imSSDescr_0"></div>
</div>
</div>
<img src="jscripts/gallery/res/ss_left0.gif" alt="" /><img class="imssBtn" src="jscripts/gallery/res/ss_close0.gif" onclick="javascript:window.close();" alt="Закрыть" /><img class="imssBtn" src="jscripts/gallery/res/ss_prev0.gif" onclick="imDoTrans(0,-1)" alt="Назад" /><img class="imssBtn" src="jscripts/gallery/res/ss_play0.gif" id="imssPlay_0" onclick="imSSPlay(0,0,0)" alt="Play" /><img class="imssBtn" src="jscripts/gallery/res/ss_next0.gif" onclick="imDoTrans(0,1)" alt="Вперёд" /><img src="jscripts/gallery/res/ss_right0.gif" alt="" />
<script type="text/javascript">
imSSLoad(0,[<?=implode(',', $t)?>]);

</script>
</td></tr></table>
</body>
</html>

<?
	exit;
}
?>