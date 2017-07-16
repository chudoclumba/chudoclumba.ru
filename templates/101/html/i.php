<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if lt IE 7 ]> <html lang="en" class="ie6">    <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7">    <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8">    <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="ie9">    <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($content['meta_title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></title>
    <meta name="description" content="<?php echo htmlspecialchars($content['meta_desc'],ENT_COMPAT | ENT_XHTML,'utf-8')?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php /*?><meta name="keywords" content="<?php echo htmlspecialchars($content['meta_keys'],ENT_COMPAT | ENT_XHTML,'utf-8')?>"/><?php */?>
    <meta name="yandex-verification" content="62daf02b4cfbd0f9" />
    <? if(file_exists(ROOT_DIR.'favicon.ico')) echo '<link rel="shortcut icon" href="'.SITE_URL.'favicon.ico" type="image/x-icon"/>';?>
    <base href="<?php echo SITE_URL?>"/>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600,300,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" media="screen" href="templates/101/css/full_css<?=$sets['CSSver']?>.css"/>
    <link rel="stylesheet" type="text/css" media="print" href="<?=TEMP_FOLDER?>css/print.css"/>
    <!-- CSS  -->

    <!-- Bootstrap CSS
============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- owl.carousel CSS
============================================ -->
    <link rel="stylesheet" href="css/owl.carousel.css">

    <!-- owl.carousel CSS
============================================ -->
    <link rel="stylesheet" href="css/jquery-ui.css">

    <!-- owl.transitions CSS
============================================ -->
    <link rel="stylesheet" href="css/owl.transitions.css">

    <!-- font-awesome.min CSS
============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    
    <!-- icon-7-stroke font CSS
============================================ -->
    <link href="css/pe-icon-7-stroke.css" rel="stylesheet">    <!-- google font CSS
============================================ -->
    <link href="css/meanmenu.min.css" rel="stylesheet">

    <!-- animate CSS
============================================ -->
    <link rel="stylesheet" href="css/animate.css">

    <!-- Fancybox CSS
============================================ -->
    <link rel="stylesheet" href="css/fancybox/jquery.fancybox.css">

    <!-- nivo slider CSS
============================================ -->
    <link rel="stylesheet" href="custom-slider/css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="custom-slider/css/preview.css" type="text/css" media="screen" />

    <!-- chosen.min.css CSS
============================================ -->
    <link rel="stylesheet" href="css/chosen.min.css">

    <!-- normalize CSS
============================================ -->
    <link rel="stylesheet" href="css/normalize.css">

    <!-- main CSS
============================================ -->
    <link rel="stylesheet" href="css/main.css">

    <!-- style CSS
============================================ -->
    <link rel="stylesheet" href="style.css">

    <!-- responsive CSS
============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    
</head>
<body id="home-2">
<noscript><div style="border:1px solid red; font-size:22px; padding:20px; text-align:center;">В Вашем браузере отключен JavaScript! Корректная работа сайта невозможна!</div></noscript>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->
	<?echo Site::gI()->view('main/header', array('ebox' => $ebox))?>

    <!-- header area start -->
 <div class="home-2-waraper">
	<?echo Site::gI()->view('main/top_menu', array('ebox' => $ebox))?>
    <!-- header area end -->
	<?if($_GET['module'] == 'site' && $_GET['id'] == '1') Site::gI()->view('main/slider')?>
	
    <!-- main area start -->
    <div class="main-area">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12 nopadding-right">
                    <aside>
                        <div class="left-category-menu">
                            <div class="left-product-cat">
                                <div class="category-heading">
                                    <h2>category</h2>
                                </div>
                                <!-- category-menu-list start -->
                                <div class="category-menu-list">
                                    <ul>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/2.jpg" alt="" /><span>Equipments</span><i class="fa fa-angle-right"></i></a>
                                            <!-- cat-left mega menu start -->
                                            <div class="cat-left-drop-menu big-ldrop">
                                                <div class="cat-left-drop-menu-left sub-drop">
                                                    <a class="menu-item-heading" href="shop.html">Tops</a>
                                                    <ul>
                                                        <li><a href="shop.html">Blouse</a></li>
                                                        <li><a href="shop.html">T-shirts</a></li>
                                                        <li><a href="shop.html">T-shirts</a></li>
                                                    </ul>
                                                    <a class="menu-item-heading" href="shop.html">Dresses</a>
                                                    <ul>
                                                        <li><a href="shop.html">Summer Dresses</a></li>
                                                        <li><a href="shop.html">Casual Dresses</a></li>
                                                        <li><a href="shop.html">Enening Dresses</a></li>
                                                    </ul>
                                                </div>
                                                <div class="cat-left-drop-menu-left sub-drop">
                                                    <a class="menu-item-heading" href="shop.html">Tops</a>
                                                    <ul>
                                                        <li><a href="shop.html">Evening</a></li>
                                                        <li><a href="shop.html">Day</a></li>
                                                        <li><a href="shop.html">Sports</a></li>
                                                    </ul>
                                                    <a class="menu-item-heading" href="shop.html">Tops</a>
                                                    <ul>
                                                        <li><a href="shop.html">Evening</a></li>
                                                        <li><a href="shop.html">Day</a></li>
                                                        <li><a href="shop.html">Sports</a></li>
                                                    </ul>
                                                </div>
                                                <div class="cat-left-drop-menu-left sub-drop">
                                                    <a class="menu-item-heading" href="shop.html">Tops</a>
                                                    <ul>
                                                        <li><a href="shop.html">Blouse</a></li>
                                                        <li><a href="shop.html">T-shirts</a></li>
                                                        <li><a href="shop.html">Lungi</a></li>
                                                    </ul>
                                                    <a class="menu-item-heading" href="shop.html">Dresses</a>
                                                    <ul>
                                                        <li><a href="shop.html">Summer Dresses</a></li>
                                                        <li><a href="shop.html">Casual Dresses</a></li>
                                                        <li><a href="shop.html">Enening Dresses</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- cat-left mega menu end -->
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/3.jpg" alt="" /><span>Jewellery</span><i class="fa fa-angle-right"></i></a>
                                            <!-- cat-left mega menu start -->
                                            <div class="cat-left-drop-menu">
                                                <div class="cat-left-drop-menu-left">
                                                    <a class="menu-item-heading" href="shop.html">Tops</a>
                                                    <ul>
                                                        <li><a href="shop.html">Evening</a></li>
                                                        <li><a href="shop.html">Day</a></li>
                                                        <li><a href="shop.html">Sports</a></li>
                                                    </ul>
                                                </div>
                                                <div class="cat-left-drop-menu-left">
                                                    <a class="menu-item-heading" href="shop.html">Blouse</a>
                                                    <ul>
                                                        <li><a href="shop.html">Houseware</a></li>
                                                        <li><a href="shop.html">Headphone</a></li>
                                                        <li><a href="shop.html">Handbags</a></li>
                                                    </ul>
                                                </div>
                                                <div class="cat-left-drop-menu-left">
                                                    <a class="menu-item-heading" href="shop.html">Accsosorice</a>
                                                    <ul>
                                                        <li><a href="shop.html">Healht % Beuty</a></li>
                                                        <li><a href="shop.html">Home</a></li>
                                                        <li><a href="shop.html">Houseware</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- cat-left mega menu end -->
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/4.jpg" alt="" /><span>Watches</span><i class="fa fa-angle-right"></i></a>
                                            <!-- cat-left mega menu start -->
                                            <div class="cat-left-drop-menu">
                                                <div class="cat-left-drop-menu-left">
                                                    <a class="menu-item-heading" href="shop.html">Headphone</a>
                                                    <ul>
                                                        <li><a href="shop.html">Evening</a></li>
                                                        <li><a href="shop.html">Day</a></li>
                                                        <li><a href="shop.html">Sports</a></li>
                                                    </ul>
                                                </div>
                                                <div class="cat-left-drop-menu-left">
                                                    <a class="menu-item-heading" href="#">Blouse</a>
                                                    <ul>
                                                        <li><a href="shop.html">Houseware</a></li>
                                                        <li><a href="shop.html">Headphone</a></li>
                                                        <li><a href="shop.html">Handbags</a></li>
                                                    </ul>
                                                </div>
                                                <div class="cat-left-drop-menu-left">
                                                    <a class="menu-item-heading" href="shop.html">Sports</a>
                                                    <ul>
                                                        <li><a href="shop.html">Healht % Beuty</a></li>
                                                        <li><a href="shop.html">Home</a></li>
                                                        <li><a href="shop.html">Houseware</a></li>
                                                    </ul>
                                                </div>
                                                <div class="cat-left-drop-menu-left">
                                                    <a class="menu-item-heading" href="shop.html">Dresses</a>
                                                    <ul>
                                                        <li><a href="shop.html">Summer Dresses</a></li>
                                                        <li><a href="shop.html">Casual Dresses</a></li>
                                                        <li><a href="shop.html">Enening Dresses</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- cat-left mega menu end -->
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/5.jpg" alt="" /><span>Books</span><i class="fa fa-angle-right"></i></a>
                                            <!-- cat-left mega menu start -->
                                            <div class="cat-left-drop-menu subsm-drop">
                                                <div class="cat-left-drop-menu-left common0">
                                                    <a class="menu-item-heading" href="shop.html">Headphone</a>
                                                    <ul>
                                                        <li><a href="shop.html">Evening</a></li>
                                                        <li><a href="shop.html">Day</a></li>
                                                        <li><a href="shop.html">Sports</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- cat-left mega menu end -->
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/6.jpg" alt="" /><span>Sports</span><i class="fa fa-angle-right"></i></a>
                                            <!-- cat-left mega menu start -->
                                            <div class="cat-left-drop-menu subsm-drop">
                                                <div class="cat-left-drop-menu-left common0">
                                                    <a class="menu-item-heading" href="shop.html">Jeans</a>
                                                    <ul>
                                                        <li><a href="shop.html">Evening</a></li>
                                                        <li><a href="shop.html">Day</a></li>
                                                        <li><a href="shop.html">Sports</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- cat-left mega menu end -->
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/7.jpg" alt="" /><span>Gifts</span></a>
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/8.jpg" alt="" /><span>Health</span></a>
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/9.jpg" alt="" /><span>Medical</span></a>
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/10.jpg" alt="" /><span>Electronics</span></a>
                                        </li>
                                        <li>
                                            <a href="shop.html"><img src="img/catg-side/1.jpg" alt="" /><span>Accessories</span></a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- category-menu-list end -->
                            </div>
                        </div>
                    </aside>
                    <!--aside 1 end-->
                    <aside>
                        <div class="tag-area">
                            <div class="area-title">
                                <h3>Popular Tags</h3>
                            </div>
                            <ul class="aside-padd">
                                <li> <a href="#">html5</a></li>
                                <li> <a href="#">css3</a></li>
                                <li> <a href="#">Joomla</a></li>
                                <li> <a href="#">Jquery</a></li>
                                <li> <a href="#">css</a></li>
                                <li> <a href="#">Content</a></li>
                                <li> <a href="#">clothing</a></li>
                                <li> <a href="#">shoes</a></li>
                                <li> <a href="#">gifts</a></li>
                                <li> <a href="#">electronics</a></li>
                            </ul>
                        </div>
                    </aside>
                    <!--aside 2 end-->
                    <aside>
                        <div class="newsletter-area">
                            <div class="area-title">
                                <h3>Newsletter</h3>
                            </div>
                            <div class="aside-padd">
                                <div class="vina-newsletter">
                                    <form method="post" action="#">
                                        <div class="input-box">
                                            <label>Sign Up for Our Newsletter:</label>
                                            <input type="email" placeholder="Email" name="email">
                                        </div>
                                        <div class="input-box">
                                            <input type="submit" class="submit-btn" name="submit" value="Subscribe">
                                        </div>
                                    </form>
                                </div>
                                <div class="web-links">
                                    <ul>
                                        <li><a href="#" class="rss"><i class="fa fa-rss"></i>
                        </a></li>
                                        <li><a href="#" class="ldin"><i class="fa fa-linkedin"></i>
                        </a></li>
                                        <li><a href="#" class="face"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#" class="google"><i class="fa fa-google-plus"></i>
                        </a></li>
                                        <li><a href="#" class="twitter"><i class="fa fa-twitter"></i>
                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </aside>
                    <!--aside 3 end-->
                 </div>
                <!--col-md-3-->

                <div class="col-md-9 col-sm-9 col-xs-12 nopadding-left">
                    <div class="ambit-key">
                        <!--purchase progress area start-->
                        <div class="purchase-Progress-area featur-padd">
                            <div class="area-title bdr mt25">
                                <h2>Заказать просто!</h2>
                            </div>
                            <div class="progress-steps">
                                <ul class="mt10">
                                    <li>
                                        <h4>Шаг 1</h4>
                                        <div class="progress-img">
                                            <img src="img/step1.png" alt="">
                                        </div>
                                        <p>Выбираете товар</p>
                                    </li>
                                    <li>
                                        <h4>Шаг 2</h4>
                                        <div class="progress-img">
                                            <img src="img/step2.png" alt="">
                                        </div>
                                        <p>Добавляете в корзину</p>
                                    </li>
                                    <li>
                                        <h4>Шаг 3</h4>
                                        <div class="progress-img">
                                            <img src="img/step3.png" alt="">
                                        </div>
                                        <p>Подтверждаете заказ</p>
                                    </li>
                                    <li>
                                        <h4>Шаг 4</h4>
                                        <div class="progress-img">
                                            <img src="img/step4.png" alt="">
                                        </div>
                                        <p>Оплачиваете</p>
                                    </li>
                                    <li>
                                        <h4>Шаг 5</h4>
                                        <div class="progress-img">
                                            <img src="img/final_step.png" alt="">
                                        </div>
                                        <p>Ждете 3-5 лет..</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--purchase progress area end-->
                        <!-- product section start -->
                        
                        <?=Serv::gI()->gethits(9)?>
 
                        <!--advertise area start-->
                        <div class="advertise-area mt10">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="single-add vina-banner">
                           		<a href="#"><img src="thumb.php?id=data/main/ban3.jpg&x=409&y=176&crop" alt=""></a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="single-add vina-banner">
                           		<a href="#"><img src="thumb.php?id=data/main/ban4.jpg&x=409&y=176&crop" alt=""></a>
                                </div>
                            </div>
                        </div>
                        <!--advertise area end-->

                        <!--====== best offered area start===== -->
                        <?=Serv::gI()->newitems(8)?>
                        <!-- best offered area end-->



 

                     </div>
                    <!--ambit-key-->
                </div>
                <!--col-md-9-->
            </div>
            <!--row-->
            </div>
        <!--container-->
    </div>
    <!-- main area end -->








<div id="loader" style="display:none"></div>
<table class="main col w100 h100">


<tr><td class="line3 p0 top">
<table class="t_tbl3 w100 h100 col">
<tr><td class="mc1 p0 top">
<div class="mc3fn">
<a href="<?=SITE_URL?>ishop/advs" style="margin-top:30px" >Расширенный поиск</a>
</div>
<?=
!empty($show['Меню из кнопок']) ? ((class_exists('Ishop')) ? '<div class="lm_cat">'.$ishop->left_menuui(0, 0, '_btn').'</div>' : '').
		  ((class_exists('Sitemenu')) ? '<div class="lm_site">'.$sitemenu->left_menuui(0, 0, '_btn').'</div>' : '') : '';?></td>
<td class="mc2 p0 top"><div id="search_h" <?=((isset($_POST['bsearch_str']) && !empty($_POST['bsearch_str']))?'style="height:23px"':'style="height:0px"')?>>
<form action="search/1" method="post" id="sch_f">Поиск <input id="main_search" type="text" class="fbinp" style="width:<?=((isset($_POST['bsearch_str']) && !empty($_POST['bsearch_str']))?'500':'150')?>px" value="<?=htmlspecialchars((isset($_POST['bsearch_str']) && !empty($_POST['bsearch_str'])) ? trim($_POST['bsearch_str']) : ((isset($_SESSION['bsearch_str']))?$_SESSION['bsearch_str']:''),ENT_COMPAT | ENT_XHTML,'cp1251')?>" name="bsearch_str"/><span class="icon clear" onclick="ClrSch();" <?=((isset($_POST['bsearch_str']) && !empty($_POST['bsearch_str']))?'':'style="display:none"')?>></span><span class="icon next" onclick="$('#sch_f').submit();" <?=((isset($_POST['bsearch_str']) && !empty($_POST['bsearch_str']))?'':'style="display:none"')?>>Искать</span></form>	
	
</div><?echo $site->view('i_mc2_outer1', array('path'=> cut($content['path'],2500), 'content'=> $content['html']));?></td>
<td class="mc3 p0 top"><?
if(!empty($show['Регистрация'])) {?>
<div class="mc3_block" id="mc3reg"><?=$reg->form()?></div>
<?
$sk = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE skidka_day = 1 && enabled = 1 && visible = 1 LIMIT 1");
if (count($sk)>0) {
$scc = '<div class="sk_ttl"><a href="ishop/product/'.$sk['0']['id'].'">'.$sk['0']['title'].'</a></div>
<div class="sk_img"><a href="ishop/product/'.$sk['0']['id'].'"><img alt="'.$sk['0']['title'].'" src="thumb.php?id='.$sk['0']['foto'].'&x='.$ishop->image_width.'&y='.$ishop->image_height.'" /></a></div>
<div class="sk_pr">Старая цена: '.$ishop->s_price($sk['0']['tsena']).'<br>Новая цена: '.$ishop->s_price($sk['0']['tsena'], $sk['0']['skidka']).'</div>';
if ($sk['0']['id']>0) { echo $site->view('block', array('title' => 'Скидка дня', 'text' => $scc));}};
if ($sets['mod_hit']){
$scc='<div class="mc3_block tcen"><div class="h2" onclick="UpdateHit();">Хит продаж</div><div id="right_hit" '.(($sets['mod_hit_an'])?'an="1">':'>');
if (isset($_SESSION['hitblock']) && $sets['mod_hit_an']) $sk=$db->get(TABLE_PRODUCTS,$_SESSION['hitblock']);
else {
	$sk = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE hit = 1 && enabled = 1 LIMIT 1");
	if (count($sk)>0) $sk=$sk[0];
}
if (isset($sk['id'])) $scc.=$site->view('ishop/hitblock', array('row' => $sk));
$scc.='</div></div>';
echo $scc;
}
if(!empty($show['Подписка'])) {?>
<div class="mc3_block tcen"><?=$podpiska->form()?></div><? } }
if(class_exists('Filter')) echo '<div class="mc3_block tcen">'.Filter::gI()->form(0).'</div>';?>
<div class="mc3_block p0 tcen" style="padding-left: 8px"><div id="vk_groups"></div></div>
<? if ($_GET['module'] == 'site' && $_GET['id'] == '1' && $sets['show_baner']){?>
<div class="mc3_block p0 tcen"><a href="http://vosaduli.ru" target="_blank"><img src="<?=TEMP_FOLDER?>images/banner01.jpg" alt="vosaduli.ru" /></a></div>
<?}?>
<!-- VK Widget -->
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 0, width: "200", height: "250", color1: 'EDF9C7', color2: 'e51a4b', color3: '95C12B'}, 56610454);
</script>
</td>
	  <? //echo $site->view('i_mc3', array('text'=>$sitemenu->get_html(31))); ?>
