<?
$rows=array();
if ($cat['srcid']>0) {
	$rows=$this->db->get_rows('select * from chudo_ishop_products where srcid='.$cat['srcid'].' and visible=1 order by tsena');
	foreach($rows as $id => $row){
		if ($row['id']==$row['srcid']) $cat=$row;
	}
}
$q=$this->db->get_rows("select * from chudo_upak");
$upak=array();
foreach($q as  $r){
	$upak[$r['id']]=array('name'=>$r['name'],'descr'=>$r['descr']);
}


?>
<!--start-->
<script type="text/javascript">
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
    "ecommerce": {
        "detail": {
            "products": [
                {
                    "id": '<?=$cat["id"]?>',
                    "name" : '<?=$cat["title"]?>',
                    "price": '<?=number_format($this->skidka($cat["tsena"], $cat["skidka"]),2,'.','')?>',
                    "brand": '<?=$cat["param_proizvoditel"]?>',
                    "category": '<?=$top_cat?>'
                }
            ]
        }
    }
});
</script>
                          
                        <div class="product-single-details">
                            <div class="col-md-5 col-sm-5 col-xs-12"><div style="position: relative">
<?  
if ($row['param_starayatsena']>0 || (!empty($cat['spec'])) ||  $cat['skidka']>0 ){?>
			<div class="product-label">
				<div class="sale"></div>
			</div>
<?}		

if ((!empty($cat['new'])) ){?>
			<div class="product-label">
				<div class="new"></div>
			</div>
<?}		

   $p_size = ($sets['mod_p_size']) ? 'class="highslide" onclick="return hs.expand(this)"' : ''; 
   if(file_exists( trim($cat['foto'],'/'))) {?>
<a id="flink" <?=$p_size?> href="<?=SITE_URL?><?=trim($cat['foto'],'/')?>">
<img alt="<?=$cat['title']?>" src="<?=SITE_URL?>thumb.php?id=<?=trim($cat['foto'],'/')?>&x=340&y=340&crop" width="340" height="340" />
</a>
<? } else { ?>Изображение временно отсутствует.<? } ?></div>
</div>                            
<div class="col-md-7 col-sm-7 col-xs-12">
<div style="padding:6px 0 6px 5px; font-size:14px; color: #fb5a04"><?=$cat['param_naimenovanielatinskoe']?></div> 
<div class="product-details-content">
<h3><?=htmlspecialchars($cat['title'],ENT_COMPAT | ENT_XHTML,'utf-8')?></h3>
<?
		if(!empty($cat['param_brend']) && !empty($sets['mod_proizv'])) {
			?><div class="ppr_d_t">Производитель: <a href="ishop/brand/<?=$cat['param_brend']?>"><?=$this->get_cat_title($cat['param_brend'])?></a></div><?
		} 
echo (!empty($cat['param_proizvoditel'])) ? '<div class="ppr_d_t"><b>Производитель:</b> '.$cat['param_proizvoditel'].'</div>
' : '';
echo (!empty($cat['param_visota'])) ? '<div class="ppr_d_t"><b>Высота, см:</b> '.$cat['param_visota'].'</div>
' : '';
echo (!empty($cat['param_shirina'])) ? '<div class="ppr_d_t"><b>Ширина, см:</b> '.$cat['param_shirina'].'</div>
' : '';
echo (!empty($cat['param_aromat'])) ? '<div class="ppr_d_t"><b>Аромат:</b> '.$cat['param_aromat'].'</div>
' : '';
echo (!empty($cat['param_tsvetenie'])) ? '<div class="ppr_d_t"><b>Цветение:</b> '.$cat['param_tsvetenie'].'</div>
' : '';
echo (!empty($cat['param_razmertsvetka'])) ? '<div class="ppr_d_t"><b>Размер цветка, см:</b> '.$cat['param_razmertsvetka'].'</div>
' : '';
echo (!empty($cat['param_tiptsvetka'])) ? '<div class="ppr_d_t"><b>Тип цветка:</b> '.$cat['param_tiptsvetka'].'</div>
' : '';
echo (!empty($cat['param_periodtsveteniya'])) ? '<div class="ppr_d_t"><b>Период цветения:</b> '.$cat['param_periodtsveteniya'].'</div>
' : '';
echo (!empty($cat['param_okraskatsvetka'])) ? '<div class="ppr_d_t"><b>Окраска цветка:</b> '.$cat['param_okraskatsvetka'].'</div>
' : '';
echo (!empty($cat['param_okraskalistvi'])) ? '<div class="ppr_d_t"><b>Окраска листвы:</b> '.$cat['param_okraskalistvi'].'</div>
' : '';
if(!empty($cat['param_morozostoykost'])){ 
	$ss=(strlen(strpbrk($cat['param_morozostoykost'], '0123456789'))>0) ? '&nbsp;&nbsp;&nbsp;<a href="site/28">Зоны морозостойкости</a>' : '';
?><div class="ppr_d_t"><b>Морозостойкость:</b> <?=$cat['param_morozostoykost'].$ss?></div>
<?}
		if(!empty($cat['param_muchnistayarosa']) || !empty($cat['param_chernayapyatnistost'])) { 
		echo '<div class="ppr_d_t"><b>Устойчивость листвы к заболеваниям:</b>';
		echo (!empty($cat['param_muchnistayarosa'])) ? '<br><b>мучнистая роса:</b> '.$cat['param_muchnistayarosa'] : '';
		echo (!empty($cat['param_chernayapyatnistost'])) ? '<br><b>черная пятнистость:</b> '.$cat['param_chernayapyatnistost'] : '';
		echo '</div>
		';
		}
		echo (!empty($cat['param_tippochvi'])) ? '<div class="ppr_d_t"><b>Тип почвы:</b> '.$cat['param_tippochvi'].'</div>
