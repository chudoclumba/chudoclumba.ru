<div class="col-md-12 pb30" id="cart_frm">
                    <div class="area-title bdr">
                        <h2>ВАША КОРЗИНА</h2>
                    </div>
                    <div class="table-area">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr class="c-head">
                                        <th></th>
                                        <th>Наименование</th>
                                        <th>Цена</th>
                                        <th>Кол-во</th>
                                        <th>Стоимость</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
<?
		if (!isset($_SESSION['script'])) $_SESSION['script']='';
		$_SESSION['script'].='<script type="text/javascript">$(window).load(function() {yaCounter16195645.reachGoal(\'Cart_on\')});</script>';

$ps='--';
$zk=0;
$sum=0;
$sum_for_sale = 0;
$sale=0;

if(!empty($this->sets['mod_prd_skidka']) && !empty($_SESSION['user']))
{
	$orders1 = $this->db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$_SESSION['user']."' && status != 6");
}
if (isset($_SESSION['user']) && $_SESSION['user']>0) $sale=User::gI()->user['sale'];
if(!empty($this->sets['mod_cards']) || ((!empty($this->sets['mod_prd_skidka']))   && (!empty($_SESSION['user'])) )  && !empty($orders1['0']['summa'])){

	if(!empty($this->sets['mod_prd_skidka']) && !empty($_SESSION['user']) && !empty($orders1['0']['summa'])) {
		$orders = $this->db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
		if ($orders['0']['percent']>$sale) $sale=$orders['0']['percent'];
	}
}	

 foreach($prd as $id=>$val)
{
	$link=SITE_URL.'ishop/product/'.$id;
	if(!empty($val['vlink']) && $this->sets['cpucat']==1){
		$link=SITE_URL.$val['vlink'];
	}
	if ($ps!=$val['tper']){
		$zk++;
		$ps=$val['tper'];
		echo '<tr><th>Заказ №'.$zk.'</th><th class="tcen" colspan="5">'.$val['param_srokpostavki'].' *Поставка с '.date('d.m.Y',$val['dt']).'</th></tr>';
	}
	$q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,p.saletype from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].") and not oc.id='{$_SESSION['cartid']}' group by prdid) as cr on cr.prdid=p.id WHERE p.id={$val['prdid']}";
	$prdi=$this->db->get_rows($q);
	if ($prdi[0]['saletype']==1)
		if ($val['cnt']>$prdi[0]['ost']) $val['cnt']=($prdi[0]['ost']>0)?$prdi[0]['ost']:0;
	$sum+=$this->skidka($val['tsena']*$val['cnt'], $val['skidka']);

//echo $val['skidka'];

	if(!Site::gI()->isProductForSale($val['prdid']))
	    $sum_for_sale += $val['tsena']*$val['cnt'];

?>
    <tr id="cp_<?=$id?>" <?echo(($val['active'] && ($prdi[0]['ost']>0 || $prdi[0]['saletype']==0)) ? '' : 'style="background:#ffff00"')?>>
        <td class="c-img">
   <? if(file_exists($val['foto'])) {?>
		<a id="flink" class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$val['foto']?>">
        <img alt="<?=$val['name']?>" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&x=250&y=250&crop" style="display: block"/>
		</a>
	   <? } else { ?>Изображение временно отсутствует.<? } ?>
        </td>
        <td class="c-name"><a href="<?=$link?>"><?=$val['name']?></a><br>
            <span class="c-size">Код товара : <?=$val['param_kodtovara']?></span>
        </td>
        <td class="c-price"><?=$this->s_price($val['tsena'], $val['skidka'])?></td>
  <? if ($val['active'] && ($prdi[0]['ost']>0 || $prdi[0]['saletype']==0)) {?>
  <td class="c-qty">
  <input id="cnt_<?=$id?>" value="<?=$val['cnt']?>" />
  </td>
  <td class="c-price"><?=$this->s_price($val['tsena']*$val['cnt'], $val['skidka'])?></td>  
  <?}else{?>
  <td class="c-name" colspan="2">Прием заказов завершен</td>	
 <?		$val['cnt']=0;
 	}?>        
        <td class="trash-btn">
            <a class="btn-remove mb20" onclick="ClearCartItem(<?=$id?>);" title="Удалить из корзины"></a>
            <?if (Wish::gI()->on && User::gI()->is_logged() && empty(Wish::gI()->ShowCnt($id)))
				echo str_ireplace('%cnt%',Wish::gI()->ShowCnt($id),str_ireplace('%id%',$id,Wish::gI()->knmove));?>
        </td>
    </tr>


