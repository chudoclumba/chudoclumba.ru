<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if lt IE 7 ]> <html lang="en" class="ie6">    <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7">    <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8">    <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="ie9">    <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="no-js" lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($content['meta_title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></title>
    <meta name="description" content="<?php echo htmlspecialchars($content['meta_desc'],ENT_COMPAT | ENT_XHTML,'utf-8')?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php /*?><meta name="keywords" content="<?php echo htmlspecialchars($content['meta_keys'],ENT_COMPAT | ENT_XHTML,'utf-8')?>"/><?php */?>
    <meta name="yandex-verification" content="62daf02b4cfbd0f9" />
    <meta name="google-site-verification" content="sCtINS2WkGXK2EVzY8_zeVWjtGq5Eih8wTDNiYRnFKU" />
    <link rel=”canonical” href="https://chudoclumba.ru<?php echo $_SERVER['REQUEST_URI']?>"/>
	<? if(file_exists(ROOT_DIR.'favicon.ico')) echo '<link rel="shortcut icon" href="'.SITE_URL.'favicon.ico" type="image/x-icon"/>';?>
    <base href="<?php echo SITE_URL?>"/>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700,600,300,800&subset=cyrillic' rel='stylesheet' type='text/css'>
<? if (DEBUG_FT==1) {
	foreach($cs as $val){?>
		<link rel="stylesheet" type="text/css" media="screen" href="<?=$val?>"/>
<?	}
} else {?>
<link rel="stylesheet" type="text/css" media="screen" href="full_css<?=$sets['CSSver']?>.css"/>
<?}?>
	<link rel="stylesheet" type="text/css" media="print" href="<?=TEMP_FOLDER?>css/print.css"/>

<script src="js/vendor/modernizr-2.8.3.min.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87151949-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter16195645 = new Ya.Metrika({
                    id:16195645,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    trackHash:true,
                    ecommerce:"dataLayer"
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/16195645" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '150202312194096', {
em: 'insert_email_variable,'
});
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=150202312194096&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->

    
</head>
<body id="home-2">
<noscript><div style="border:1px solid red; font-size:22px; padding:20px; text-align:center;">В Вашем браузере отключен JavaScript! Корректная работа сайта невозможна!</div></noscript>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->

    <!-- header area start -->
 		<script type="text/javascript" src="js/vendor/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="jscripts/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="custom-slider/js/jquery.nivo.slider.js"></script>

 <div class="home-2-waraper">
 
 	<?echo Site::gI()->view('main/header', array('ebox' => $ebox))?>
	<?echo Site::gI()->view('main/top_menu', array('ebox' => $ebox))?>
    <!-- header area end -->
	<?if($_GET['module'] == 'site' && $_GET['id'] == '1') echo Site::gI()->view('main/slider')?>
		<!--script type="text/javascript" src="custom-slider/home.js"></script-->
	
    <!-- main area start -->
    <div class="main-area">
        <div class="container">
            <div class="row">
	
                <? if (!isset($content['left_menu']) || $content['left_menu']==true){
						
                echo Site::gI()->view('main/leftmenu',array('ishop'=>$ishop));?>

                <div class="col-md-9 col-sm-9 col-xs-12 col-padd">
                
                <? } else { ?>
                <div class="">
					
				<? }
                
                $tmp=preg_replace('/\(\s?(скоро|СКОРО|Скоро).*?\)/i','',$content['path']);
                  if (strpos($tmp,'<li')===false) $tmp="<li>$tmp</li>";
                  if (!($_GET['module'] == 'site' && $_GET['id'] == '1')) {?>
                <ol class="breadcrumb"> <li class="home"><a href="" title="Перейти к главной странице">Главная</a></li>
                <?=$tmp?>
                </ol>
                
                <?
                	echo Site::gI()->view('main/advertise',array('page'=>Site::gI()->url,'place'=>1));

                }?>
                    <div class="ambit-key" id="content">
 		<?if($_GET['module'] == 'site' && $_GET['id'] == '1') echo Site::gI()->view('main/purchase')?>
                       <!-- product section start -->
                         <?if($_GET['module'] == 'site' && $_GET['id'] == '1') echo Serv::gI()->gethits(9)?>
                        <?if($_GET['module'] == 'site' && $_GET['id'] == '1') echo Site::gI()->view('main/advertise',array('page'=>'main','place'=>1))?>
 
                        <?if($_GET['module'] == 'site' && $_GET['id'] == '1') echo Serv::gI()->newitems(80)?>
                        <?if($_GET['module'] == 'site' && $_GET['id'] == '1') echo Site::gI()->view('main/advertise',array('page'=>'main','place'=>3))?>
<?echo $content['html'];?>
<?	if (!($_GET['module'] == 'site' && $_GET['id'] == '1')) echo Site::gI()->view('main/advertise',array('page'=>Site::gI()->url,'place'=>3));
?>


 

                     </div>
                    <!--ambit-key-->
                <? if (!isset($content['left_menu']) || $content['left_menu']==true){?>
                </div>
                <? } else { ?>
                </div>
				<? }?>
                <!--col-md-9-->
            </div>
            <!--row-->
            </div>
        <!--container-->
    </div>
    <!-- main area end -->
<?echo Site::gI()->view('main/footer',array('ishop'=>$ishop))?>






<?if ($_SERVER['REMOTE_ADDR']==$sets['ofip'] || User::gI()->user_role>0) 
{?>
<div id="dbt" class="hidden-xs">Время обработки <?=number_format(microtime(true)-START_TIME,3,'.','')?> sec. Err_level <?=error_reporting()?> <?=((User::gI()->user_role>0)?$gmess:"")?></div>
<?
}
	echo (isset($_SESSION['deb_msg']))? '<div id="debug" onclick="ClrDbg();"><pre>'.$_SESSION['deb_msg'].'<pre></div>' : "";
?>
</div>

<? if (1==1) {
	foreach($js as $val){?>
		<script type="text/javascript" src="<?=$val?>"></script>
<?	}
} else {?>
	<script type="text/javascript" src="full_js<?=$sets['JSver']?>.js"></script>
<?}
	require('counters.php'); //Счетчики ?>
</body>
</html>