' : '';
		echo (!empty($cat['param_svetovoyrezhim'])) ? '<div class="ppr_d_t"><b>Световой режим:</b> '.$cat['param_svetovoyrezhim'].'</div>
' : '';
?>

</div>
</div>
</div>
<div class="clearfix"></div>
<div class="product-details-content">                        
<?

if (!count($rows)>0) $rows[0]=$cat;
// echo '<tr><th class="nbl"><div class="ppd_list_item pr10">Код товара</div></th><th><div class="ppd_list_item pr10 pl10">Cтандарт<br>поставки</div></th><th><div class="ppd_list_item pr10">Срок поставки</div></th><th><div class="ppd_list_item pr10 tcen">Цена</div></th><th class="noprint"></th>'.((Wish::gI()->on)?'<th class="noprint"></th>':'').'</tr>';

foreach($rows as $id => $row){
	    $mt='';
		if (Wish::gI()->on)
				$mt= str_ireplace('%cnt%',Wish::gI()->ShowCnt($row['id']),str_ireplace('%id%',$row['id'],Wish::gI()->knadd));
    $q="select sum(d.count+d.isdel-d.otgr) as otot from ".TABLE_ORDERS_PRD." d right join ".TABLE_ORDERS." o on o.id=d.order_id where o.status =1 && prd_id={$row['id']} group by prd_id";
    $av=$row['sklad']+$row['zakazpost']-$row['reserv'];
	$rwi=$this->db->get_rows($q);
	if (count($rwi)>0) $av=$av-$rwi[0]['otot'];
    $q="select sum(cnt) as ctot from chudo_cart_det d right join chudo_cart o on o.id=d.cartid 
 where o.date>(UNIX_TIMESTAMP()-60*{$this->sets['cart_res_time']}) && prdid={$row['id']}";
	$rwi=$this->db->get_rows($q);
	if (count($rwi)>0) $av=$av-$rwi[0]['ctot'];
	$ss='';
	if ($_SERVER['REMOTE_ADDR']==$this->sets['ofip'] || User::gI()->user_role>0) $ss=' title="Редактировать в СУС" class="link_sus nbl" onclick="window.open'."('/wq121/include.php?place=ishop#update_prd_html(".$row['id'].")','',''); return false;".'"'; 
	else  $ss=' class="nbl"';
	$pos= strtoupper(substr(trim($row['param_kodtovara']),17,2));
		$ct='';
		if (isset($_SESSION[CART][$row['id']]['count']) && $_SESSION[CART][$row['id']]['count']>0)  $ct= '(Уже '.$_SESSION[CART][$row['id']]['count'].')';
	
	
	?>
	
<div class="add-to-box-view pt25 pb10 mb10">

 <div class="col-md-5 col-sm-5 col-xs-12">
 	<div class="ppr_d_t" <?=$ss?>><?="Код товара: ".$row['param_kodtovara']?></div>
 	<div class="ppr_d_t lf" data-title="<?=$upak[$pos]['descr']?>">Стандарт поставки: <span style="font-weight:700"><?=$upak[$pos]['name']?></span>
	<?=(!empty($row['param_kolichestvo']) && $row['isupak']==1) ? ', Кол-во в упак.: '.$row['param_kolichestvo'] : ''?>
	</div>
	<div class="ppr_d_t"><?='Срок поставки: '.$row['param_srokpostavki']?></div>
 </div>
<div  class="col-md-7 col-sm-7 col-xs-12">                
<?=$mt?>
<div class="price-box">
<span class="new-price pr10"><?=$this->s_price($row['tsena'],($row['skidka_day']>0)?  $row['skidka'] : 0)?></span>
<?if(!empty($row['param_starayatsena']) && $row['param_starayatsena'] > 0 && $sets['mod_old_price'] && !($row['skidka_day']>0)) {?>
<span class="old-price"><del><?=$this->s_price($row['param_starayatsena'], 0)?></del></span>
<?}?>
</div>
<?if($row['enabled']==1 && $this->get_prdstate($row['cat_id']) && ($av>0 || $row['saletype']==0)){?>
<div class="pb20">
    <a class="button cart_button" id="spac_<?=$row['id']?>" onclick="Add_to_cart(this);return false;" title="Добавить в корзину">
        <span>В Корзину</span><span class="cart_btinfo"><?=$ct?></span>
    </a>
	
</div>
<?} else {?>
<div class="ppd_list_item" style="color:red; font-size:11px;">Прием заказов завершен</div>	
<?}?>
</div>	
<div class="clearfix"></div>
</div>
<?}?>	
</div>                      
                        <div class="col-md-12">
                            <div class="features-tab product-des-review">
                                <!-- Nav tabs -->
                                <ul class="nav">
                                    <li role="presentation" class="active"><a href="#product-des" data-toggle="tab">Описание</a></li>
                                    <li role="presentation"><a href="#add-review" data-toggle="tab">Отзывы</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="product-des">
                                        <div class="std">
                                         <?=$cat['param_polnoeopisanie']?>   
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="add-review">
<?	if($sets['mod_comments']) echo $this->get_prd_comment($cat['id']);?>
    <? if($sets['mod_comments']) {?>
                                        <div class="review-lower mt30">
                                            <div class="input-header">
                                                <p>Напишите свой отзыв!</p>
                                            </div>
                                            <form action="" method="post" id="frmFeedback">
                                                <div class="input-box">
                                                    <span>Имя:</span>
                                                    <input type="text" class="review_user_name" placeholder="Имя" name="name" id="name">
                                                </div>
                                                <div class="input-box">
                                                   <span>Email:</span>
                                                   <input type="email" placeholder="Ваш Email" class="review_email" name="email" id="email">                                                </div>
                                                <div class="input-box">
                                                    <span>Пожалуйста, оставьте Ваш отзыв</span>
                                                    <textarea name="msg" id="msg"></textarea>
                                                </div>
                                                <div class="product-running">
                                                <div class="add_msg_btn"><noscript>Для включения возможности оставлять отзывы необходимо включить JavaScript</noscript></div>
<?if ($sets['mod_rating']) {
	 echo $this->get_prd_rating($_GET['id']); }?>
 <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,lj,yaru"></div>                                                  
                                                </div>
                                            </form>
                                        </div>
<?}?>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
<script>
$().ready(function(){
	        jQuery("#frmFeedback").validate({
        	submitHandler: function(f){
        			var dta=$(f).serializeArray();
	$.ajax({
		type		: "POST",
		cache	: false,
		url		: "service/send_comment/<?=$cat['id']?>",
		data		: dta,
		success: function(hh) {
				$('#frmFeedback').html(hh);
		}
	});

        	},
            rules : {name : {required : true, minlength: 4},
            email : {required : true, email : true},
            msg :  {required : true, minlength: 4}
            },
            messages : {
            	name : {required : "<span class=\"frm_err\">Заполните поле Имя</span>", minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа!</span>"},
				email : {required : "<span class=\"frm_err\">Заполните поле Email</span>", email : "<span class=\"frm_err\">Неверно заполнено поле Email</span>"},
            	msg : {required : "<span class=\"frm_err\">Заполните поле Отзыв</span>", minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа!</span>"}
				
            }
        });
	$('.add_msg_btn').html('<a title="Submit" href="#" class="button cart_button" name="otpravitb" onclick="$(\'#frmFeedback\').submit(); return false;"><span>Отправить</span></a>');
});
</script>

<table class="w100 col noprint">
<?
	//рекомендуемые товары
	if($sets['mod_rec_prds']) echo $this->get_prd_recc($cat['id'],$cat['cat_id']);

?>
<tr><td class="mcnp">

<br></td></tr>
<?

	//другие товары в этой категории
	if(ISHOP_GALLERY) echo $this->gallery($cat['cat_id']);

	//характеристики товара
	if($sets['mod_chars']) echo $this->get_prd_chars($cat['id'], $cat['cat_id']);



//echo '<tr><td><div class="mcnp"><button name="Print" type="button" value="ok" onclick="window.print();" class="albutton alorange"><span><span><span class="print">Распечатать</span></span></span></button></div><br></td></tr>';

if($sets['mod_prd_foto']) echo '<tr><td>'.$this->get_prd_photos($cat['id']).'</td></tr>';
?>
</table>
<!--end of productnew-->