<?}?>
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            <a class="button c_button c-shop" onclick="location.href='<?=SITE_URL.'ishop/order'?>'" title="Перейти к оформлению заказа">
                                                <span>Оформить заказ</span>
                                            </a>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
 <div class="discount-process-area">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                     </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="single-dis">
                            <div class="discount">
                                <? if (isset($_SESSION['user']) && $_SESSION['user'] != null){
                                    $promo = PromoEngine::Instance()->getPromoAssignedToUser($_SESSION['user']);
                                    if(isset($promo))
                                    {
                                        ?>
                                        <div class="discount-form">
                                            <h2>Вы успешно применили промо код: <?php echo $_SESSION["currentPromoCode"];?></h2>
                                        </div>
                                <?
                                    }
                                    else
                                    {
                                        Logger::Info("CASE 1");
                                        ?>
                                        <h2>Коды скидок</h2>
                                        <div class="discount-form">
                                            <form action="ishop/cart" method="post" id="promo">
                                                <label>Если у Вас есть промо-код, введите его </label>
                                                <div class="input-box">
                                                    <input type="text" value="" id="rabatt" name="rabatt" class="inputbox">
                                                </div>
                                                <div class="coupon_submit">
                                                    <a class="button c_button" href="" onClick="document.getElementById('promo').submit(); return false;">
                                                        <span>Применить код</span>
                                                    </a>
                                                    <!--                                            <button type="submit" class="button c_button" title="Применить код" />-->
                                                </div>
                                            </form>
                                        </div>
                                <?
                                    }
                                }
                                else
                                {
                                    if(!isset($_SESSION["promoError"]))
                                    {
                                        if(isset($_SESSION["currentPromo"]) && $_SESSION["currentPromo"] != null)
                                        {

                                            $promo = $_SESSION["currentPromo"];
                                            $promoCode = $_SESSION["currentPromoCode"];//$promo->getPromoCode();
                                            Logger::Info("CASE 2");
                                            ?>
                                            <h2>Коды скидок</h2>
                                            <div class="discount-form">
                                                <form action="ishop/cart" method="post" id="promo">
                                                    <label>
                                                        Вы успешно применили промо код: <?php echo $_SESSION["currentPromoCode"]; ?>
                                                    </label>
                                                    <!--<div class="input-box">
                                                        <input type="text" value="" id="rabatt" name="rabatt" class="inputbox">
                                                    </div>
                                                    <div class="coupon_submit">
                                                        <a class="button c_button" href="" onClick="document.getElementById('promo').submit(); return false;">
                                                            <span>Применить код</span>
                                                        </a>
                                                        <!--                                            <button type="submit" class="button c_button" title="Применить код" />-->
<!--                                                    </div>-->
                                                </form>
                                            </div>
                                            <?
                                        }
                                        else
                                        {
                                            Logger::Info("CASE 3");
                                            ?>
                                            <h2>Коды скидок</h2>
                                            <div class="discount-form">
                                                <form action="ishop/cart" method="post" id="promo">
                                                    <label>Если у Вас есть промо-код, введите его </label>
                                                    <div class="input-box">
                                                        <input type="text" value="" id="rabatt" name="rabatt" class="inputbox">
                                                    </div>
                                                    <div class="coupon_submit">
                                                        <a class="button c_button" href="" onClick="document.getElementById('promo').submit(); return false;">
                                                            <span>Применить код</span>
                                                        </a>
                                                        <!--                                            <button type="submit" class="button c_button" title="Применить код" />-->
                                                    </div>
                                                </form>
                                            </div>
                                            <?
                                        }
                                    }
                                    else
                                    {
                                        Logger::Info("CASE 4");
                                        ?>
                                        <h2>Коды скидок</h2>
                                        <h2><?$_SESSION["promoError"]?></h2>
                                        <div class="discount-form">
                                            <form action="ishop/cart" method="post" id="promo">
                                                <label><?php echo $_SESSION["promoError"];?> </label>
                                                <div class="input-box">
                                                    <input type="text" value="" id="rabatt" name="rabatt" class="inputbox">
                                                </div>
                                                <div class="coupon_submit">
                                                    <a class="button c_button" href="" onClick="document.getElementById('promo').submit(); return false;">
                                                        <span>Применить код</span>
                                                    </a>
                                                    <!--                                            <button type="submit" class="button c_button" title="Применить код" />-->
                                                </div>
                                            </form>
                                        </div>
                                        <?
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="subtotal-area">
                            <table class="shop_subtotal">
                                <tbody>
                                    <tr>
                                        <td class="s-total">
                                            Сумма
                                        </td>
                                        <td>
                                            <span class="s-price sum"><?=$this->s_price_c($sum)?></span>
                                        </td>
                                    </tr>
<?
Logger::Info("SALE1 = ".$sale);
if(isset($_SESSION["currentPromoCode"]) && $_SESSION["currentPromoCode"] != null)
{
    if (isset($_SESSION['user']) && $_SESSION['user'] != null)
    {
        $promoValue = PromoEngine::Instance()->getPromoValueAssignedToUser($_SESSION['user']);
        $sale += $promoValue;
        Logger::Info("SALE2 = ".$sale);
    }
}
else
{
    if (isset($_SESSION['user']) && $_SESSION['user'] != null)
    {
        Logger::Info("SALE3 = ".$sale);
        $promoValue = PromoEngine::Instance()->getPromoValueAssignedToUser($_SESSION['user']);
        $sale += $promoValue;
    }
}
if ($sale>0) {

    ?>
                                    <tr>
                                        <td class="s-total">
                                            Скидка
                                        </td>
                                        <td>
                                            <span class="s-price sumsk"><?=$this->s_price_c(($sum_for_sale*$sale)/100)?></span>
                                        </td>
                                    </tr>

<?}?>                                   
                                </tbody>
                                <tfoot>
                                    <tr class="s-total">
                                        <td>
                                            <strong>Общая сумма</strong>
                                        </td>
                                        <td>
                                            <strong><span class="s-price sumall"><?$skidka = (!empty($sale)) ? ($sum_for_sale*$sale)/100 : 0;echo $this->s_price_c($sum - $skidka)?></span></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="clear"></div>
                            <a class="button c_button" title="Перейти к оформлению заказа" onclick="location.href='<?=SITE_URL.'ishop/order'?>'">
                                <span>Оформить заказ</span>
                            </a>
                        </div>
                    </div>
                </div>
 <div class="clearfix"></div>
* Сроки поставок ориентировочные. Товары из разных заказов могут быть объединены в одну отгрузку (по наличию на складе) без дополнительных уведомлений.
              </div>

<script>
$('.c-qty input').bind('change',function(){SetCartCnt(this);});
</script>