</tr></table></td></tr>
<? if(1!=1) { ?>
  <tr>
   <td class="line2 p0"><?=$sitemenu->top_menu()?></td>
  </tr>
<? } ?>
<tr><td class="line4 p0"><div style="position: relative;">

<?if(!empty($show['Текст в подвале'])) { //<table class="tbl_4 w100 col"><tr><td class="f1 p0">&nbsp;</td><td class="f3 p0"></td></tr></table>
 echo Site::gI()->view('main/i_abouts', array('ebox' => $ebox));
  }
require('counters.php'); //Счетчики ?>
<div class="chkc">Copyright &copy; 2013-2015 Компания Чудо-Клумба</div>
<div class="map"><a href="<?SITE_URL?>map/view/all">Карта сайта</a>
</div></div></td></tr></table>
<?
if(!empty($show['Плавающийй телефон'])) {?>
<div id="divStayTopLeft" style="position:absolute"><table class="col fly_phone"><tr><td class="p0 phone"><?=$ebox['phone']?></td></tr></table></div>
<script type="text/javascript" src="jscripts/fly_phone.js"></script>
<?}?>
<? //echo Site::gI()->view('music'); //Музыка на сайте ?>
<?if(!empty($show['Защита'])) require('html_protect.php'); //Защита от копирования?>
<? if ($_SERVER['REMOTE_ADDR']==$sets['ofip'] || User::gI()->user_role>0) {?>
<div id="dbt">Время обработки <?=number_format(microtime(true)-START_TIME,3,'.','')?> sec. Err_level <?=error_reporting()?> <?=((User::gI()->user_role>0)?$gmess:"")?></div>
<?}
	echo (isset($_SESSION['deb_msg']))? '<div id="debug" onclick="ClrDbg();"><pre>'.$_SESSION['deb_msg'].'<pre></div>' : "";
