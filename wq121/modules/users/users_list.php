<?
ob_start();
if(!empty($_GET['offID']))
{
	$db->update(TABLE_USERS, array('id'=>$_GET['offID']), array('block'=>1));
}
//print_r($_GET);
if(!empty($_GET['sort']))
{
	$_SESSION['usort']=$_GET['sort'];
}

if(!empty($_GET['onID']))
{
	$db->update(TABLE_USERS, array('id'=>$_GET['onID']), array('block'=>0));
}

if (!empty($_GET['dID']))
{
 $q = $db->delete(TABLE_USERS,['id'=>$_GET['dID']]);
}

$arr_types = array(
	'1' => 'на новости',
	'2' => 'на журналы'	
);
?>

<?php
if('Отправить сообщение' == 'on'){
?>
	<div style="padding:0px 0px 0px 10px"><?php echo button('Отправить сообщение', "javascript:gotourl('include.php?place=users&type=send')")?></div>
<?php
}	
?>	
<?if(empty($_GET['vID'])) 
{
	$schstr="";
	$sstr='';
	if (!empty($_POST['search_string']))
	{
		$schstr=$_POST['search_string'];
	}
	else
	{
		if (!empty($_GET['sch']) && $_GET['sch']>" ")
		{
			$schstr=$_GET['sch'];
		}
	}
	if ($schstr>" " and  ($schstr{0}=="№" or $schstr{0}=="#"))
	{
			$schstr=substr($schstr,1);
			$sstr=" where u.id = '".$schstr."'";
	}
	elseif ($schstr>" ")
	{
		$sstr=" where login LIKE '%".str_replace ("'","_",$schstr)."%' or info LIKE '%".str_replace("'","_",$schstr)."%' or pass LIKE '%".str_replace("'","_",$schstr)."%' or u.id='".$schstr."'";
	}

	$sort=' order by u.id desc ';
//	print_r($_SESSION);
	if (isset($_SESSION['usort']) && $_SESSION['usort']==1) $sort=' order by u.id desc ';
	if (isset($_SESSION['usort']) && $_SESSION['usort']==2) $sort=' order by u.datep desc ';
	if (isset($_SESSION['usort']) && $_SESSION['usort']==3) $sort=' order by sm desc ';

	$pdtlist2 = $db->query("SELECT id FROM ".TABLE_USERS.' u'.$sstr);
	$pg_count = $db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);

	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';


	$query ='SELECT u.id, u.data, u.datep, u.login, u.block, u.pass, u.info, sum(o.summa*(100-o.skidka)/100) as sm, count(o.summa) as cn FROM '.TABLE_USERS.' u left join '.TABLE_ORDERS.' o on u.id=o.user_id '.$sstr.' group by u.id'.$sort.$limit;

	$q = $db->get_rows($query);
	if ($schstr>" ")
	{ $ee='&sch='.$schstr;}
	else
	{ $ee='';}
?>

	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place.'&action=order'.((!empty($_GET['userid'])) ? '&userid='.$_GET['userid'] : '')?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>
