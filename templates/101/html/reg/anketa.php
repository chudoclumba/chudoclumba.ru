<div  class="orders_btnm"><input onclick="location.href='<?=SITE_URL?>service/orders'" type="button" value="Заказы" /></div>
<form action="user/anketa" method="post">
<div class="reg_data">
<table class="col ishop_ord_tbc2">
 <tr>
  <td class="p0 top">
  <table>
<?
foreach($data as $id=>$val)
{
?>
   <tr>
    <td class="reg_col_1"><?=$id?></td>
    <td class="reg_col_2"><input class="fbinps" name="reg_<?=$val?>" type="text" value="<?=(!empty($info[$val])) ? htmlspecialchars($info[$val],ENT_COMPAT | ENT_XHTML,'cp1251') : ''?>" /></td>
   </tr>
<?
}
?>
   </table>
</td>
 </tr>
</table>
</div>
<div class="orders_btnm"><input type="submit" value="Сохранить" /></div>
</form>
