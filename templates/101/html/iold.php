<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" >
<<?='h'.'t'.'m'.'l'?>>
<<?='h'.'e'.'a'.'d'?>>
<meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251">
<title><?php echo htmlspecialchars($content['meta_title'])?></title>
<meta name="keywords" content="<?php echo htmlspecialchars($content['meta_keys'])?>">
<meta name="description" content="<?php echo htmlspecialchars($content['meta_desc'])?>">
<meta name='yandex-verification' content='6136d2fa382c8653' />
<?php echo $ebox['yandex_meta']?>
<? if(file_exists(ROOT_DIR.'favicon.ico')) echo '<link rel="shortcut icon" href="'.SITE_URL.'favicon.ico" type="image/x-icon" />';?>
<base href="<?php echo SITE_URL?>">
<link rel="stylesheet" type="text/css" media="print" href="<?=TEMP_FOLDER?>css/print.css">
<?require('css.php')?>
<?require('javascripts.php')?>
</<?='h'.'e'.'a'.'d'?>>
<<?='b'.'o'.'d'.'y'?> class="p0 m0"<? if (!empty($show['Защита'])) { ?> id="noselect"<?}?>>
<?if($_SERVER['REQUEST_URI'] == '/') {?>
<div class="p1"><img alt="" src="<?=TEMP_FOLDER?>images/p1.png" usemap="#Map" /><br><br><a href="<?=SITE_URL?>map/view/all">Карта сайта</a>

<div style="position:absolute; left:-9000px;">
<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter16195645 = new Ya.Metrika({id:16195645, enableAll: true, webvisor:true}); } catch(e) {} }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/16195645" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
<!-- Yandex.Metrika informer -->
<a href="http://metrika.yandex.ru/stat/?id=10337665&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/10337665/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:10337665,type:0,lang:'ru'});return false}catch(e){}"/></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<div style="display:none;"><script type="text/javascript">
(function(w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter10337665 = new Ya.Metrika({id:10337665, enableAll: true});
        }
        catch(e) { }
    });
})(window, "yandex_metrika_callbacks");
</script></div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript><div><img src="//mc.yandex.ru/watch/10337665" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</div>
</div>
<map name="Map" id="Map">
  <area shape="rect" coords="2,566,122,649" href="site/10" />
  <area shape="rect" coords="122,568,245,649" href="news/page/1" />
  <area shape="rect" coords="245,568,369,649" href="site/5" />
  <area shape="rect" coords="369,569,492,648" href="site/6" />
  <area shape="rect" coords="492,569,615,648" href="site/7" />
  <area shape="rect" coords="615,568,738,648" href="site/8" />
  <area shape="rect" coords="739,568,861,647" href="forum/" />
  <area shape="rect" coords="862,567,982,647" href="site/2" />
  <area shape="rect" coords="107,347,207,450" href="ishop/507" />
  <area shape="rect" coords="100,228,215,325" href="ishop/2" />
  <area shape="rect" coords="166,118,286,219" href="ishop/390" />
  <area shape="rect" coords="278,47,400,143" href="ishop/4" />
  <area shape="rect" coords="446,14,529,109" href="ishop/5" />
  <area shape="rect" coords="566,40,693,137" href="ishop/6" />
  <area shape="rect" coords="705,117,812,224" href="ishop/7" />
  <area shape="rect" coords="777,229,867,332" href="ishop/8" />
  <area shape="rect" coords="772,342,909,451" href="ishop/9" />
  <area shape="rect" coords="414,241,571,398" href="site/3" />
