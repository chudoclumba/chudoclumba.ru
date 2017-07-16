
<?php
$module['scripts'] = MODULES_PATH.$global_place.'/wish.js';
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
		$sstr=" and (login LIKE '%".str_replace ("'","_",$schstr)."%' or info LIKE '%".str_replace("'","_",$schstr)."%' or pass LIKE '%".str_replace("'","_",$schstr)."%' or w.userid='".$schstr."')";	}

	$pdtlist2 = $db->query('SELECT sum(d.cnt) as cnt FROM '.TABLE_WISH.' w , '.TABLE_WISH_DET.' d, '.TABLE_USERS.' u where d.userid=w.userid and u.id=w.userid '.$sstr.' group by d.userid');
	$pg_count = $db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);

	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';


	$query ='SELECT w.*,u.*, sum(d.cnt) as cnt FROM '.TABLE_WISH.' w , '.TABLE_WISH_DET.' d, '.TABLE_USERS.' u where d.userid=w.userid and u.id=w.userid '.$sstr.' group by d.userid order by w.date desc '.$limit;
	if ($schstr>" ")
	{ $ee='&sch='.$schstr;}
	else
	{ $ee='';}
?>
	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>

<? 	echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.$ee.'&page=','info'=>$mes)).'<div class="clear"></div>';
	$q = $db->get_rows($query);
	echo show_wish($q);
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.$ee.'&page=','info'=>$mes));
$module['html'] .= ob_get_contents();
$module['path'] ='WishList';
$btns = array('WishList'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=wish\''),
	'Товары в WishList'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=wish&action=prd\''));
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
	$pdtlist2 = $db->query('select sum(c.cnt) as cnt,p.param_kodtovara, p.title from '.TABLE_WISH_DET.' c, '.TABLE_PRODUCTS.' p where p.id=c.prdid '.$sstr.' group by c.prdid');
	$pg_count = $db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);

	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';

	$query ='select sum(c.cnt) as cnt, p.param_kodtovara, p.title,p.id,p.enabled,p.visible from '.TABLE_WISH_DET.' c, '.TABLE_PRODUCTS.' p where p.id=c.prdid '.$sstr.' group by c.prdid order by cnt desc, p.param_kodtovara asc '.$limit;

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
$module['path'] ='Товары в WishList';
$btns = array('WishList'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=wish\''),
	'Товары в WishList'=>array('id'=>1,'href'=>'javascript:location.href=\'include.php?place=wish&action=prd\''));
ob_end_clean();
	
}
?>