<? 	$plist=get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.$ee.'&page=','info'=>$mes)).'<div class="clear"></div>';
	echo (empty($plist))?'<div style="height:20px"></div>':$plist;
	echo show_users($q);
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.$ee.'&page=','info'=>$mes));

	
	} else {
	
	$inf = $db->get(TABLE_USERS, $_GET['vID']);
	if (empty($inf)) {
		echo 'Клиент не найден.';
	} else {
		//Site::gI()->getRealOrderSumm("0");
        //TODO REAL ORDER SUM
$orders_sum = 0;
        $orders = $db->get_rows('select id as order_id from '.TABLE_ORDERS.' where status != 6 && user_id='.$_GET['vID']);
        if(!empty($orders))
        {
            foreach ($orders as $or_row)
            {
                $order_id = $or_row["order_id"];
                $orders_sum += Site::gI()->getRealOrderSumm($order_id);
            }
        }
//echo "ORDERS SUM = ".$orders_sum, '<br>';

	$infcart = $db->get_rows('SELECT c.id, c.date,c.datec, c.userid, c.ip, c.isset, c.scooc, sum(d.cnt) as cnt FROM '.TABLE_CART.' c left join '.TABLE_CART_DET.' d on d.cartid=c.id where c.userid='.$_GET['vID'].' group by c.id');
	$dop_rows = $db->get_rows('select * from '.TABLE_FEED.' where enabled=1 order by sort');
	$ord_inf = $db->get_rows('select sum(summa*(100-skidka)/100) as sm, count(summa) as cnt, sum(sumotgr) as so from '.TABLE_ORDERS.' where status != 6 && user_id='.$_GET['vID']);
	$infwish = $db->get_rows('SELECT c.date,c.datec, c.userid, c.isset, c.scooc, sum(d.cnt) as cnt FROM '.TABLE_WISH.' c left join '.TABLE_WISH_DET.' d on d.userid=c.userid where c.userid='.$_GET['vID'].' group by c.userid');
	$sk=0;
	if ($ord_inf['0']['sm']>0)
	{
		$ordsk = $db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$ord_inf['0']['sm']." && end > ".$ord_inf['0']['sm']);
		$sk=$ordsk[0]['percent'];
	}
	?> <div align="left"><form id="fuser" action="" enctype="multipart/form-data">

	<table cellspacing="0" cellpadding="0" width="100%">
	 <tr>
	  <td class="news_td" style="padding-right:20px;">Дата регистрации</td>
	  <td class="news_td"><?echo ($inf['data']>0 ? date('d-m-y, G:i', $inf['data']) : '&nbsp;')?></td>
	  <td class="news_td" style="padding-right:20px;">Дата последнего посещения</td>
	  <td class="news_td"><?echo ($inf['datep']>0 ? date('d-m-y, G:i', $inf['datep']) : '&nbsp;')?></td>
	 </tr>
	 <tr>
	  <td class="news_td" colspan="4">E-mail <input id="fum" class="fbinp" readonly="readonly" style="width:300px" type="text" name="k_mail" value="<?php  echo $inf['login']?>"/>
	  Пароль <input class="fbinp" readonly="readonly" type="text" style="width:150px" name="k_pass" value="<?php  echo $inf['pass']?>"/></td><input type="hidden" name="k_id" value="<?=$_GET['vID']?>"/>
	 </tr>
	<?
		foreach($dop_rows as $id=>$val)
		{
			if($val['id'] != 3 && $val['id'] != 4 && $val['id'] != 16 && $val['id'] != 10)
			{
				$opts = array();
				if(!empty($val['options']))
				{
					$lt = explode(';',$val['options']);
					foreach($lt as $o_id=>$o_val)
					{
						$lt2 = explode(':', $o_val);
						if(!empty($lt2['1']))
						{
							$opts[$lt2['0']] = $lt2['1'];
						}
					}
				}
				$inf_dop = unserialize($inf['info']);
				$v = $inf_dop[$val['id']];
				if ($val['id']==21){
					$v=$db->count(TABLE_PODPISKA,array('email'=>$inf['login']));
				}

				if($val['type'] == 'checkbox')
				{
					echo '
					 <tr>
					  <td class="news_td" colspan="4"><label><input disabled="disabled"'.(($v==1)?' checked="checked"':'').' name="p'.$val['id'].'" type="checkbox" />'.$val['name'].'</label></td>
					 </tr>';
				}
				elseif($val['type'] == 'textarea')
				{
					echo '
					 <tr>
					  <td  class="news_td">'.$val['name'].'</td>
					  <td  class="news_td" colspan="3" ><textarea readonly="readonly" class="fbinp" style="width:300px; height:50px;" name="p'.$val['id'].'" id="p'.$val['id'].'"/>'.$v.'</textarea></td>
					 </tr>';
				}
				elseif($val['type'] == 'select')
				{
					echo '
					 <tr>
					  <td  class="news_td">'.$val['name'].'</td>
					  <td  class="news_td" colspan="3"><select readonly="readonly" name="p'.$val['id'].'">';
					$list = explode(',', $opts['list']);
					foreach($list as $l_id=>$l_val)
					{
						$chk = ($v == $l_val) ? ' selected="selected"' : '';
						echo '<option'.$chk.' value="'.$l_val.'">'.$l_val.'</option>';
					}
					  echo '</select></td>
					 </tr>';
				}
				else
				{
					$lng = (!empty($opts['tw'])) ? ' maxlength="'.$opts['tw'].'"' : '';
					echo '
					 <tr>
					  <td  class="news_td">'.$val['name'].'</td>
					  <td  class="news_td" colspan="3"><input'.$lng.'  readonly="readonly" class="fbinp" type="text" value="'.htmlspecialchars($v,ENT_COMPAT | ENT_XHTML,'utf-8').'" style="width:200px;" name="p'.$val['id'].'"  id="p'.$val['id'].'"/></td>
					 </tr>';

				}
			}
		}?>
	<script type="text/javascript">
		$(function(){
		$("#p14").mask("+7 (999) 999-9999");
		$("#p15").mask("+7 (999) 999-9999");
		});
	</script>
 <tr>
	  <td class="news_td cli" style="padding-right:20px;" onclick="view_userorders(this,<?=$_GET['vID']?>);">Заказов</td>
	  <td class="news_td"><?php  echo $ord_inf[0]['cnt']?></td>
	  <td class="news_td" style="padding-right:20px;">На сумму</td>
	  <td class="news_td"><?php  echo number_format($orders_sum,2,'.','')?></td>
	 </tr>
	  <tr>
	  <td class="news_td" style="padding-right:20px;">Отгрузка</td>
	  <td class="news_td"><?php  echo $ord_inf[0]['so']?></td>
	  <td class="news_td" style="padding-right:20px;">Скидка накоп.</td>
	  <td class="news_td"><?php  echo $sk?></td>
	 </tr>
	 <tr>
	  <td class="news_td" style="padding-right:20px;">Скидка персон.</td>
	  <td class="news_td" colspan="2"><input class="fbinp" readonly="readonly" type="text" name="k_sale" value="<?=$inf['sale']?>"/></td>
	  <td class="news_td"><?php echo button('Редактировать',"SaveUser(this);")?></td>
	 </tr>
	 <? if (count($infcart)>0 && $infcart[0]['id']>' '){ ?>
	 <tr>
	  <td class="highslide news_td cli" colspan="4" onclick="view_cart(this,'<?=$infcart[0]['id']?>');">Корзина <?=$infcart[0]['id']?><?echo ($infcart[0]['date']>0 ? date(' Обновлена d-m-y, G:i',$infcart[0]['date']) : '&nbsp;').' Товаров '; echo ($infcart[0]['cnt']>0 ? $infcart[0]['cnt'] : ' нет.');?></td>
	 </tr>
	 <?}?>
	 <? if (count($infwish)>0){ ?>
	 <tr>
	  <td class="highslide news_td cli" colspan="4" onclick="view_wish(this,'<?=$_GET['vID']?>');">WishList <?echo ($infwish[0]['date']>0 ? date(' Обновлен d-m-y, G:i',$infwish[0]['date']) : '&nbsp;').' Товаров '; echo ($infwish[0]['cnt']>0 ? $infwish[0]['cnt'] : ' нет.');?></td>
	 </tr>
	 <?}?>

	</table>
	</form>
	</div>
	<?}}?>
<?php

$module['html'] .= ob_get_contents();
ob_end_clean();
if (!empty($_GET['ajax']))
{
	echo $module['html'];
	exit;
}