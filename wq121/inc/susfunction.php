<?php

	function fn_write($fp, $str){
		gzwrite($fp, $str);
	}
	function backup_tables($tabl_arr,$file_name){
		global $db;
		$result = $db->query("SET SQL_QUOTE_SHOW_CREATE = 1");
		$tabstat=$db->get_rows("SHOW TABLE STATUS");
		foreach($tabstat as $key => $val){
			$tabsize[$val['Name']] = 1 + round(1 * 1048576 / ($val['Avg_row_length'] + 1));
			$tabinfo[$val['Name']] = $val['Rows'];
		}
		$fp=gzopen($file_name, "wb9");
		foreach($tabl_arr AS $table){
			$result = $db->query("SHOW CREATE TABLE `{$table}`");
			$tab = $db->fetch_array($result);
			$tab = preg_replace('/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+)/i', '', $tab);
			fn_write($fp, "DROP TABLE IF EXISTS `{$table}`;\n{$tab[1]};\n\n");
			$NumericColumn = array();
			$result = $db->query("SHOW COLUMNS FROM `{$table}`");
			$field = 0;
			while($col = $db->fetch_array($result,PDO::FETCH_NUM)){
				$NumericColumn[$field++] = preg_match("/^(\w*int|year)/", $col[1]) ? 1 : 0;
			}
			$fields = $field;
			$from = 0;
			$limit = 15;
			if($tabinfo[$table] > 0){
				$i = 0;
				$t=0;
				fn_write($fp, "INSERT INTO `{$table}` VALUES\n");
				$result = $db->query("SELECT * FROM {$table}");
				$cnt=$db->num_rows($result);
				while($row = $db->fetch_array($result,PDO::FETCH_NUM)){
					$i++;
					$t++;

					for($k = 0; $k < $fields; $k++){
						if($NumericColumn[$k])
						$row[$k] = isset($row[$k]) ? $row[$k] : "NULL";
						else
						$row[$k] = isset($row[$k]) ? $db->escape_string($row[$k]) : "NULL";
					}
					$twr="(" . implode(", ", $row) . ")";
					if($t<$cnt && $i<15){
						$twr.=",\n";
					} elseif($t<$cnt){
						$i=0;
						$twr.=";\n\nINSERT INTO `{$table}` VALUES\n";
					} else{
						$twr.=";\n\n";
					}
					fn_write($fp, $twr);
				}

				$db->free_result($result);
			}
		}
		gzclose($fp);
		return true;
	}

	function get_stattovday($dt){
		//	if ($dt==0) $dt=mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
		$dt1=$dt+24*60*60;
		$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%" class="selstr"><tbody><tr><th class="news_header lft">Код</th><th class="news_header lft">Наименование</th><th class="news_header cen">Кол-во</th><th class="news_header rgt">Сумма</th></tr>';
		global $db;
		$qy = $db->get_rows("select sum((o.summa-o.summa*o.skidka/100) * o.count ) as sm, sum(o.count) as cnt,p.title, p.param_kodtovara,o.prd_id from chudo_ishop_order_idet o ,chudo_ishop_products p, chudo_ishop_order_i c where c.data>=".$dt." and c.data<".$dt1." and o.order_id=c.id and p.id=o.prd_id group by o.prd_id order by p.param_kodtovara");
		foreach($qy as $id=>$row){
			$cnt.='<tr><td class="news_td lft">'.$row['param_kodtovara'].'</td><td class="news_td lft cli"  onclick="view_statdt(this,'.$dt.','.$row['prd_id'].')">'.$row['title'].'</td><td class="news_td cen">'.$row['cnt'].'</td><td class="news_td rgt">'.number_format($row['sm'],2,'.',"").'</td></tr>';
		}
		$cnt.='</tbody></table><!--'.$dt.'-->';
		return $cnt;
	}
function get_grrec($gr)
{
	global $db;
	$ret=array();
	$cat = $db->get_rows("SELECT id,recpar,parent_id from ".TABLE_CATEGORIES." where id=".quote_smart($gr));
	$prd = $db->get_rows("SELECT r.prdid from ".TABLE_GRREC." r, ".TABLE_PRODUCTS." p where p.id=r.prdid && p.enabled=1 && p.visible=1 && r.grid=".$cat[0]['id']);
	foreach($prd as $id=>$rprd) {
		$ret[]=$rprd['prdid'];
	} 
	if ($cat[0]['parent_id']>0 && $cat[0]['recpar']>0) $ret=array_merge($ret,get_grrec($cat[0]['parent_id']));
	return $ret;
}

