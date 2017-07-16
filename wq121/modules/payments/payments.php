<?php
	$ds='';
	$tid=1;
	$act='';
	if(!empty($_GET['action'])) {
		if ($_GET['action']=='dec') {$ds=' c.declined=1 '; $tid=3;}
		if ($_GET['action']=='apr') {$ds=' c.approved=1 '; $tid=2;}
		if ($_GET['action']=='apru') {$ds=' c.approved=1 and c.uchet=0 '; $tid=4;}
		$act='&action='.$_GET['action'];
	} 

	if(!empty($_GET['sort'])) $_SESSION['psort']=$_GET['sort'];
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
		$sstr=" where (c.orderamount LIKE '%".str_replace ("'","_",$schstr)."%' or  o.fio LIKE '%".str_replace ("'","_",$schstr)."%') or c.orderid ='".$schstr."'";
	}
	$sort=' order by c.date desc ';
//	print_r($_SESSION);
	if (isset($_SESSION['psort']) && $_SESSION['psort']==1) $sort=' order by c.date desc ';
	if (isset($_SESSION['psort']) && $_SESSION['psort']==2) $sort=' order by c.orderid desc,c.date desc ';
	if (!empty($ds)){
		if (empty($sstr)) $sstr=' where '.$ds; else $sstr=' and '.$ds;
	}
	$pdtlist2 = $db->query("SELECT * FROM ".TABLE_PAYMENTS." c left join ".TABLE_ORDERS.' o on o.id=c.orderid '.$sstr);
	$pg_count =$db->num_rows($pdtlist2);
	$db->free_result($pdtlist2);
	$page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
	$lin = $sets['sus_lines'];
	$pages = ceil($pg_count/$lin);
	$limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
	$mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';

	$query ='SELECT c.*, o.fio FROM '.TABLE_PAYMENTS.' c left join '.TABLE_ORDERS.' o on o.id=c.orderid '.$sstr.$sort.$limit;
	$q = $db->get_rows($query);
	if ($schstr>" ") $ee='&sch='.$schstr;
	else $ee='';
?>
	<div class="search_pan">
	   <form method="post" action="<?php echo 'include.php?place='.$global_place?>">
		Поиск: <span style="color:red"><?php if ( !empty($schstr)) { echo htmlspecialchars(" ".$schstr." ",ENT_COMPAT | ENT_XHTML,'utf-8'); } ?></span> &nbsp;<input type="text" value="<?php echo htmlspecialchars($schstr,ENT_COMPAT | ENT_XHTML,'utf-8')?>" name="search_string">
	   </form>
	</div>

<? 	echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.$ee.$act.'&page=','info'=>$mes)).'<div class="clear"></div>';
	echo show_payments($q);
	echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place='.$global_place.$ee.$act.'&page=','info'=>$mes));
$module['scripts'] = MODULES_PATH.$global_place.'/payments.js';
$module['html'] .= ob_get_contents();
$module['path'] ='Платежи';
$ball=array('href'=>'javascript:location.href=\'include.php?place=payments\'');
if ($tid==1) $ball['id']=1;
$bapr=array('href'=>'javascript:location.href=\'include.php?place=payments&action=apr\'');
if ($tid==2) $bapr['id']=1;
$bdec=array('href'=>'javascript:location.href=\'include.php?place=payments&action=dec\'');
if ($tid==3) $bdec['id']=1;
$bapru=array('href'=>'javascript:location.href=\'include.php?place=payments&action=apru\'');
if ($tid==4) $bapru['id']=1;
$btns = array('Все'=>$ball,'Успешные'=>$bapr,'Отклоненные'=>$bdec,'Не в учете'=>$bapru);
ob_end_clean();

?>