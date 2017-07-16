
<?php
$module['scripts'] = MODULES_PATH.$global_place.'/cart.js';
if(empty($_GET['action'])) 
{
	ob_start();
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
	if ($schstr>" ")
	{
		$sstr=" and (c.id LIKE '%".str_replace ("'","_",$schstr)."%' or c.ip LIKE '%".str_replace ("'","_",$schstr)."%' or c.userid='".$schstr."')";
	}

	$pdtlist2 = $db->query('SELECT c.id, c.date,c.datec, c.userid, c.ip, c.isset, c.scooc, sum(d.cnt) as cnt FROM '.TABLE_CART.' c , '.TABLE_CART_DET.' d where d.cartid=c.id '.$sstr.' group by d.cartid');
	$pg_count=$db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);

	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';

	$query ='SELECT c.id, c.date,c.datec, c.userid, c.ip, c.isset, c.scooc, sum(d.cnt) as cnt FROM '.TABLE_CART.' c , '.TABLE_CART_DET.' d where d.cartid=c.id '.$sstr.' group by d.cartid order by c.date desc '.$limit;
	if ($schstr>" ")
	{ $ee='&sch='.$schstr;}
	else
	{ $ee='';}
?>
	<div id="dialog" title="Dialog Title"></div>
	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>

<? 	echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.$ee.'&page=','info'=>$mes)).'<div class="clear"></div>
';
	$q = $db->get_rows($query);
	echo show_cart($q);
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.$ee.'&page=','info'=>$mes));
$module['html'] .= ob_get_contents();
$module['path'] ='Корзины';
$btns = array('Корзины'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=cart\''),
	'Товары в корзинах'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=cart&action=prd\''));
ob_end_clean();
}
if (isset($_GET['action']) && $_GET['action']=='prd')
{
	ob_start();
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
	if ($schstr>" ")
	{
		$sstr=" and p.param_kodtovara LIKE '%".str_replace ("'","_",$schstr)."%' or p.title LIKE '%".str_replace ("'","_",$schstr)."%'";
	}
	$pdtlist2 = $db->query('select sum(c.cnt) as cnt,p.param_kodtovara, p.title from '.TABLE_CART_DET.' c, '.TABLE_PRODUCTS.' p where p.id=c.prdid '.$sstr.' group by c.prdid');
	$pg_count=$db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);

	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';

	$query ='select sum(c.cnt) as cnt, p.param_kodtovara, p.title,p.id,p.enabled,p.visible from '.TABLE_CART_DET.' c, '.TABLE_PRODUCTS.' p where p.id=c.prdid '.$sstr.' group by c.prdid order by cnt desc, p.param_kodtovara asc '.$limit;
	if ($schstr>" ")
	{ $ee='&sch='.$schstr;}
	else
	{ $ee='';}
?>
	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place.'&action=prd'?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>
<? 	echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.'&action=prd'.$ee.'&page=','info'=>$mes)).'<div class="clear"></div>';
	$q = $db->get_rows($query);
	echo show_prdinw($q);
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.'&action=prd'.$ee.'&page=','info'=>$mes));
$module['html'] .= ob_get_contents();
$module['path'] ='Товары в корзинах';
$btns = array('Корзины'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=cart\''),
	'Товары в корзинах'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=cart&action=prd\''));
ob_end_clean();
	
}
?>