function get_streesubcat($cat,$lvl=1)
{
	$ret=array();
	global $db;
	$rows = $db->get_rows("SELECT * from ".TABLE_CATEGORIES." where parent_id=".$cat." ORDER BY enabled desc,visible desc, sort, title");
	foreach($rows as $id=>$val)
	{
		$ret[]=array($val['id']=>array_merge($val,array('level'=>$lvl)));
//		$str.='<p>'.$lvl.' '.$val['id'].'</p>';	
//		if (get_scatcnt($val['id'])>0) $ret=array_merge($ret,get_streesubcat($val['id'],$lvl+1));
	    $ret=array_merge($ret,get_streesubcat($val['id'],$lvl+1));
	}
	return $ret;
}

function get_newtreesubcat($cat,$lvl=1,$opt='disable_controls',$no='tre0')
{
	global $db;
	$ret=Get_StreeSubCat($cat,$lvl=1);
	$module['html'] = '';
	$renable=array();
	$oldlevel=0;
	$ncnt=!(strpos($opt,'nocnt')===false);
	$shvl=!(strpos($opt,'vlink')===false);
	$out='<div class="Tree'.((!(strpos($opt,'disable_controls')===false))?' nctrl':'').'" onclick="tree_toggle(arguments[0],\''.$no.'\');" id="'.$no.'">
	';
	foreach($ret as $id=>$val){
		$tid=array_keys($val);
		$res=array_values($val);
		$tlev=array($res[0]['level']);
//		$res = $db->get_rows("select * from ".TABLE_CATEGORIES." where id = ".$tid[0]."");
		$link_id = $res[0]['id'];
		$prdc=get_prdcnt($link_id);
		$rprd=get_grrec($link_id);
		$prdc_t='';
		if ($prdc>0){
			$prdc_t.="&nbsp;&nbsp;({$prdc}/<span style=\"color:green\">".get_prdencnt($link_id)."</span>)";
		}
		$sty=(($tlev[0]==1)?' TRR':''); 
		if(!($oldlevel==$tlev[0])){
			if($tlev[0]>$oldlevel){
				$out.='<ul class="Tcc">';
				$out=str_replace('TEl'.$oldlevel,'TEc',$out);
			}
			else
			{
				for($i = $oldlevel ; $i>$tlev[0]; $i--){
					$out.='</li></ul>';
 					$out=str_replace('IsLast'.$i,'IsLast',$out);
				}
				$out.='</li>';
				$out=str_replace(' IsLast'.$tlev[0],'',$out);
				$out=str_replace('TEl'.$oldlevel,'TEl',$out);
			}
			$oldlevel=$tlev[0];
		}
		else{
			if ($oldlevel>0) $out.='</li>';
			$out=str_replace('TEl'.$oldlevel,'TEl',$out);
			$out=str_replace(' IsLast'.$tlev[0],'',$out);
		}
		$sty=(($tlev[0]==1)?' TRR':''); 
		$sty=' IsLast'.$tlev[0].$sty;
		$sty=' TEl'.$tlev[0].$sty;
		$sty.=(($res[0]['enabled']!=1)?' edis':'').(($res[0]['visible']!=1)?' eunv':'');
		$cnts='';
		if (!$ncnt){
			$cnts=(($res[0]['cnt']>0)?'<div class="TrcT" title="Открытых товаров">'.$res[0]['cnt'].'</div>':'<div class="TrcN"></div>').((count($rprd)>0)?'<div class="TrcT TrcR trec" title="Рекомендованных товаров">'.count($rprd).'</div>':'<div class="TrcN trec"></div>');
		}
		$vlks='';
		if ($shvl){
			$vlks=((!empty($res[0]['vlink']))?'<div class="TrcT1" title="ЧПУ ссылка">'.$res[0]['vlink'].'</div>':'<div class="TrcN"></div>');
		}
		$out.='<li id='.$link_id.' class="'.$sty.'"><div class="TRe"></div>'.
		((!(strpos($opt,'disable_controls')===false))?'':'<div class="enb"></div><div class="vsb"></div>').
		'<div class="TrcN">'.$res[0]['id'].'</div>'.$cnts.$vlks.
		'<div class="Trc'.(($res[0]['onprom']==1)?' TrPr':'').'">'.htmlspecialchars($res[0]['title'],ENT_COMPAT | ENT_XHTML,'utf-8').$prdc_t.'</div>';
	}
	$tlev[0]=0;
	if(!($oldlevel==$tlev[0])){
		if($tlev[0]>$oldlevel){
			$out.='<ul class="Tcc">';
			$out=str_replace('TEl'.$oldlevel,'TEc',$out);
		}
		else
		{
			for($i = $oldlevel ; $i>$tlev[0]; $i--){
				$out.='</li></ul>';
				$out=str_replace('IsLast'.$i,'IsLast',$out);
			}
			$out=str_replace('TEl'.$oldlevel,'TEl',$out);
		}
		$oldlevel=$tlev[0];
	}
	else{
		$out=str_replace('TEl'.$oldlevel,'TEl',$out);
		$out=str_replace(' IsLast'.$tlev[0],'',$out);
	}
	$out.='</div>
	';
	$module['html'] = $out;
	return $module['html'];
	//	return $max;
}