?>
</div>
    <!-- JS -->

    <!-- jquery-1.11.3.min js
============================================ -->
    <script src="js/vendor/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/full_js<?=$sets['JSver']?>.js"></script>

    <!-- bootstrap js
============================================ -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Nivo slider js
============================================ -->
    <script src="custom-slider/js/jquery.nivo.slider.js" type="text/javascript"></script>
    <script src="custom-slider/home.js" type="text/javascript"></script>

    <!-- mixit up js
============================================ -->
    <script src="js/jquery.mixitup.min.js"></script>

    <!-- Fancybox up js
============================================ -->
    <script src="js/fancybox/jquery.fancybox.pack.js"></script>

    <!-- Price Slider js
============================================ -->
    <script src="js/jquery-price-slider.js"></script>

    <!-- owl.carousel.min js
============================================ -->
    <script src="js/owl.carousel.min.js"></script>

    <!-- counterUp
============================================ -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>

    <!-- Scroll up js
============================================ -->
    <script src="js/jquery.scrollUp.js"></script>

    <!-- elevator zoom js
============================================ -->
    <script src="js/jquery.elevateZoom-3.0.8.min.js"></script>

    <!-- mean Menu
============================================ -->
    <script src="js/jquery.meanmenu.js"></script>

    <!-- wow js
============================================ -->
    <script src="js/wow.js"></script>

    <!-- chosen.jquery.min.js
============================================ -->
    <script src="js/chosen.jquery.min.js"></script>

    <!-- plugins js
============================================ -->
    <script src="js/plugins.js"></script>

    <!-- main js
============================================ -->
    <script src="js/main.js"></script>

</body>
</html>