</map>
<?} else {?>
<? if (!empty($show['Подсказки'])) { ?>
<div id="tooltip">
	<div><a href="ishop/cart">Перейти к заказу</a></div>
	<div><a href="ishop/cart" id="basketDel" rel="">Убрать из корзины</a></div>
</div>
<?}?>
<? if (!empty($show['Плавающие телефоны сверху'])) { ?>
<div id="fixed">
	<div class="fixed-cont">
		<a href="/"><img src="<?=TEMP_FOLDER?>images/phone1.png" alt="" style="float:left"/><img src="<?=TEMP_FOLDER?>images/phone2.png" alt="" style="float:left"/></a>
		<div class="fixed-right"><div class="basket2" style="float:right;"></div></div>
	</div>
</div>
<?}?>
<div id="loader" style="display:none"></div>
<!--[if IE 6]>
<script type="text/javascript">
    /*Load jQuery if not already loaded*/ if(typeof jQuery == 'undefined'){ document.write("<script type=\"text/javascript\"   src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js\"></"+"script>"); var __noconflict = true; }
    var IE6UPDATE_OPTIONS = {
        icons_path: "http://static.ie6update.com/hosted/ie6update/images/"
    }
</script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/ie6update.min.js"></script>
<![endif]-->
 <table class="main col w100 h100">
<? if (1!= 1) { ?>
  <tr>
   <td class="line2 p0"><?=$sitemenu->top_menu_js(0, array('razd'=>1,'left'=>1,'right'=>1))?></td>
  </tr>
<? } ?>
  <tr>
   <td class="line1 p0">
   <div style="position:relative;">
    <table class="t_tbl w100 col">
     <tr>
      <td class="t1 p0">&nbsp;</td>
      <td class="t3 p0">&nbsp;</td>
     </tr>
    </table>
	 <? //echo '<div class="edc_text">'.$sitemenu->get_html(31).'</div>'
	 $lval = (empty($_SESSION['user'])) ? "" : "2";
	 ?>
	 <? if(file_exists(TEMP_FOLDER.'images/top_text.png')) echo '<div class="top_text"></div>' ?>
<div id="stmenu" class="top_text2"><img alt="" src="<?=TEMP_FOLDER?>images/top_text2.png" usemap="#Map" />
<map name="Map" id="Map">
  <area shape="rect" coords="20,114,127,225" href="ishop/7" />
  <area shape="rect" coords="104,9,201,107" href="ishop/507" />
  <area shape="rect" coords="229,9,341,107" href="ishop/2" />
  <area shape="rect" coords="362,9,489,109" href="ishop/4" />
  <area shape="rect" coords="513,7,636,113" href="ishop/390" />
  <area shape="rect" coords="573,117,716,226" href="ishop/9" />
  <area shape="rect" coords="441,115,538,216" href="ishop/8" />
  <area shape="rect" coords="312,113,416,215" href="ishop/5" />
  <area shape="rect" coords="155,113,281,213" href="ishop/6" />
</map>
</div>
 <script>
  $(document).ready(function () {
    nav = navigator.appName;
    if (nav != "Netscape") 
       {
       var w = document.body.clientWidth;
       }
    else
       {
       var w = window.innerWidth;
       }
	if (w>1000) {
		w=Math.round((w-253-734)/2);
	}
	else {
		w=0;
	}
	$('#stmenu').css('right',w);
});
  </script>
	 <? //echo '<div class="calender"></div>' ?>
	 <? //echo '<div class="lnk1" onclick="location.href=\''.SITE_URL.'\'"></div>' ?>
	 <? //echo '<div class="lnk2" onclick="location.href=\'mailto:'.$ebox['email'].'\'"></div>' ?>
	 <? //echo '<div class="lnk3"></div>' ?>
	 <? echo '<div class="top_phone">'.$ebox['phone'].'</div>'?>
	 <? echo '<div class="top_phone2"><table><tr><td><img alt="" src="http://status.icq.com/online.gif?icq='.$ebox['icq'].'&amp;img=5"></td>
	 <td>'.$ebox['icq'].'</td></tr></table></div>'?>
	<?// echo '<div class="top_phone2">Сегодня: '.date('d').' '.$mes2[intval(date('m'))-1].' '.date('Y').' г. '.date('H').':'.date('i').'</div>'?>

	 <? //echo '<div class="top_phone2">тел. '.$ebox['phone'].'</div>'?>
	 <? //echo '<div class="top_email"></div>'?>
	 <?php if(class_exists('Search') && !empty($show['Поиск'])) echo Search::form(0)?>
	 <div onclick="location.href='<?=SITE_URL?>site/3'" class="logo"></div>
	 <?//=$reg->form(); // Форма регистрации?>
	 <?//=$ishop->cart(); //Корзина?>
	 <div class="ttx"><?=$ebox['top_text']?></div>
	 <?//=(class_exists('Ishop')) ? $ishop->curr_box() : ''; //Выбор валюты?>
<?if(1!=1) {?>
<a href="ishop/show_prd_vs/1">добавлено к сравнению (<span class="count_vs"><?=$ishop->count_vs_prd()?></span>)</a>
<a href="ishop/show_vs/1">сравнить выбранные</a>
<?}?>
<? if (!empty($show['Меню сверху'])) { ?>
	<div class="top_menu"><?=$sitemenu->top_menu(0, array('razd'=>1,'left'=>1,'right'=>1))?></div>
<? //                                            ^
         // папка с  разделами меню -------------^
}?>

<? if (!empty($show['Флеш-часы'])) { ?>
	 <div id="flash"></div>
<script type="text/javascript">
$(document).ready(function(){

// FLASH FILE EMBED

$('#flash').flash(
     {
         src: 'about/clock_2004-1.swf',
         width: 125,
         menu:true,
         height: 125,
         background: '#000000',
         id: 'mymovie',
         wmode: 'transparent',
         flashvars: { folder: 'nightmare_ice' }
      },
      {
          expressInstall: true,
          version: '8'
      }
 );

 }); </script>

<? } 
if($_SERVER['REQUEST_URI'] == '/site/3') unset($_SESSION['bsearch_str']);
?>
	</div>
   </td>
  </tr>
  <tr>
   <td class="line3 p0 top">
    <table class="w100 h100 col pre_ttbl3">
	 <tr>
	  <td class="p0">
    <table class="t_tbl3 w100 h100 col">
     <tr>
	       <?if(1==1) {?>
      <td class="mc1 p0 top">
	   <table class="lc col">
	    <tr>
		 <td class="p0 top lmc <?=(!empty($show['Поиск'])) ? 'search_top' : ''?>">
		 <div class="lci">
		 <div class="sm_btn_1 sm_btn_open" style="background: url(<?=TEMP_FOLDER?>images/b1o.png) repeat-y transparent; height: 50px;">
		 <table class="btn1 col">
		 <tr><td class="p0" style="padding: 5px 0px 5px 0px;">
		 <form action="search/1" method="post">
			Поиск <input class="pdp_inp" style="background: white;" type="text" value="<?=htmlspecialchars((!empty($_POST['bsearch_str'])) ? trim($_POST['bsearch_str']) : $_SESSION['bsearch_str'])?>" name="bsearch_str" />
		 </form>
		 </td>
		 </tr>
		 <tr><td class="p0">
		<div class="sk_ttl">
			<a href="ishop/advs" style="text-transform: none;text-decoration: underline;">Расширенный поиск</a></div>
		 </td>
		 </tr>
		 </table>
		 </div>
		 <?=(!empty($show['Меню из кнопок'])) ? (

		  //Обычное меню
		  		 ((class_exists('Ishop')) ? $ishop->left_menu(0, 0, '_btn') : '').
		  ((class_exists('Sitemenu')) ? $sitemenu->left_menu(0, 0, '_btn') : '') .
		 //                                                  ^  ^
         // папка с  разделами меню -------------------------^  ^
		 // подразделы поставить -1 ----------------------------^
		 //Каталог

		 '' //                                        ^  ^
		 //                                           ^  ^
		 // папка с  разделами меню ------------------^  ^
		 // подразделы поставить -1 ---------------------^
		 ) : ''?>
		<?php //if(class_exists('Ishop')) echo $ishop->select()?>
		  <table class="w100 col">
		  <?if(!empty($show['Меню обычное'])) {?>
		  <tr>
		   <td class="p0 menu1"><?=$site->view('block', array('title' => '&nbsp;', 'text' =>

			//Каталог
		  ((class_exists('Ishop')) ? $ishop->left_menu(0, 0, '') : '').
		 //                                            ^  ^
		 // папка с  разделами меню -------------------^  ^
		 // подразделы поставить -1 ----------------------^

		   //Обычное меню
		  ((class_exists('Sitemenu')) ? $sitemenu->left_menu(0, 0, '') : '').
		 //                                                  ^  ^
         // папка с  разделами меню -------------------------^  ^
		 // подразделы поставить -1 ----------------------------^



		   ''

		   )) ?></td>
		  </tr>
		  <? } ?>
		  <?if(!empty($sets['mod_proizv'])) {?>
		  <tr>
		   <td class="p0 hp"><?

		   $ishop->setVar('pre', 'ishop/brand/');
		   echo $site->view('block', array('title' => '&nbsp;', 'text' => ((class_exists('Ishop')) ? $ishop->left_menu($ebox['id_proizv'], 0, '') : '')));

		   ?></td>
		  </tr>
		  <? } ?>
		  <?if(!empty($show['Свяжитесь с нами'])) {?>
		  <tr>
		   <td class="p0 conts"><?=$site->view('block', array('title' => '&nbsp;', 'text' => 'Адрес: '.$ebox['adr'].'<br> Тел.:'.$ebox['phone'].'<br> E-mail: <a href="mailto:'.$ebox['email'].'">'.$ebox['email'].'</a>')) ?></td>
		  </tr>
		  <? } ?>
		  <?if(!empty($show['Новости'])) {?>
		  <tr>
		   <td class="p0 news"><?=$site->view('block', array('title' => '&nbsp;', 'text' => News::gI()->menu())) ?></td>
		  </tr>
		  <? } ?>
		  <?if(!empty($show['Опрос'])) {?>
		  <tr>
		   <td class="p0 opros"><?=$site->view('block', array('title' => '&nbsp;', 'text' => $opros->menu())) ?></td>
		  </tr>
		  <? } ?>

		  <?if(!empty($show['Календарь'])) {?>
		  <tr>
		   <td class="p0 calender"><?=$site->view('block', array('title' => '', 'text' => '<div class="calend"></div>')) ?></td>
		  </tr>
		  <? } ?>
		   </table>

<?if(1 != 1) {?>
<div class="orphus">
<script type="text/javascript" src="orphus.js"></script>
<a href="http://orphus.ru" id="orphus" target="_blank"><img alt="Система Orphus" src="<?=SITE_URL?>orphus.gif" border="0" width="125" height="115" /></a>
</div>
<?}?>

		 <?//=((class_exists('Sitemenu')) ? $sitemenu->left_menu(0) : '').((class_exists('Ishop')) ? $ishop->left_menu(0) : '')?>
		 </div>
		 </td>
        </tr>
       </table>
	  </td>
	  <? } ?>
	   <td class="mc2 p0 top"><?
	   echo $site->view('i_mc2_outer', array('path'=> cut($content['path'],2500), 'content'=> $content['html']));
	   ?></td>
      <td class="mc3 p0 top">
	   <table class="lc2 h100 col">
	    <tr>
		 <td class="p0 top lmc2">
		  <table class="w100 col">
		  <?if(!empty($show['Корзина'])) {?>
		  <tr>
		   <td class="p0"><?=$ishop->cart()?></td>
		  </tr>
		  <? } ?>
		  <?if(!empty($show['Регистрация'])) {?>
		  <tr>
		   <td class="p0 conts1"><?=$reg->mesage()?></td>
		  </tr>
<? if (empty($_SESSION['user'])) {?>
		  <tr>
		   <td class="p0 top reg<?=(!empty($_SESSION['user'])) ? '_out' : ''?>"><?=$reg->form()?></td>
		  </tr>
<?}?>		  
		  <tr>
		   <td class="p0 conts1"><?

		  $sk = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE skidka_day = 1 && enabled = 1 LIMIT 1");

		    $scc = '<div class="skk">

			<div class="sk_ttl"><a href="ishop/product/'.$sk['0']['id'].'">'.$sk['0']['title'].'</a></div>
			<div class="sk_img">
			<a href="ishop/product/'.$sk['0']['id'].'">
			<img alt="'.$sk['0']['title'].'" src="thumb.php?id='.$sk['0']['foto'].'&x='.$ishop->image_width.'&y='.$ishop->image_height.'" /></a></div>
			<div class="sk_pr">Старая цена: '.$ishop->s_price($sk['0']['tsena']).'<br>Новая цена: '.$ishop->s_price($sk['0']['tsena'], $sk['0']['skidka']).'</div>

			</div>';

		  if ($sk['0']['id']>0) { echo $site->view('block', array('title' => '&nbsp;', 'text' => $scc));};


		   ?></td>
		  </tr>
		  <tr>
		   <td class="p0 conts2"><?

		  $sk = $db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE hit = 1 && enabled = 1 LIMIT 1");

		    $scc = '<div class="skk">

			<div class="sk_ttl"><a href="ishop/product/'.$sk['0']['id'].'">'.$sk['0']['title'].'</a></div>
			<div class="sk_img"><a href="ishop/product/'.$sk['0']['id'].'">
			<img alt="'.$sk['0']['title'].'" src="thumb.php?id='.$sk['0']['foto'].'&x='.$ishop->image_width.'&y='.$ishop->image_height.'" /></a></div>
			<div class="sk_pr">Цена: '.$ishop->s_price($sk['0']['tsena']).'</div>

			</div>';

		   echo $site->view('block', array('title' => '&nbsp;', 'text' => $scc));


		   ?></td>
		  </tr>
		  <?if(!empty($show['Подписка'])) {?>
		  <tr>
		   <td class="p0"><?=$podpiska->form()?></td>
		  </tr>
		  <? } ?>
		  <? } ?>
		   </table>
		 </td>
        </tr>
       </table>
	  </td>
	  <? //echo $site->view('i_mc3', array('text'=>$sitemenu->get_html(31))); ?>
     </tr>
    </table></td></tr></table>
   </td>
  </tr>
<? if(1!=1) { ?>
  <tr>
   <td class="line2 p0"><?=$sitemenu->top_menu()?></td>
  </tr>
<? } ?>
  <tr>
   <td class="line4 p0">
    <div style="position:relative;">
	    <table class="tbl_4 w100 col">
	 <tr>
	  <td class="f1 p0">&nbsp;</td>
	  <td class="f3 p0">&nbsp;</td>
	 </tr>
	</table>
<?if(!empty($show['Текст в подвале'])) {?>
<? echo Site::gI()->view('main/i_abouts2', array(
	'text' => '

	Адрес: '.$ebox['adr'].'<br>
	Тел.:'.$ebox['phone'].'<br>
	E-mail: <a href="mailto:'.$ebox['email'].'">'.$ebox['email'].'</a> <a style="margin-left:150px;" href="'.SITE_URL.'map/view/all">Карта сайта</a>'

)); ?>
<?}?>
<? require('counters.php'); //Счетчики ?>
	</div>
   </td>
  </tr>
 </table>
<?if(!empty($show['Плавающийй телефон'])) {?>
<div id="divStayTopLeft" style="position:absolute"><table class="col fly_phone"><tr><td class="p0 phone"><?=$ebox['phone']?></td></tr></table></div>
<script type="text/javascript" src="jscripts/fly_phone.js"></script>
<?}?>
<?}?>
<? //echo Site::gI()->view('music'); //Музыка на сайте ?>
<?if(!empty($show['Защита'])) require('html_protect.php'); //Защита от копирования?>
<script type="text/javascript">
$(window).resize(function(){
    nav = navigator.appName;
    if (nav != "Netscape") 
       {
       var w = document.body.clientWidth;
       }
    else
       {
       var w = window.innerWidth;
       }
	if (w>1000) {
		w=Math.round((w-253-734)/2);
	}
	else {
		w=0;
	}
	 $('#stmenu').css('right',w);
});
 </script>
</<?='b'.'o'.'d'.'y'?>>
</<?='h'.'t'.'m'.'l'?>>