function ModuleExists($str)
{
	return file_exists(MODULES_PATH.$str.'/'.$str.'.php');
}

function array_del_empty($myarr)
{
	foreach ($myarr as $key => $value)
	{
		if (is_null($value) || $value=="")
		{
			unset($myarr[$key]);
		}
	}
	return $myarr;
}
function conv_str($str)
{
	$str1=str_replace('"""','"',$str);
	$str1=str_replace('""','"',$str1);
	$str1=str_replace('"','""',$str1);
	$str1=str_replace("\r\n","\n",$str1);
//	$str1=str_replace("\r",'\r',$str1);
	return $str1;
}


function GetOnOff($title,$param)
{
	global $sets;
	return GetForm('',$title,'<input type="checkbox" class="checkbox" id="ch_'.$param.'" '.(($sets[$param] == 1) ? 'checked="checked"' : '').'"/>  
<label onclick="set_param(\''.$param.'\', this);" for="checkbox">'.(($sets[$param] == 1) ? 'Включено' : 'Выключено').'</label>','','');
}

function GetDown($title)
{
	global $sets;
	return GetForm('',$title,'','','<input onclick="location.href=\'backup_sql.php\'" type="button" style="background:#fff" class="button1" value="Скачать" />');
}

function GetUpl($title)
{
	global $sets;
	return GetForm('',$title,'','','<input onclick="alert(123)" type="button" style="background:#fff" class="button1" value="Загрузить" />');
}


function substr2($value1,$value2)
{
	if(strlen($value1) > $value2)
	{
		$value1 = substr($value1,0,$value2).'...';
	}
	return $value1;
}

function get_mat_list($type, $curr = 0, $dinf = array())
{
	global $db;

	$materials = $db->get_rows("SELECT id, name FROM ".TABLE_MATERIAL."");

	if($type == 1)
	{
		$cnt = '<select name="value[\' + (i-1) + \'][\' + (count_col-1) + \']">';
	}
	if($type == 3)
	{
		$cnt = '<select name="value['.$dinf['g'].']['.$dinf['i'].']">';
	}

	else
	{
		$cnt = '<select name="value[\' + g + \'][\' + i + \']">';
	}

	foreach($materials as $id=>$material)
	{
		$chk = (!empty($curr) && $material['id'] == $curr) ? ' selected="selected"' : '';
		$cnt .= '<option'.$chk.' value="'.$material['id'].'">'.htmlspecialchars($material['name'],ENT_COMPAT | ENT_XHTML,'utf-8').'</option>';
	}

	$cnt .= '</select>';
	return $cnt;
}
function day_of_w($day)
{
	$ar=array(
	"1"=>"Пн",
	"2"=>"Вт",
	"3"=>"Ср",
	"4"=>"Чт",
	"5"=>"Пт",
	"6"=>"Сб",
	"7"=>"Вс");
	return $ar[$day];
}

function get_statcarttoday()
{	
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">Корзин</th><th class="news_header cen">Товаров</th><th class="news_header rgt">Сумма</th></tr>';
	global $db;
	$qy = $db->get_rows("select count(distinct c.id) as idcnt,sum((p.tsena-p.tsena*p.skidka/100) * d.cnt ) as sm, sum(d.cnt) as cnt from chudo_cart c ,chudo_cart_det d, chudo_ishop_products p where c.date>=UNIX_TIMESTAMP(curdate()) and d.cartid=c.id and p.id=d.prdid");
	$cnt.='<tr><td class="news_td lft">'.$qy[0]['idcnt'].'</td><td class="news_td cen">'.$qy[0]['cnt'].'</td><td class="news_td rgt">'.number_format($qy[0]['sm'],0,'.',"'").'</td></tr>';
	$cnt.='</tbody></table>';
	return $cnt;
}

function get_reguser24h()
{	
	$cnt='';
	global $db;
	$qy = $db->get_rows("select count(c.id) as idcnt from chudo_users c where c.datep>=UNIX_TIMESTAMP(now())-24*60*60 and c.data<UNIX_TIMESTAMP(now())-24*60*60");
	if (count($qy)>0) $cnt.='Посещений Старых пользователей: '.$qy[0]['idcnt'].'</br>';
	$qy = $db->get_rows("select count(c.id) as idcnt from chudo_users c where c.data>=UNIX_TIMESTAMP(now())-24*60*60");
	if (count($qy)>0) $cnt.='Регистраций пользователей: '.$qy[0]['idcnt'].'</br>';
	return $cnt;
}

function get_statcart24h()
{	
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">Корзин</th><th class="news_header rgt">Товаров</th><th class="news_header rgt">Сумма</th></tr>';
	global $db;
	$qy = $db->get_rows("select count(distinct c.id) as idcnt,sum((p.tsena-p.tsena*p.skidka/100) * d.cnt ) as sm, sum(d.cnt) as cnt from chudo_cart c ,chudo_cart_det d, chudo_ishop_products p where c.date>=UNIX_TIMESTAMP(now())-24*60*60 and d.cartid=c.id and p.id=d.prdid");
	$cnt.='<tr><td class="news_td lft">'.$qy[0]['idcnt'].'</td><td class="news_td rgt">'.$qy[0]['cnt'].'</td><td class="news_td rgt">'.number_format($qy[0]['sm'],0,'.',"'").'</td></tr>';
	$cnt.='</tbody></table>';
	return $cnt;
}

function get_statnd($days=0)
{	
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">Дата</th><th class="news_header rgt">Кол-во</th><th class="news_header rgt">Сумма</th></tr>';
	global $db;
	$qy = $db->get_rows("select count(id) as cnt,sum(summa-summa*skidka/100) as sum, data,to_days(FROM_UNIXTIME(data)) as ds from chudo_ishop_order_i where data>=UNIX_TIMESTAMP(curdate())-".$days."*24*60*60  group by ds order by ds desc");
	$sum=0;
	$csum=0;
	foreach($qy as $id=>$row)
	{
		$cl=(date('N, ', $row['data'])==7 || date('N, ', $row['data'])==6)?' class="vyh"':'';
		$sum=$sum+$row['sum'];
		$csum=$csum+$row['cnt'];
		if ($days>0) $cnt.='<tr'.$cl.'><td class="news_td lft">'.date('d-m-y, ', $row['data']).day_of_w(date('N', $row['data'])).'</td><td class="news_td rgt">'.$row['cnt'].''.'</td><td class="news_td rgt">'.number_format($row['sum'],0,'.',"'").'</td></tr>';
	}
	$cnt.='<tr><th class="news_td lft">Итого</th><th class="news_td rgt">'.$csum.'</th><th class="news_td rgt">'.number_format($sum,0,'.',"'").'</th></tr>';
	if ($days>2) {
		$ag=($sum-$qy[0]['sum'])/(count($qy)-1);
		$cnt.='<tr><th class="news_td lft" colspan="3">Cреднесуточная сумма: '.number_format($ag,0,'.',"'").'</th></tr>';
	}
	$cnt.='</tbody></table>';
	return $cnt;
}

function get_stath($hrs=24)
{	
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">Дата</th><th class="news_header rgt">Кол-во</th><th class="news_header rgt">Сумма</th></tr>';
	global $db;
	$qy = $db->get_rows("select count(id) as cnt,sum(summa-summa*skidka/100) as sum, data,to_days(FROM_UNIXTIME(data)) as ds from chudo_ishop_order_i where data>=UNIX_TIMESTAMP(now())-".$hrs."*60*60  group by ds order by ds desc");
	$sum=0;
	$csum=0;
	foreach($qy as $id=>$row)
	{
		$sum=$sum+$row['sum'];
		$csum=$csum+$row['cnt'];
//		$cnt.='<tr><td class="news_td lft">'.date('d-m-y, ', $row['data']).day_of_w(date('N', $row['data'])).'</td><td class="news_td cen">'.$row['cnt'].''.'</td><td class="news_td rgt">'.number_format($row['sum'],2,'.',' ').'</td></tr>';
	}
	$cnt.='<tr><th class="news_td lft">Итого</th><th class="news_td rgt">'.$csum.'</th><th class="news_td rgt">'.number_format($sum,0,'.',"'").'</th></tr></tbody></table>';
	return $cnt;
}

function get_Top20prd()
{	
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">Наименование</th><th class="news_header rgt">Кол-во</th></tr>';
	global $db;
	$qy = $db->get_rows("select param_kodtovara,title,views from chudo_ishop_products order by views desc LIMIT 20");
	foreach($qy as $id=>$row)
	{
		$cnt.='<tr><td class="news_td lft" title="'.$row['param_kodtovara'].'">'.$row['title'].'</td><td class="news_td rgt">'.$row['views'].'</td></tr>';
	}
	$cnt.='</tbody></table>';
	return $cnt;
}

function get_Top20prdcrt()
{	
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">Наименование</th><th class="news_header rgt">Кол-во</th><th class="news_header rgt">Просм.</th></tr>';
	global $db;
	$qy = $db->get_rows("select sum(c.cnt) as cnt, p.param_kodtovara,p.title,p.views from chudo_cart_det c join chudo_cart cc on cc.id=c.cartid left join chudo_ishop_products p on p.id=c.prdid  where cc.date>=UNIX_TIMESTAMP(now())-15*24*60*60  group by c.prdid order by cnt desc, p.param_kodtovara asc LIMIT 20");
	foreach($qy as $id=>$row)
	{
		$cnt.='<tr><td class="news_td lft" title="'.$row['param_kodtovara'].'">'.$row['title'].'</td><td class="news_td rgt">'.$row['cnt'].'</td><td class="news_td rgt">'.$row['views'].'</td></tr>';
	}
	$cnt.='</tbody></table>';
	return $cnt;
}

function get_statm()
{	
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">Дата</th><th class="news_header rgt">Кол-во</th><th class="news_header rgt">Сумма</th><th class="news_header rgt">Assist</th><th class="news_header rgt">Сумма Ass</th></tr>';
	global $db;
	$qya = $db->get_rows("select count(orderid) as cnt,sum(orderamount) as sum, date,to_days(FROM_UNIXTIME(date)) as ds from chudo_payments where date>=UNIX_TIMESTAMP(curdate())-(DAYOFMONTH(curdate())-1)*24*60*60 and approved=1 group by ds order by ds desc");
	$qy = $db->get_rows("select count(id) as cnt,sum(summa-summa*skidka/100) as sum, data,to_days(FROM_UNIXTIME(data)) as ds from chudo_ishop_order_i where data>=UNIX_TIMESTAMP(curdate())-(DAYOFMONTH(curdate())-1)*24*60*60 group by ds order by ds desc");
	$sum=0;
	$csum=0;
	$suma=0;
	$csuma=0;
	foreach($qya as $ida=>$rowa){
		$suma=$suma+$rowa['sum'];
		$csuma=$csuma+$rowa['cnt'];	
		foreach ($qy as $id=>$row){
			if ($row['ds']==$rowa['ds']){
				$row['suma']=$rowa['sum'];
				$row['cnta']=$rowa['cnt'];
				$qy[$id]=$row;
				break;
			} 
		}
	}
	foreach($qy as $id=>$row)
	{
		$sum=$sum+$row['sum'];
		$csum=$csum+$row['cnt'];
		$cl=(date('N, ', $row['data'])==7 || date('N, ', $row['data'])==6)?' class="vyh"':'';
		$cnt.='<tr onclick="view_statd(this,'."'".date('d-m-y', $row['data'])."'".');" '.$cl.'><td class="news_td lft cli">'.date('d-m-y, ', $row['data']).day_of_w(date('N', $row['data'])).'</td><td class="news_td rgt">'.$row['cnt'].''.'</td><td class="news_td rgt">'.number_format($row['sum'],0,'.',"'").'</td><td class="news_td rgt">'.$row['cnta'].''.'</td><td class="news_td rgt">'.number_format($row['suma'],0,'.',"'").'</td></tr>';
	}
	$cnt.='<tr><th class="news_td lft">Итого</th><th class="news_td rgt">'.$csum.'</th><th class="news_td rgt">'.number_format($sum,0,'.',"'").'</th><th class="news_td rgt">'.$csuma.'</th><th class="news_td rgt">'.number_format($suma,0,'.',"'").'</th></tr>';
	$ag=($sum-$qy[0]['sum'])/(count($qy)-1);
	$aga=($suma-$qya[0]['sum'])/(count($qya)-1);
	if (count($qy)>1) $cnt.='<tr><th class="news_td lft" colspan="5">Cреднесуточная сумма: '.number_format($ag,0,'.',"'").'</th></tr>';
	$cnt.='</tbody></table>';
	return $cnt;
}

function get_stat()
{	
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">Дата</th>
	<th class="news_header rgt">Заказов</th>
	<th class="news_header rgt">Клиентов</th>
	<th class="news_header rgt">Новых кл.</th>
	<th class="news_header rgt">Сумма пр</th>
	<th class="news_header rgt">Сумма обр</th>
	<th class="news_header rgt">Сумма нов</th>
	</tr>';
	global $db;
	$qyo = $db->get_rows("select count(id) as cnto,sum(summa-summa*skidka/100) as sumo, data,extract(YEAR_MONTH from FROM_UNIXTIME(data)) as dto from chudo_ishop_order group by dto order by dto desc");
	$qynew = $db->get_rows("select count(t.id) as cnt,sum(t.summa-t.summa*t.skidka/100) as sum, t.data,extract(YEAR_MONTH from FROM_UNIXTIME(t.data)) as dt,t.email from chudo_ishop_order_i t where t.email not in (select email from chudo_ishop_order_i b where b.data<UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(t.data),'%Y-%m-01'))) group by email,extract(YEAR_MONTH from FROM_UNIXTIME(t.data)) order by extract(YEAR_MONTH from FROM_UNIXTIME(t.data)) desc");
	$qyn = $db->get_rows("select count(id) as cntn,sum(summa-summa*skidka/100) as sumn, data,extract(YEAR_MONTH from FROM_UNIXTIME(data)) as dtn from chudo_ishop_order_i group by email,dtn order by dtn desc");
	$tper=$qyn[0]['dtn'];
	$sumper=0;
	$cntuper=0;
	$cntper=0;
	$sum=0;
	$csum=0;
	$ucnt=0;
	foreach($qyn as $id=>$row)
	{
		if (!($tper===$row['dtn']))
		{
			$cnt.='<tr><td class="news_td lft">'.substr($tper,4,2).'-'.substr($tper,2,2).'</td><td class="news_td rgt">'.
				$cntper.'</td><td class="news_td rgt">'.$cntuper.'</td><td class="news_td rgt">%newc'.$tper.
				'%</td><td class="news_td rgt">'.number_format($sumper,0,'.',"'").'</td><td class="news_td rgt">%sumo'.
				$tper.'%</td><td class="news_td rgt">%sumnu'.$tper.'%</td></tr>';
			$tper=$row['dtn'];
			$sumper=0;
			$cntuper=0;
			$cntper=0;
		}
		$cntuper=$cntuper+1;
		$cntper=$cntper+$row['cntn'];
		$sumper=$sumper+$row['sumn'];
		$sum=$sum+$row['sumn'];
		$csum=$csum+$row['cntn'];
		$ucnt++;
	}
	$cnt.='<tr><td class="news_td lft">'.substr($tper,4,2).'-'.substr($tper,2,2).'</td><td class="news_td rgt">'.
				$cntper.'</td><td class="news_td rgt">'.$cntuper.'</td><td class="news_td rgt">%newc'.$tper.
				'%</td><td class="news_td rgt">'.number_format($sumper,0,'.',"'").'</td><td class="news_td rgt">%sumo'.
				$tper.'%</td><td class="news_td rgt">%sumnu'.$tper.'%</td></tr>';
	$sumo=0;
	foreach($qyo as $id=>$row)
	{
		$cnt=str_replace('%sumo'.$row['dto'].'%',number_format($row['sumo'],0,'.',"'"),$cnt);
		$sumo+=$row['sumo'];
	}

	$tper=$qynew[0]['dt'];
	$sumper=0;
	$cntuper=0;
	$cntper=0;
	$sumnu=0;
	$csumnu=0;
	foreach($qynew as $id=>$row)
	{
		if (!($tper===$row['dt']))
		{
			$cnt=str_replace('%sumnu'.$tper.'%',number_format($sumper,0,'.',"'"),$cnt);
			$cnt=str_replace('%newc'.$tper.'%',number_format($cntuper,0,'.',"'"),$cnt);
			$tper=$row['dt'];
			$sumper=0;
			$cntuper=0;
			$cntper=0;
		}
		$cntuper=$cntuper+1;
		$cntper=$cntper+$row['cnt'];
		$sumper=$sumper+$row['sum'];
		$sumnu=$sumnu+$row['sum'];
		$csumnu=$csumnu+$row['cnt'];
	}
	$cnt=str_replace('%sumnu'.$tper.'%',number_format($sumper,0,'.',"'"),$cnt);
	$cnt=str_replace('%newc'.$tper.'%',number_format($cntuper,0,'.',"'"),$cnt);
	$cnt.='<tr><th class="news_td lft">Итого</th><th class="news_td rgt">'.$csum.'</th><th class="news_td rgt">'.$ucnt.'</th>
	<th class="news_td rgt"></th><th class="news_td rgt">'.number_format($sum,0,'.',"'").'</th><th class="news_td rgt">'.number_format($sumo,0,'.',"'").'</th><th class="news_td rgt"></th></tr>';
	
//	$ag=($sum-$qyo[0]['sum'])/(count($qyo)-1);
//	if (count($qy)>1) $cnt.='<tr><th class="news_td lft" colspan="3">Cреднесуточная сумма: '.number_format($ag,2,'.',' ').'</th></tr>';
	$cnt.='</tbody></table>';
	return $cnt;
}
function get_stat_u()
{	
	global $db;
	$cnt='<table align="center" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><th class="news_header lft">ФИО</th><th class="news_header rgt">Кол-во</th><th class="news_header rgt">Сумма</th></tr>';
	$query ='SELECT u.id, u.data, u.info, sum(o.summa-o.summa*o.skidka/100) as sm, count(o.summa) as cn FROM '.TABLE_USERS.' u left join '.TABLE_ORDERS.' o on u.id=o.user_id  where o.user_id>0 and o.tstatus not like \'Отменен%\' and o.status<6  group by u.id order by sm desc limit 20';
	$qy = $db->get_rows($query);
	foreach($qy as $id=>$row)
	{
		$inf = unserialize($row['info']);
		$fio=$inf['9'].' '.$inf['8'].' '.$inf['11'];
		$cnt.='<tr><td class="news_td lft">'.$fio.'</td><td class="news_td rgt">'.$row['cn'].''.'</td><td class="news_td rgt">'.number_format($row['sm'],0,'.',"'").'</td></tr>';
	}	
	$cnt.='</tbody></table>';
	return $cnt;
}
function get_module($btns, $btn_id, $content, $title)
{
//	   <tr>
//     <td class="fr1 p0">'.$title.'</td>
//	   </tr>



return '

		 <table class="fr2_vb_1">
		  <tr>
		   <td class="fr2_td_1 p0 h100">&nbsp;</td>
		   <td id="btns_panel" class="fr2 p0">'.get_btns_panel($btns, $btn_id).'</td>
		   <td id="btns_panel" class="tit fr2">'.$title.'</td>
		   <td class="fr2_td_3 p0 h100">&nbsp;</td>
		  </tr>
		 </table>


		 <div id="module_html" class="fr3_td_2 p0">'.$content.'</div>
';
}

function get_btns_panel($btns, $btn_id=1)
{
	$bp = '&nbsp;';
	if(!empty($btns) && count($btns) > 0)
	{
		$bp = '<table class="mc_tm_1"><tr>';
	    foreach($btns as $id=>$btn){
			$bp .= '<td class="mc_btn_1 p0" onclick="'.htmlspecialchars($btn['href'],ENT_COMPAT | ENT_XHTML,'utf-8').'">'.((isset($btn['id']) && $btn['id']==$btn_id) ? btn_sel($id) : btn($id)).'</td>';
		}
		$bp .= '</tr></table>';
	}
	return $bp;
}


function btn($a)
{
	return '
	   <table class="mc_btn_c">
		<tr>
		 <td class="mc_b_btn11 p0"></td>
		 <td class="mc_b_btn22 p0">'.$a.'</td>
		 <td class="mc_b_btn33 p0"></td>
		</tr>
	   </table>
	';
}

function btn_sel($a)
{
	return '
	   <table class="mc_btn_c">
		<tr>
		 <td class="mc_b_btn1 p0"></td>
		 <td class="mc_b_btn2 p0">'.$a.'</td>
		 <td class="mc_b_btn3 p0"></td>
		</tr>
	   </table>
	';
}

if(!function_exists('strrpt'))
{
	function strrpt($str,$n)
	{
		$rtn = "";
		for ($i=0; $i < $n; $i++)
		{
			$rtn .= $str;
		}
		return $rtn;
	}
}
if(!function_exists('ru_to_en_lc'))
{
// ФУНКЦИЯ ТРАНСЛИТА РУССКИХ БУКВ -> НА АНГЛИЙСКИЙ;
// ПЕРЕВОДА ВЕРХНЕГО РЕГИСТРА -> В НИЖНИЙ РЕГИСТР
function ru_to_en_lc($slovo) {

	$repl = array(
		" " => "",
		"*" => "",
		"?" => "",
		"&" => "",
		"^" => "",
		";" => "",
		"-" => "",
		"$" => "",
		"%" => "",
		"@" => "",
		"\"" => "",
		"\\" => "",
		"!" => "",
		"+" => "",
		"=" => "",
		"|" => "",
		"№" => "",
		"/" => ""
	);

	$slovo = strtolower(strtr($slovo, $repl));

	$slovo=strtr($slovo,"ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮЁ","йцукенгшщзхъфывапролджэячсмитьбюё");
	$slovo=strtr($slovo,"абвгдеёзийклмнопрстуфхъыэ","abvgdeeziyklmnoprstufh3ie");

	$slovo=strtr($slovo,
	array(
		"ж"=>"zh","ц"=>"ts",
		"ч"=>"ch","ш"=>"sh",
		"щ"=>"shch","ь"=>"",
		"ю"=>"yu","я"=>"ya"
	));

	return($slovo);
	}
}

function GetHeader($title='',$desc='',$trig='',$adv='')
{
	return '
	<tr>
	<td class="header_td">'.$title.'</td>
	<td class="header_td">'.$desc.'</td>
	<td class="header_td">'.$trig.'</td>
	<td class="header_td">'.$adv.'</td>
	';
}

function GetOpen($title,$desc,$trig,$adv)
{
	return '
	<tr valign="top">
		<td class="adv">'.$adv.'</td>
		<td class="title">'.$title.'</td>
		<td class="desc">'.$desc.'</td>
		<td class="trig">'.$trig.'</td>
	<tr><td colspan=4 class="tdsep"></td>
	';
}

function GetForm($action,$title,$desc,$trig,$adv)
{
	return '
	<tr valign="top">
		<td class="title" align="right" style="padding-right:20px " >'.$title.'</td>
		<td class="desc">'.$desc.'</td>
		<td class="trig">'.$trig.'</td>
		<td class="adv">'.$adv.'</td>
	<tr><td colspan=4 class="tdsep"></td>
	';
}


function redir_a($action,$dir)
{
	echo "<div class=\"saving\">".$action."</div>";
	echo "<script>location.href='".$dir."'</script>";
	exit;
}

function button($value, $action)
{
	return '<table onclick="'.htmlspecialchars($action,ENT_COMPAT | ENT_XHTML,'utf-8').'" onmouseover="className=\'button_sel\'" onmouseout="className=\'button\'" class="button"><tr><td class="btn_l"></td><td class="btn_c">'.$value.'</td><td class="btn_r"></td></tr></table>';
}
function button1($value, $action,$id='',$cl='def')
{
	return '<button'.((strpos($id,'type=')===false)?' type="button" ':' ').$id.' onclick="'.htmlspecialchars($action,ENT_COMPAT | ENT_XHTML,'utf-8').'" class="albutton alorange"><span><span><span class="'.htmlspecialchars($cl,ENT_COMPAT | ENT_XHTML,'utf-8').'">'.$value.'</span></span></span></button>';

//	return '<table '.$id.' onclick="'.htmlspecialchars($action).'" onmouseover="className=\'button_selm\'" onmouseout="className=\'buttonm\'" class="buttonm"><tr><td class="btn_l"></td><td class="btn_c">'.$value.'</td><td class="btn_r"></td></tr></table>';
}

function tinymce_options($id)
{
if ($id==0){
	
	return '
	tinyMCE.init({
		// General options
		mode : "exact",
		theme : "advanced",
        skin : "o2k7",
        skin_variant : "silver",
		editor_selector : "mceEditor",
		language : "ru",
		plugins : "inlinepopups,table,advimage,advlink,paste,contextmenu,print,fullscreen,media,imagemanager,style,razdelmanager,template",
		disk_cache : true,
		document_base_url : \''.SITE_URL.'\',
        content_css : "'.SITE_URL.'templates/101/css/style.css",
//		template_external_list_url : "jscripts/tiny_mce/templates/templatelist.js",
template_templates : [
        {
                title : "Скидки",
                src : "'.SITE_URL.'jscripts/tiny_mce/templates/1.html",
                description : "Банер со скидками"
        },
        {
                title : "Таймер",
                src : "'.SITE_URL.'jscripts/tiny_mce/templates/2.html",
                description : "Таймер обратного отсчета. Подкючить main.css и jquery.lwtCountdown-1.0.js"
		}
],
		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleprops,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,template,cleanup,code,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,removeformat,|,sub,sup,|,charmap,|,print,fullscreen,|,cite,abbr|,nonbreaking,media",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		relative_urls : true,
		remove_script_host : true,
		fix_content_duplication : false,
		cleanup : true,
		fix_nesting : true,
		fix_table_elements : true,
		force_hex_style_colors : true,
		remove_trailing_nbsp : true,
		table_inline_editing : 1,
		valid_elements : "br[clear|style|title],@[id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|name|href|target|title|class|onfocus|onblur],strong/b,em/i,strike,u,#p[align],-ol[type|compact],-ul[type|compact],-li,noindex,img[longdesc|usemap|src|border|alt=|alt1=|title|hspace|vspace|width|height|align],-sub,-sup,-blockquote[cite],-table[border=0|cellspacing|cellpadding|width|frame|rules|height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,-div,-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],object[classid|width|height|codebase|*],param[name|value],embed[type|width|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value|tabindex|accesskey],kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],'.'if'.'rame'.'[width|height|frameborder|scrolling|marginheight|marginwidth|src],q[cite],samp,select[disabled|multiple|name|size],small,textarea[cols|rows|disabled|name|readonly],tt,var,big",
		theme_advanced_fonts : "Arial=arial;Arial Black=Arial Black,Gadget,sans-serif,helvetica,sans-serif;Comic Sans MS=Comic Sans MS,cursive;Courier New=courier new,courier,monospace;Georgia=georgia,times new roman,times,serif;Impact=impact;Monotype Corsiva=monotype corsiva;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,arial,helvetica,sans-serif",
		font_size_style_values : "8px,10px,12px,14px,18px,24px,36px",
		theme_advanced_blockformats : "div,p,h1,h2,h3,h4"
	});
	';
	} 
	else
	{
	return	'
//	tinymce.PluginManager.load(\'moxiecut\', \'/jscripts/tinymce/plugins/moxiecut/plugin.min.js\');
tinymce.init({
    selector: ".moxiecut",
    theme: "modern",
    plugins: [
	    "advlist autolink lists link image charmap print preview anchor",
		"searchreplace visualblocks code fullscreen",
		"insertdatetime media table contextmenu paste moxiemanager"
	],
	toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link insertfile image media",
	autosave_ask_before_unload: false,
    height: 500,
    relative_urls: false
});
';

	}
}

function tinymce($id=0)
{
	return '
	<script type="text/javascript">
	'.tinymce_options($id).'
	</script>
	<!-- /TinyMCE -->
	<!-- /TinyMCE -->
	';
}
