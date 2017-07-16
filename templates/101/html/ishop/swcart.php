<div class="col-md-12 pb30">
<div class="row">
<div class="area-title bdr">
<h2>Выбор корзины</h2>
</div>
<p>На данном компьютере обнаружена корзина неизвестного пользователя, при этом, у Вас есть персональная корзина.
Вы можете продолжить работу с персональной корзиной или объединить обе корзины.</p>
<script>
function SendU(Val) {
   var obj=document.unionfrm;
   obj.union.value=Val;
   obj.submit();
}
</script>
<form action="" method="POST" name="unionfrm">
<input name="union" type="hidden" value="use_user"/>
<div class="add-to-box-view">
                <a class="button v_button" onclick="SendU('use_user')" title="Использовать персональную корзину"><span>Персональная</span></a>
                <a class="button cart_button" onclick="SendU('union')" title="Объединить все корзины в персональную"><span>Объединить</span></a>
                <div class="clearfix"></div>
</div>
</form>
<div class="col-md-6 col-sm-12 col-xs-12 col-padd">
<div class="area-title bdr">
<h3>Персональная корзина</h3>
</div>
<div class="table-area">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
 <tr class="c-head">
  <th class=""></th>
  <th class="">Наименование</th>
  <th class="">Цена</th>
  <th class="">Кол-во</th>
 </tr>
 </thead><tbody>
<? foreach($pcart as $id => $val){?> 
<tr>
        <td class="c-img">
   <? if(file_exists($val['foto'])) {?>
		<a id="flink" class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$val['foto']?>">
        <img alt="<?=$val['name']?>" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&x=100&y=100&crop" style="display: block"/>
		</a>
	   <? } else { ?>Изображение временно отсутствует.<? } ?>
        </td>

<td class="c-name"><?=$val['name']?></td>
<td class="c-price"><?=$val['price']?></td>
<td class="c-qty"><?=$val['cnt']?></td>
</tr>
<?   }?>
</tbody>
</table>
</div></div> 
</div>
<div class="col-md-6 col-sm-12 col-xs-12 col-padd">
<div class="area-title bdr">
<h3>Корзина неизвестного пользователя</h3>
</div>
<div class="table-area">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
 <tr class="c-head">
  <th class=""></th>
  <th class="">Наименование</th>
  <th class="">Цена</th>
  <th class="">Кол-во</th>
 </tr>
 </thead><tbody>
<? foreach($kcart as $id => $val){?>
<tr>
        <td class="c-img">
   <? if(file_exists($val['foto'])) {?>
		<a id="flink" class="highslide" onclick="return hs.expand(this)" href="<?=SITE_URL?><?=$val['foto']?>">
        <img alt="<?=$val['name']?>" src="<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&x=100&y=100&crop" style="display: block"/>
		</a>
	   <? } else { ?>Изображение временно отсутствует.<? } ?>
        </td>

<td class="c-name"><?=$val['name']?></td>
<td class="c-price"><?=$val['price']?></td>
<td class="c-qty"><?=$val['cnt']?></td>
</tr>
<?   }?>
</tbody>
</table>
</div></div> 
</div>
</div></div>