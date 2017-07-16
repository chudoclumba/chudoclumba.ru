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
	case 'viewwish':
	{
		$kl=$db->get(TABLE_WISH, array('userid'=>trim($_GET['id'])));
		$infcart = $db->get_rows('SELECT c.id, c.date,c.datec, c.userid, c.ip, c.isset, c.scooc, sum(d.cnt) as cnt FROM '.TABLE_CART.' c left join '.TABLE_CART_DET.' d on d.cartid=c.id where c.userid='.$_GET['id'].' group by c.id');
		
		$buff .='WishList: '.$kl[0]['id'].' создан '. date('d-m-y G:i', $kl[0]['datec']).' обновлен ' .date('d-m-y G:i', $kl[0]['date']);
			$queryk ='SELECT k.data, k.login, k.pass, k.info, sum(o.summa) as sm, count(o.summa) as cn FROM '.TABLE_USERS.' k left join '
			.TABLE_ORDERS.' o on k.id=o.user_id  where k.id='.$kl[0]['userid'].' group by k.id';
			$k = $db->query_first($queryk);
			$inf=unserialize($k['info']);
			$buff .='<br/>Клиент: '.$inf[9].' '.$inf[8].' '.$inf[11].'<br/>';
			$buff .='заказов: '.$k['cn'].' на сумму: '.$k['sm'].'<br/>';
		 	if (count($infcart)>0 && $infcart[0]['id']>' ')
		   		$buff .='<div class="highslide cli" onclick="view_cart(this,\''.$infcart[0]['id'].'\');">Корзина '.$infcart[0]['id'].(($infcart[0]['date']>0) ? date(' Обновлена d-m-y, G:i',$infcart[0]['date']) : '&nbsp;').' Товаров '.(($infcart[0]['cnt']>0) ? $infcart[0]['cnt'] : ' нет.').'</div>';
			
		$query ='SELECT c.cnt, p.* FROM '.TABLE_WISH_DET.' c left join '.TABLE_PRODUCTS." p on p.id=c.prdid where c.userid='".$_GET['id']."' order by p.param_kodtovara";
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
	 <tr class="cli" title="По корзинам" <? echo(($row['enabled'] && $row['visible']) ? '' : 'style="background:#FFFF00"')?> onclick="prd_in_wish(this,'<? echo $row['id']?>')">
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
	case 'prdinwish':
	{
		$kl=$db->get(TABLE_PRODUCTS, array('id'=>trim($_GET['id'])));
		$buff .='Товар: '.$kl[0]['param_kodtovara'].' '.$kl[0]['title'];
		$query ='SELECT d.cnt, c.* FROM '.TABLE_WISH_DET.' d left join '.TABLE_WISH." c on c.userid=d.userid where d.prdid='".$_GET['id']."' order by c.date desc";
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
	  <td class="news_td lft cli" onclick="view_wish(this);"><?php echo $row['userid']?></td>
	  <td class="news_td lft"><?php echo date('d-m-y G:i', $row['date'])?></td>
	  <td class="highslide news_td lft cli" onclick="view_user(this);"><?=$row['userid']?></td>
	  <td class="news_td lft"><?php  echo $row['cnt']?></td>
	 </tr>
<?	}?>	 
</table>
<?	
		$buff .= ob_get_contents();
		ob_end_clean();
		break;
	}
	case 'clearwish':
	{
		$buff=$db->delete(TABLE_WISH_DET,['userid'=>$_GET['id']]);
		break;
	}
	
	default:
		break;
}
echo $buff;
?>