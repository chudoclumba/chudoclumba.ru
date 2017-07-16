<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

$buff = '';

include_once "../../../includes/kanfih.php";
include_once "../../vars.php";

include_once INC_DIR."dbconnect.php";
include_once INC_DIR."functions.php";
include_once SA_DIR."inc/susfunction.php";

switch($_GET['act']){
	case 'viewcart':
	{
		$kl=$db->get(TABLE_CART, array('id'=>trim($_GET['id'])));
		$buff .='Корзина: '.$kl[0]['id'].' создана '. date('d-m-y G:i', $kl[0]['datec']).' обновлена ' .date('d-m-y G:i', $kl[0]['date']);
		if ($kl[0]['userid']>0)
		{
			$queryk ='SELECT k.data, k.login, k.pass, k.info, sum(o.summa) as sm, count(o.summa) as cn FROM '.TABLE_USERS.' k left join '
			.TABLE_ORDERS.' o on k.id=o.user_id  where k.id='.$kl[0]['userid'].' group by k.id';
			$k = $db->query_first($queryk);
			$inf=unserialize($k['info']);
			$buff .='<br/>Клиент: '.$inf[9].' '.$inf[8].' '.$inf[11].'<br/>';
			$buff .='заказов: '.$k['cn'].' на сумму: '.$k['sm'].'<br/>';
		}
		$query ='SELECT c.cnt, p.* FROM '.TABLE_CART_DET.' c left join '.TABLE_PRODUCTS." p on p.id=c.prdid where c.cartid='".$_GET['id']."' order by p.param_kodtovara";
		$q = $db->get_rows($query);
		ob_start();
?>
<table width="98%" cellspacing="0" cellpadding="0">
<tr>
	  <th class="news_header lft">Код</th>
	  <th class="news_header lft">Наименование</th>
	  <th class="news_header lft">ID</th>
	  <th class="news_header rgt">Цена</th>
	  <th class="news_header lft">Кол-во</th>
	  <th class="news_header rgt">Сумма</th>
</tr>
<?
	$sum=0;
	foreach ($q as $row)	{
		$sum+=($row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']))*$row['cnt'];
 ?>
	 <tr class="cli" title="По корзинам" <? echo(($row['enabled'] && $row['visible']) ? '' : 'style="background:#FFFF00"')?> onclick="prd_in_cart(this,'<? echo $row['id']?>')">
	  <td class="news_td lft"><?php echo $row['param_kodtovara']?></td>
	  <td class="news_td lft"><?php echo $row['title']?></td>
	  <td class="news_td lft"><?php  echo $row['id']?></td>
	  <td class="news_td rgt"><?php  echo ($row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']))?></td>
	  <td class="news_td lft"><?php  echo $row['cnt']?></td>
	  <td class="news_td rgt"><?php  echo ($row['tsena'] - (0.01 * $row['skidka'] * $row['tsena']))*$row['cnt']?></td>
	 </tr>
<?	}?>	 
	 <tr>
	  <td class="news_td rgt" colspan="5">Сумма</td>
	  <td class="news_td rgt"><? echo $sum ?> </td>
	 </tr>
</table>
<?	
		$buff .= ob_get_contents();
		ob_end_clean();
		break;
	}
	case 'prdincart':
	{
		$kl=$db->get(TABLE_PRODUCTS, array('id'=>trim($_GET['id'])));
		$buff .='Товар: '.$kl[0]['param_kodtovara'].' '.$kl[0]['title'];
		$query ='SELECT d.cnt, c.* FROM '.TABLE_CART_DET.' d left join '.TABLE_CART." c on c.id=d.cartid where d.prdid='".$_GET['id']."' order by c.date desc";
		$q = $db->get_rows($query);
		ob_start();
?>
<table width="98%" cellspacing="0" cellpadding="0">
<tr>
	  <th class="news_header lft">Номер</th>
	  <th class="news_header lft">Дата обн.</th>
	  <th class="news_header lft">Клиент</th>
	  <th class="news_header lft">Кол-во</th>
</tr>
<?
	foreach ($q as $row)	{
 ?>
	 <tr>
	  <td class="news_td lft cli" onclick="view_cart(this);"><?php echo $row['id']?></td>
	  <td class="news_td lft"><?php echo date('d-m-y G:i', $row['date'])?></td>
	  <td class="news_td lft"><?php  echo ($row['userid']>0 ? $row['userid'] :  '&nbsp;')?></td>
	  <td class="news_td lft"><?php  echo $row['cnt']?></td>
	 </tr>
<?	}?>	 
</table>
<?	
		$buff .= ob_get_contents();
		ob_end_clean();
		break;
	}
	case 'clearcart':
	{
		$buff=$db->delete(TABLE_CART_DET,['cartid'=>$_GET['id']]);
		break;
	}
	case 'clearoldcart':
	{
		$tmc=isset($sets['cart_time']) ? $sets['cart_time'] : 60;
		$sql='delete c.* from  '.TABLE_CART.' c where c.userid=0 and c.date<'.(time()-$tmc*24*60*60);
		$sql1='delete d.* from '.TABLE_CART_DET.' d, '.TABLE_CART.' c where c.id=d.cartid and c.userid>0 and c.date<'.(time()-180*24*60*60);
		$sql2='delete d.* FROM '.TABLE_CART_DET.' d LEFT JOIN '.TABLE_CART.' c ON c.id=d.cartid WHERE c.id IS NULL';
		$res1=$db->exec($sql);
		$res2=$db->exec($sql1);
		$res3=$db->exec($sql2);
		$buff =($res1===false || $res2===false || $res3===true) ? '0' : '1';
		break;
	}
	case 'deletecart':
	{
		$db->delete(TABLE_CART_DET,['cartid'=>$_GET['id']]);
		$buff=$db->delete(TABLE_CART,['id'=>$_GET['id']]);
		break;
	}
	
	default:
		break;
}
echo $buff;
?>