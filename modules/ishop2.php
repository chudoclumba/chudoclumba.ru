<?php



if(!class_exists('Site')) die(include '../404.html');

class Ishop extends Site{
	private $dop_par = array('mat_id', 'color_id');
	private $curr_cat_id = -1;
	private $content = array();
	public $cat_all = array();
	public $disabled = array();
	public $filtered_ids = array();
	public $top_id;
	public $card;
	public $image_width = '123';
	public $image_height = '155';
	public $product_pw = '35';
	public $open_pages = array(11);  //Всегда открытые разделы
	public $subcats = array();
	private $prd_list_style = 'prdlistnew';


	public function __construct($db, $sets, $ebox)	{
		$this->db = Site::gI()->db;
		$this->sets = $sets;
		$this->ebox = $ebox;
		$this->setVar('pre', 'ishop/');
		$this->setVar('fix', '');
		$this->setVar('hidden_path', array()); //скрыть разделы в пути
		$this->setVar('sitemap_ttl', 'Каталог');
		$this->setVar('module', 'ishop');
		$id=(isset($_GET['id']))?$_GET['id']:0;
		$this->get_menu($id, $this->getMenuRows());
		define('PAGES_TOP_ENABLED',1);
		define('PAGES_BOTTOM_ENABLED',1);
		define('PAGES',$sets['prd_count']);
		$config  = TEMP_FOLDER.'ishop_config.php';
		if(file_exists($config))
		{
			require $config;
		}
		$_GET['type'] = (!empty($_GET['type']))?$_GET['type']:'';
//		if(empty($_GET['id'])) $_GET['id'] = 0;
		if(!empty($_SESSION['card_nomer']))
		{
			$card_inf = $this->db->get_rows("SELECT skidka FROM ".TABLE_CARDS." WHERE nomer = ".quote_smart($_SESSION['card_nomer'])." LIMIT 1");
			if(!empty($card_inf['0']['skidka'])) $this->card = $card_inf['0']['skidka'];
		}
	}


	function get_catnm($id,$islink=1,$tid=0){
	    $catpinf = $this->db->get(TABLE_CATEGORIES,$id);
	    if (!(count($catpinf)>0)) return '';
		if ($islink==1 && $tid!=$id) {
			$nm='<li class="active"><a title="Открыть" href="'.SITE_URL.$catpinf['vlink'].'">'.$catpinf['title'].'</a></li>';
		}	else	{
			if ($islink==2) $nm=$catpinf['title'].'/';
			else $nm='<li>'.$catpinf['title'].'</li>';
		}
		if ($catpinf['parent_id']==11 && $islink!=2) $this->top_cat=$catpinf['title'];
		if ($catpinf['id']>0 && ($catpinf['parent_id']!=11 && $islink=2)) {
			$r=$this->get_catnm($catpinf['parent_id'],$islink);
			if (!empty($r)) $nm=$r.$nm;
		}
		return $nm;
	}


	function getMenuRows()	{
		return $this->db->get_rows("SELECT id, parent_id, title, link, visible, vlink,cnt FROM ".TABLE_CATEGORIES." where enabled=1 and visible=1 ORDER BY sort ASC, id ASC");
	}


	function filter_top($id, $curr_c = -1, $curr_v = -1)	{
		$fltr = $this->filtered_ids;
		$fl = '';
		if(count($fltr) > 0)
		{
			$fl = " && prd_id IN (".implode(', ',$fltr).")";
		}
		$chars = $this->db->get_rows("SELECT char_id, name FROM ".TABLE_CHARS." WHERE cat_id = ".quote_smart($id)." ORDER BY char_id ASC");
		$chars_val = $this->db->get_rows("SELECT char_id, value  FROM ".TABLE_CHARS_VALUES." WHERE char_id IN(SELECT char_id FROM ".TABLE_CHARS." WHERE cat_id = ".quote_smart($id).")");
		$chars_val2 = $this->db->get_rows("SELECT char_id, value  FROM ".TABLE_CHARS_VALUES." WHERE char_id IN(SELECT char_id FROM ".TABLE_CHARS." WHERE cat_id = ".quote_smart($id).") ".$fl."");
		//print_r($chars_val2);
		$chars_v = array();
		$chars_v2 = array();

		foreach($chars_val2 as $id=>$val)
		{
			$chars_v2[$val['char_id']][] = $val['value'];
		}

		foreach($chars_val as $id=>$val)
		{
			$chars_v[$val['char_id']][] = $val['value'];
		}

		foreach($chars_v as $id=>$val)
		{
			$val = array_unique($val);
			sort($val);
			foreach($val as $id5=>$val5)
			{
				if(!in_array($val5, $chars_v2[$id]))
				{
					unset($val[$id5]);
				}
			}
			$chars_v[$id] = $val;
		}
		return $this->view('ishop/filter_top', array('chars'=>$chars, 'chars_v'=>$chars_v, 'curr_c' => $curr_c, 'curr_v' => $curr_v));
	}


	function update_all()	{
		$this->cat_all = array();
		$rows = $this->db->get_rows("SELECT id, enabled, parent_id FROM ".TABLE_CATEGORIES."");
		foreach($rows as $id=>$val)
		{
			$this->cat_all[$val['parent_id']][] = array($val['id'], $val['enabled']);
		}
		$this->recurs_cats($this->cat_all['0'], 1, 1);
	}


	function recurs_cats($cats2, $display, $display2){
		if(count($cats2) > 0){
			foreach($cats2 as $c_id=>$c_val){
				if($display == 0 || $c_val['1'] == 0 || $display2 == 0){
					$this->disabled[] = $c_val['0'];
				}
				if(array_key_exists($c_val['0'], $this->cat_all)){
					$this->recurs_cats($this->cat_all[$c_val['0']], $c_val['1'], $display);
				}
			}
		}
	}


	function count_vs_prd()	{
		$count = 0;
		if(!empty($_SESSION['vs_prds']))		{
			$count = count(array_unique($_SESSION['vs_prds']));
		}
		return $count;
	}


	function specpredloshenie($cnts = 6, $rs = 3)
	{
		$cnt = '';
		$rows = $this->db->get_rows("SELECT id, title, tsena, skidka, foto,vlink FROM ".TABLE_PRODUCTS." WHERE new = 1 && enabled = 1 && visible = 1 ORDER BY id DESC LIMIT ".$cnts);
		if(count($rows) > 0)
		{
			$cnt = $this->view('ishop/specpredl', array('rows'=>$rows, 'rs'=>$rs, 'sets'=>$this->sets));
		}
		return $cnt;
	}


	function hitprod($cnts = 6, $rs = 3)
	{
		$cnt = '';
		$rows = $this->db->get_rows("SELECT id, title, tsena, skidka, foto,vlink FROM ".TABLE_PRODUCTS." WHERE hit = 1 && enabled = 1 && visible = 1 ORDER BY id DESC LIMIT ".$cnts);
		if(count($rows) > 0)
		{
			$cnt = $this->view('ishop/hitprod', array('rows'=>$rows, 'rs'=>$rs, 'sets'=>$this->sets));
		}
		return $cnt;
	}


	function left_menu_img($cat_id)
	{
		$cnt = '';
		$rows = $this->db->get_rows("SELECT * FROM ".TABLE_CATEGORIES." WHERE parent_id=".quote_smart($cat_id)." && enabled = 1 ORDER BY sort ASC");
		if(count($rows) > 0)
		{
			$cnt .= '<table style="width:100%;">';
			foreach($rows as $id=>$value)
			{
				$cnt .= '<tr><td style="text-align:center; padding:20px 18px 10px 0px">';
				/*$cnt .= '<a href="ishop/brand/'.$value['id'].'">
				<img alt="" src="thumb.php?id='.$value['foto'].'&x=100&y=100" />
				</a>';*/
				$cnt .= '<div><a href="ishop/brand/'.$value['id'].'">'.$value['title'].'</a></div>
				</td></tr>';
			}
			$cnt .= '</table>';
		}
		return $cnt;
	}


	function get_subcats($id)
	{
		$rows = $this->db->get_rows("SELECT * FROM ".TABLE_CATEGORIES." WHERE parent_id = ".quote_smart($id)."  && enabled='1' ORDER BY position ASC, id ASC");
		return $rows;
	}


	function param_list($gvalue, $cr = '')
	{
		$cnt = '';
		$rows = $this->db->get_rows("SELECT ".$gvalue." FROM ".TABLE_PRODUCTS." GROUP BY ".$gvalue." ORDER BY ".$gvalue." ASC");
		foreach($rows as $id=>$value)
		{
			if(!empty($value[$gvalue]))
			{
				$cnt .= '<option '.(($cr == $value[$gvalue]) ? 'selected="selected"' : '').' value="'.$value[$gvalue].'">'.$value[$gvalue].'</option>';
			}
		}
		return $cnt;
	}


	function cart2($type = 0) //TODO Скидки
	{
		$prd = Cart::gI()->get_cart_info();
		$summa = Cart::gI()->cart_sum;
		$summa_for_sale = Cart::gI()->cart_sum_for_sale;
		$cnt = 'Корзина пуста';
		if(count($prd) > 0 && $summa > 0)
		{
			$cnt = '';
			foreach($prd as $prd_id=>$val)
			{
				$cnt .= $prd[$prd_id]['name'].': '.$_SESSION[CART][$prd_id]['count'].' - '.$_SESSION[CART][$prd_id]['count']*$prd[$prd_id]['tsena'].'<br>';
			}

		$cnt .= 'Сумма: '.$this->s_price_c($summa).'<br>
Скидка по дисконтной карте: '.$this->s_price_c(($summa_for_sale*$this->card)/100).'<br>
Всего: '.$this->s_price_c($summa-(($summa_for_sale*$this->card)/100)).'<br>
<a href="ishop/cart">Оформление заказа</a>';
		}

		return $cnt;
	}


	function select($type = 0)
	{
		return $this->view('ishop/filter_form');
	}

	function get_cat_title($id) {
		$inf = $this->db->get_rows("SELECT title FROM ".TABLE_CATEGORIES." WHERE id = ".quote_smart($id)."");
		return $inf['0']['title'];
	}


	function cat($id)
	{	
		if ($id==0) return false;
		$cat_inf = $this->db->get(TABLE_CATEGORIES,$id);
		return $cat_inf;
	}


	function cats($id)
	{
		$cats = $this->db->get_rows("SELECT id,title,foto,cnt,vlink FROM ".TABLE_CATEGORIES." WHERE parent_id = '".$id."' && enabled=1 && visible=1 ORDER BY sort ASC, id ASC");
		if ($this->sets['hide_empty_cat']) 
		{
	   	    for ($x=count($cats); $x>=1; $x--)   
			{
				if (!($cats[$x-1]['cnt']>0) && (stripos(mb_strtoupper($cats[$x-1]['title']),'СКОРО')===false) ) array_splice($cats,$x-1,1);
			}
		}
		
		return $cats;
	}


	function get_prd_palitra($prd_id, $text)
	{
		$artics = get_elements($text,'articul');
		$a = 0;

		foreach($artics as $artic)
		{
			$valuesu = count(get_elements($artic,'param'));
			if($valuesu > $a)
			{
				 $a = $valuesu;
			}
		}
		$arts =  '<tr><td><table><tr><td class="outer_pal"><table class="pal_tbl">';

		$arts .= '';
		foreach($artics as $id=>$artic)
		{
			$values = get_elements($artic,'param');
			$arts .= '
			<tr>
			 <td><img alt="" src="thumb.php?id='.$values['0'].'&x='.$this->image_width.'&y='.$this->image_height.'" /></td>
			 <td style="padding:0px 20px; color:#1a9efd;">'.$values['1'].'</td>
			 <td style="padding:0px 20px; color:#1a9efd;">'.$values['2'].'</td>
			 <td><a id="zkz_lnk_'.$values['0'].'" href="ishop/add/'.$_GET['id'].'_'.$id.'"><img alt="Купить" src="'.TEMP_FOLDER.'images/buy.jpg" /></a></td>
			</tr>';
		}
		$arts .= '</table></td></tr></table></td></tr>';
		return $arts;
	}


	function get_prd_chars($prd_id, $cat_id)
	{
		$charsh = '';
		$chars = $this->db->get(TABLE_CHARS, array('cat_id' => $cat_id), array('sort' => 'asc'));
		$chars_values = $this->db->get(TABLE_CHARS_VALUES, array('prd_id' => $prd_id));
		$chars_vals = array();
		foreach($chars_values as $id=>$value)
		{
			$chars_vals[$value['char_id']] = $value['value'];
		}
		unset($chars_values);
		if(count($chars_vals) > 0)
		{
			$charsh = '<tr><td><div class="o_hd">Характеристики:</div><ul style="margin:10px 0px;">';
			foreach($chars as $char)
			{
				$value = (isset($chars_vals[$char['char_id']])) ? $chars_vals[$char['char_id']] : '';
				$charsh  .= '
				   <li>'.htmlspecialchars($char['name'],ENT_COMPAT | ENT_XHTML,'cp1251').': '.$value.'</li>';
			}
			$charsh .= '</ul></td></tr>';
		}
		return $charsh;
	}


	function gallery($prd_id)
	{
		$fotki = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE cat_id = '".$prd_id."' ORDER BY sort ASC");
		$cnt = '<tr><td>';
		if(count($fotki) > 0)
		{
			$cnt .= '<div class="prd_text2">Похожие товары:</div>';
			ob_start();
			?>
			<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#mycarousel').jcarousel();
	});
	</script>
	<div id="wrap">
	  <ul id="mycarousel" class="jcarousel-skin-tango">
	  <?foreach($fotki as $id=>$val){?>
		<li><a title="<?=$val['title']?>" href="ishop/product/<?=$val['id']?>"><img src="s.gif" style="background:url(<?=SITE_URL?>thumb.php?id=<?=$val['foto']?>&x=75&y=75) center no-repeat" width="75" height="75" alt="" /></a></li>
		<?}?>
	  </ul>
	</div>
			<?
			$cnt .= ob_get_contents();
			ob_end_clean();
		}
		$cnt .= '</td></tr>';
		return $cnt;
	}


	function get_prd_photos($prd_id)
	{
		$imgs = '';
		$fots = $this->db->get_rows("SELECT * FROM ".TABLE_PRD_FOTO." WHERE product_id = '".$prd_id."' ORDER BY sort ASC");
		if(count($fots) > 0)
		{
			$imgs .= '<tr><td><div class="o_hd">Фотографии:</div></td></tr><tr><td><div id="small_images">';
			foreach($fots as $foto)
			{
				if(file_exists(ROOT_DIR.$foto['filename']))
				{
					if(1!=1)
					{
						$imgs .= '<img onclick="ch_img(\''.$foto['filename'].'\','.$foto['id'].')" alt="" src="thumb.php?id='.$foto['filename'].'&x=100" /> ';
					}
					else
					{
						$imgs .= '<a class="highslide" onclick="return hs.expand(this)" href="'.$foto['filename'].'"><img alt="" src="thumb.php?id='.$foto['filename'].'&x=100" /></a> ';
					}

				}
			}
			$imgs .= '</div>
			   <script>
				function ch_img(new_src,id)
				{
					document.getElementById(\'big_f\').src = \'thumb.php?id=\' + new_src + \'&x='.$this->image_width.'&y='.$this->image_height.'\';
					document.getElementById(\'flink\').href = \'thumb.php?id=\' + new_src + \'&x=1200&y=1200\';
					//document.getElementById(\'buy_link\').href = \'ishop/add/'.$_GET['id'].''.$_GET['id'].'_\' + id;
				}
				</script></td></tr>
			 ';
		}
		return $imgs;
	}


	function get_prd_rating($prd_id)
	{
		$rate_c = $this->db->get_rows("SELECT * FROM ".TABLE_RATING." WHERE products_id = ".quote_smart($prd_id)." && ip = ".quote_smart($_SERVER['REMOTE_ADDR'])."");
		$product = '<div class="ppr_d_t mcnp">';
		if(count($rate_c) == 0)
		{
			$product .= '<form action="" method="post">';
			$product .= 'Рейтинг: <select name="rate"><option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option><option value="1">1</option>
</select> <input name="rate_send" type="submit" value="Проголосовать" /></form>';
		}
		else
		{
			$rate = $this->db->get_rows("SELECT * FROM ".TABLE_RATING." WHERE products_id = ".quote_smart($_GET['id'])."");
			$srate = 0;
			foreach($rate as $id=>$value)
			{
				$srate += $value['value'];
			}
			$nm = ceil($srate/count($rate));
			$rt = '<table>
			 <tr>';
			$stars = 5;
			for($i = 0; $i < $nm; $i++)
			{
				$rt .= '<td class="star p0"></td>';
			}
			for($i = $nm; $i < $stars; $i++)
			{
				$rt .= '<td class="star_s p0"></td>';
			}
			$rt .=  '</tr>
			</table>';
			$product .= 'Рейтинг: '.($srate/count($rate)).' '.$rt;
		}
		$product .= '</div>';
		return $product;
	}


	function get_grrec($gr)
	{
		$ret='';
		$cat = $this->db->get_rows("SELECT id,recpar,parent_id from ".TABLE_CATEGORIES." where id=".quote_smart($gr));
		$prd = $this->db->get_rows("SELECT r.prdid from ".TABLE_GRREC." r, ".TABLE_PRODUCTS." p where p.id=r.prdid && p.enabled=1 && p.visible=1 && r.grid=".$cat[0]['id']);
		foreach($prd as $id=>$rprd) {
			if (!empty($ret)) $ret.=',';
			$ret.=$rprd['prdid'];
		} 
		if ($cat[0]['parent_id']>0 && $cat[0]['recpar']>0) $ret.=((!empty($ret))?',':'').$this->get_grrec($cat[0]['parent_id']);
		return $ret;
	}


	function get_prd_recc($cat_id,$gr)
	{
		$product='';
		$prd=$this->get_grrec($gr);
		$r_prds = $this->db->get_rows("SELECT r.r_product from ".TABLE_RECOM." r,".TABLE_PRODUCTS." p where p.id=r.product && p.visible=1 && p.enabled=1 && r.product = ".quote_smart($cat_id));
		foreach($r_prds as $id=>$rprd) {
			if (!empty($prd)) $prd.=',';
			$prd.=$rprd['r_product'];
		}
		$r_prds=explode(',',$prd); 
		shuffle($r_prds);
		$r_prds=array_slice($r_prds,0,10);
		$prd=implode(',',$r_prds);
		$r_prds=array();
		if (!empty($prd)) $r_prds = $this->db->get_rows("SELECT p.title, p.id, p.skidka, p.tsena, p.foto, p.cat_id, p.vlink FROM ".TABLE_PRODUCTS." p WHERE p.id in (".$prd.")" );
		if(count($r_prds) > 0)
		{
			shuffle($r_prds);
		$product = '<tr><td><div class="o_hd"></div>';
		$product.=$this->view('ishop/recprd', array('rows'=>$r_prds));
		$product .= '</td></tr>';
		}
		return $product;
	}


	function get_prd_comment($prd_id)
	{
		$msgs = $this->db->get_rows("SELECT * FROM ".TABLE_COMMENTS." WHERE cat_id=".quote_smart($prd_id)." && module = 2 && enabled = 1 ORDER BY id DESC");
		return $this->view('ishop/product_comments', array('msgs'=>$msgs));
	}


	function get_all_subcats($cat_id)
	{
		$q=array();
		if(!is_array($cat_id))
		{
			$cat_id = array($cat_id);
		}
		if(count($cat_id) > 0)
		{
			sort($cat_id,SORT_NUMERIC);
			$rows = $this->db->get_rows("SELECT id FROM ".TABLE_CATEGORIES." WHERE parent_id IN (".implode(',',$cat_id ).")");
			$g = array();
			if(count($rows) > 0)
			{
				foreach($rows as $row)
				{
					$g[] = $row['id'];
					$this->subcats[] = $row['id'];
				}
				$this->get_all_subcats($g);
			}
		}
	}


	function get_act_partr($gr_id)
	{
		$row = $this->db->get(TABLE_PRD_GR,$gr_id);
		$p_ar = explode(',',$row['gr_pr']);
		return $p_ar;
	}


	function get_act_partr_r($p_id)
	{
		$q_r = $this->db->get(TABLE_CATEGORIES,$p_id);
		$row = $this->db->get(TABLE_PRD_GR,$q_r['gr_id']);
		$p_ar = explode(',',$row['gr_pr']);
		return $p_ar;
	}


	function set_act_partr($gr_id, $new_p)
	{
		$q_p = "UPDATE ".TABLE_PRD_GR." SET gr_pr = '".$new_p."' WHERE gr_id = '".$gr_id."'";
		$row = $this->db->fetch_array($db->query($q_p));
		$p_ar = explode(',',$row['gr_pr']);
		return $p_ar;
	}


	function get_filtered_ids($cat_id)
	{
		$poc = array();
		if(!empty($_SESSION['filter']) && count($_SESSION['filter']) > 0)
		{
			$fl = '';
			if(count($this->filtered_ids) > 0)
			{
				$fl = " && prd_id IN(".implode(', ', $this->filtered_ids).") ";
			}
			$chars_val = $this->db->get_rows("SELECT char_id, value, prd_id  FROM ".TABLE_CHARS_VALUES." WHERE char_id IN(SELECT char_id FROM ".TABLE_CHARS." WHERE cat_id = ".quote_smart($cat_id).") ".$fl."");
			$chars_v_all = array();
			$chars_v = array();
			foreach($chars_val as $id=>$val)
			{
				$chars_v_all[$val['char_id']][] = $val['value'];
			}
			foreach($chars_v_all as $id=>$val)
			{
				$val = array_unique($val);
				sort($val);
				$chars_v[$id] = $val;
			}
			$chars_p = array();
			foreach($_SESSION['filter'] as $id=>$val)
			{
				if(!empty($chars_v[$id][$val]))
				{
					$chars_p[] = $this->db->get_rows("SELECT prd_id  FROM ".TABLE_CHARS_VALUES." WHERE char_id = ".$id." && value = ".quote_smart($chars_v[$id][$val])."");
				}
			}
			$fprds = array();
			$oc = count($_SESSION['filter']);
			foreach($chars_p as $id=>$val)
			{
				foreach($val as $vid=>$vval)
				{
					if(empty($poc[$vval['prd_id']]))
					{
						$poc[$vval['prd_id']] = 0;
					}
					$poc[$vval['prd_id']]++;
				}
			}
			foreach($poc as $id=>$val)
			{
				if($oc > $val)
				{
					unset($poc[$id]);
				}
			}
			$poc = array_keys($poc);
		}
		return $poc;
	}


	function getPrdCountInCat($cat_id)
	{
		$poc = array();
		if($this->sets['mod_filter_top']){
			$poc = $this->get_filtered_ids($cat_id);
			$this->filtered_ids = $poc;
		}
		$fl = '';
		if(count($poc) > 0)
			$fl = "&& id IN (".implode(', ', $poc).")";
		$qd = $this->db->get_rows("SELECT distinct srcid FROM ".TABLE_PRODUCTS." p WHERE cat_id = '".$cat_id."' && enabled='1' && visible='1' && srcid>0 && id=srcid");
		$q="SELECT count(id) as cnt , tper  FROM ".TABLE_PRODUCTS." p left join (select prdid,sum(cnt) as tot from ".TABLE_ORDERS_REG." d where d.isins>0 group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*{$this->sets['cart_res_time']}) group by prdid) as cr on cr.prdid=p.id WHERE cat_id = '$cat_id' && enabled=1 && visible=1 && srcid=0 && ((p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0)>0 and p.saletype=1) or p.saletype=0) group by tper";
		$r = $this->db->get_rows($q);
		$cnt=0;
		$per=array();
		foreach($r as $val){
			$per[]=$val['tper'];
			$cnt+=$val['cnt'];
		}
//		print_r([$per,$cnt+count($qd)]);
		return $cnt+count($qd);
	}


	function products($cat_id, $sort = ' id ASC', $limit = '') //TODO Скидки
	{
		$fl = '';
		$poc = $this->filtered_ids;
		if(count($poc) > 0)
			$fl = "&& id IN (".implode(', ', $poc).")";
		$prds = array();
//		$q = $this->db->get_rows("SELECT distinct srcid FROM ".TABLE_PRODUCTS." WHERE cat_id = '".$cat_id."' && enabled='1' && visible='1' && srcid>0 ");
//		$q = $this->db->get_rows("SELECT distinct srcid FROM ".TABLE_PRODUCTS." p left join (select prd_id,sum(d.count+d.isdel-d.otgr) as tot from ".TABLE_ORDERS_PRD." d right join ".TABLE_ORDERS." o on o.id=d.order_id where o.status=1 group by prd_id) as tm1 on tm1.prd_id=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].") group by prdid) as cr on cr.prdid=p.id WHERE cat_id = '".$cat_id."' && enabled='1' && visible='1' && srcid>0 && ((p.sklad+p.zakazpost-ifnull(tm1.tot,0)-ifnull(cr.ctot,0)>0 and p.saletype='1') or p.saletype='0')");
		$res=array();
//		foreach($q as $r)
//			$res[]=$r['srcid'];
		(is_array($res) && count($res)>0) ? $or="or (id in (".implode(', ', $res)."))" : $or='';
		$q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,ifnull(tm1.tot,0)+p.reserv as ztot,ifnull(cr.ctot,0) as ctot,p.* from ".TABLE_PRODUCTS." p left join (select prdid,sum(d.cnt) as tot from ".TABLE_ORDERS_REG." d where d.isins>0 group by prdid) as tm1 on tm1.prdid=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].") group by prdid) as cr on cr.prdid=p.id WHERE (cat_id = '".$cat_id."' && ((enabled=1 && visible=1 && srcid=0) or (srcid=id)) && ((p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0)>0 and p.saletype='1') or p.saletype='0')) ".$or." ORDER BY ".$sort." ".$limit."";
		$prds = $this->db->get_rows($q);
		return $prds;
	}


	function getPrdCountInBrand($cat)
	{
		$q = "SELECT count(id) as cnt FROM ".TABLE_PRODUCTS." WHERE param_brend = '".intval($cat)."' && enabled = 1";
		$r = $this->db->get_rows($q);
		if (count($r)>0)	return $r['0']['cnt'];
		else return 0;
	}


	function getPrdCountInSearch($search_q)
	{
		$q = "SELECT count(id) as cnt FROM ".TABLE_PRODUCTS." WHERE ".$search_q;
		$r = $this->db->get_rows($q);
		if (count($r)>0)	return $r['0']['cnt'];
		else return 0;
	}


	function getProductListing($type = 'products_listing_short', $prds, $options = array())
	{
		$list = $this->view('ishop/'.$type, array('prds'=>$prds, 'sets'=>$this->sets, 'ebox'=>$this->ebox, 'options'=>$options));
		return $list;
	}


	function get_mat_list_a($a)
	{
		global $db;
		$materials = $db->get_rows("SELECT id, name FROM ".TABLE_MATERIAL." WHERE id = ".$a."");

		$cnt .= 'q2121';
		return $cnt;
	}


	function generate_orderid($user,$period=0,$mail)
	{
		if ($user>0) {
			echo "USER";
			$res=$this->db->get_rows("SELECT id,ha,ld FROM ".TABLE_ORDERS." where user_id='{$user}' and per='{$period}' and status in (1,3) order by data desc");
			if (count($res)>0) {
				$res1=$this->db->get_rows("SELECT ifnull(max(kodstr+1),1) as kodstr FROM ".TABLE_ORDERS_PRD." where order_id='{$res[0]['id']}'");
				return array('id'=>$res[0]['id'],'ha'=>$res[0]['ha'],'kodstr'=>$res1[0]['kodstr'],'new'=>false,'ld'=>$res[0]['ld']);
			}
		}
		elseif (!empty($mail) && strpos(strtoupper($mail),'@CHUDOCLUMBA')===false){
			echo "EMAIL";
			$res=$this->db->get_rows("SELECT id,ha,ld,user_id FROM ".TABLE_ORDERS." where email='{$mail}' and per='{$period}' and status in (1,3) order by data desc");
			if (count($res)>0) {
				$res1=$this->db->get_rows("SELECT ifnull(max(kodstr+1),1) as kodstr FROM ".TABLE_ORDERS_PRD." where order_id='{$res[0]['id']}'");
				$_SESSION['user']=$res[0]['user_id'];
				return array('id'=>$res[0]['id'],'ha'=>$res[0]['ha'],'kodstr'=>$res1[0]['kodstr'],'new'=>false,'ld'=>$res[0]['ld']);
			}
		}
	$ha=($user>0) ? '*' : get_ord_ha();
		$cnt=0;
		$id=false;
	$this->db->SetErrMsgOn(false);
		//do {
			$res=$this->db->get_rows("SELECT ifnull(max(tid)+1,1) as tid FROM ".TABLE_ORDERS." where user_id='{$user}' and per='{$period}'");
			$cnt+=1;
			if (count($res)>0) {
				$id=$this->next_order_number($user, $period);//$user."-".($res[0]['tid']+$cnt).'-'.$period;
	
				$res1 = $this->db->insert(TABLE_ORDERS,array('id'=>$id, 'tid'=>$res[0]['tid']+$cnt,'per'=>$period,'data'=>time(),'ha'=>$ha));
				if ($res1===FALSE)
				{
	
					$id=false;
				}
			}
	   // } while ( $id==false && $cnt<11);
	$this->db->SetErrMsgOn(true);
	
		return array('id'=>$id,'ha'=>$ha,'kodstr'=>1,'new'=>true,'ld'=>0);
	}


	function exists_in_orders($orderid)
	{
		$res = false;
		$res1=$this->db->get_rows("SELECT id FROM ".TABLE_ORDERS." where order_id='{$orderid}'");
		if(count($res1) > 0)
			$res = true;
		return $res;
	}


	function exists_in_order_details($orderid)
	{
		$res = false;
		$res1=$this->db->get_rows("SELECT order_id FROM ".TABLE_ORDERS_PRD." where order_id='{$orderid}'");
		if(count($res1) > 0)
			$res = true;
		return $res;
	}


	function next_order_number($user=0, $period=0)
	{
		$orderid = '';
		$res=$this->db->get_rows("SELECT max(substring_index(substring_index(id, '-', 2), '-', -1)) as idRes FROM ".TABLE_ORDERS." where user_id='{$user}' and per='{$period}' and status in (1,3) order by data desc");
		if(count($res) > 0)
		{
			$oid = $res[0]['idRes'];
			$exists = true;
			$exists_prd = true;
			do
			{
				$oid += 1;
				$orderid = $user . '-'. $oid . '-' . $period;
				$exists = $this->exists_in_orders($orderid);
				$exists_prd = $this->exists_in_order_details($orderid);
			}
			while($exists === true || $exists_prd === true);
		}
		return $orderid;
	}

	function cur_price($value)
	{
		$crcs = $this->db->get_rows("SELECT * FROM ".TABLE_CURRENCY."");
		return $crcs;
	}


	function addin_products($sort = ' id ASC', $limit = '')
	{
		$fl = '';
		$poc = $this->filtered_ids;
		if(count($poc) > 0)
		{
			$fl = "&& id IN (".implode(', ', $poc).")";
		}
		$prds = array();
		$q="SELECT p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0) as ost,ifnull(tm1.tot,0)+p.reserv as ztot,ifnull(cr.ctot,0) as ctot,p.* from ".TABLE_PRODUCTS." p left join (select prd_id,sum(d.count+d.isdel-d.otgr) as tot from ".TABLE_ORDERS_PRD." d right join ".TABLE_ORDERS." o on o.id=d.order_id where o.status=1 group by prd_id) as tm1 on tm1.prd_id=p.id left join (select prdid,sum(cnt) as ctot from ".TABLE_CART_DET." dc right join ".TABLE_CART." oc on oc.id=dc.cartid where oc.date>(UNIX_TIMESTAMP()-60*".$this->sets['cart_res_time'].") group by prdid) as cr on cr.prdid=p.id WHERE (((enabled=1 && visible=1 && srcid=0) or (srcid=id)) && ((p.sklad+p.zakazpost-p.reserv-ifnull(tm1.tot,0)-ifnull(cr.ctot,0)>0 and p.saletype='1') or p.saletype='0')) and new = 1 ORDER BY ".$sort." ".$limit."";
		$prds = $this->db->get_rows($q);
		
//		$prds = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE new = 1 ".$fl." && enabled='1' && visible='1' ORDER BY ".$sort." ".$limit."");
		return $prds;
	}


	function get()
	{
		switch ($_GET['type'])
		{
			case 'temperature' :
			{
				$html = $this->view('trm/main');
				$content = array (
					'html' => $html,
					'meta_title' => 'Температура',
					'meta_keys' => 'Температура',
					'meta_desc' => 'Температура',
					'path' => 'Температура'
				);
				break;
			}
			case 'yml' :
			{
				include ROOT_DIR.'modules/yml.class.php';
				$yml = new YML($this->db);
				$yml->d($shop = array(
					'name'=>SITE_NAME,
					'company'=>SITE_NAME,
					'url'=>SITE_URL
				));
				$yml->get();
				exit;
				break;
			}

			case 'product' :                                  //---------------------       Карточка товара
			{
				$path = '';
				$product = '';
				$cat = $this->db->get(TABLE_PRODUCTS, $_GET['id']);
				if($_GET['id'] < 0 || !is_numeric($_GET['id']) ||
					count($cat) == 0 || empty($cat) || in_array($cat['cat_id'], $this->disabled) || $cat['visible'] == 0){
                    error_log("Call 404 from ishop2.php: 814");
					$product = $this->_404();
				}
				else{
					$this->get_menu($cat['cat_id'], $this->getMenuRows());
					if(!isset($_SESSION[CART])){
					    error_log("Clear CARD from IS:820");
					//    echo "Clear CARD from IS:820";
						$_SESSION[CART] = Array(); //TODO empty cart
					}
					if(!empty($_POST['rate_send'])){
						$this->db->insert(TABLE_RATING, array(
								'value' => $_POST['rate'],
								'date' => time(),
								'products_id' => $_GET['id'],
								'ip' => $_SERVER['REMOTE_ADDR']
							));
						$this->redirect("ishop/product/".$_GET['id']);
					}
					if(!empty($cat['vlink']) && $this->sets['cpucat']==1 && $cat['vlink'] != substr($_SERVER['REQUEST_URI'], 1))
					{
					    error_log("Fatal in iShop2.php:832");
						header("HTTP/1.1 301 Moved Permanently");
						$this->redirect($cat['vlink']);
						exit();
					}

					if(count($cat) > 0){
						if(!($_SERVER['REMOTE_ADDR']==$this->sets['ofip'])) 
							$this->db->update(TABLE_PRODUCTS,$cat['id'],array('views'=>$cat['views']+1));
						$path = $this->get_pathnm($cat['cat_id']);
						$product = $this->view('ishop/productnew', array('cat'=>$cat, 'sets'=>$this->sets,'top_cat'=>$this->top_cat));
						$path = $path.' » <span class="curr_page">'.htmlspecialchars($cat['title'],ENT_COMPAT | ENT_XHTML,'cp1251').'</span>';
					}
				}
				$content = array (
					'html' => $product,
					'meta_title' => (!empty($cat['title'])) ? $cat['metatitle'] : '',
					'meta_keys' => (!empty($cat['metakeys'])) ? $cat['metakeys'] : '',
					'meta_desc' => (!empty($cat['metadesc'])) ? $cat['metadesc'] : '',
					'path' => $path
				);
				break;
			}
			case 'cart' :  // корзина
			{
			    if(isset($_SESSION["currentPromoCode"]) && $_SESSION["currentPromoCode"] != null)
                {
                    if (isset($_SESSION['user']) && $_SESSION['user'] != null)
                    {
                        PromoEngine::Instance()->checkPromoForUser($_SESSION['user']);
                    }
                }
                //if(!count($_POST) > 0) { //TODO get promo code
                 //   $message = "POST IS FULL";
                    //echo "<script type='text/javascript'>alert('$message');</script>";
                 //   Logger::Info($message);
                 //   Logger::InfoKeyValue($_POST);
                 //   if(isset($_POST["promo"]))
                 //   {
                  //      $message = "PROMO FORM IS HERE";
                        //echo "<script type='text/javascript'>alert('$message');</script>";
                   //     Logger::Info($message);
                   //     if(!empty($_POST["promo"]))
                   //     {
                            if(isset($_POST["rabatt"]))
                            {
                                $_SESSION["promoError"] = null;
                                $message = "PROMO FIELD IS HERE";
                                Logger::Info($message);
                              //  echo "<script type='text/javascript'>alert('$message');</script>";
                                if(!empty($_POST["rabatt"]))
                                {
                                    $message = "PROMO CODE IS: ".$_POST["rabatt"];
                                    Logger::Info($message);
                                    $_SESSION["promoError"] = null;
                                    if(PromoEngine::Instance()->isValidPromoCode($_POST["rabatt"]))
                                    {
                                        Logger::Info("Promo code ".$_POST["rabatt"]." is valid");
                                        $promo = PromoEngine::Instance()->getPromoByCode($_POST["rabatt"]);
                                        if(isset($promo) && $promo != null)
                                        {
                                            if (isset($_SESSION['user']))
                                            {
                                                $_SESSION["currentPromo"] = $promo;
                                                $_SESSION["currentPromoCode"] = $promo->getPromoCode();
                                                PromoEngine::Instance()->checkPromoForUser($_SESSION['user']);
                                            }
                                            else
                                            {

                                                $_SESSION["currentPromo"] = $promo;
                                                $_SESSION["currentPromoCode"] = $promo->getPromoCode();
                                                if(isset($_SESSION["currentPromo"]))
                                                {
                                                    Logger::Info("Promo saved in session");
                                                    $_SESSION["promoError"] = null;
                                                }
                                                else
                                                {
                                                    $_SESSION["promoError"] = "Ошибка сохранения кода";
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $_SESSION["promoError"] = "Введен неверный промо код";
                                        Logger::Info("Promo code ".$_POST["rabatt"]." is invalid");
                                    }

                                //    echo "<script type='text/javascript'>alert('$message');</script>";
                                }
                            }
                            else
                            {
                                $message = "FAILED TO GET PROMO FIELD";
                                Logger::Info($message);
                               // echo "<script type='text/javascript'>alert('$message');</script>";
                            }
                      //  }
                 //   }
                  //  else
                  //  {
                  //      $message = "FAILED TO GET PROMO FORM";
                  //      Logger::Info($message);
                       // echo "<script type='text/javascript'>alert('$message');</script>";
                  //  }

               // }
              //  else
               // {
               //     $message = "POST IS EMPTY";
                    //echo "<script type='text/javascript'>alert('$message');</script>";
               //     Logger::Info($message);
               // }
				if(empty($_SESSION[CART])) {
                    error_log("Clear CARD from IS:861");
                  //  echo "Clear CARD from IS:861";
				    $_SESSION[CART] = array();
                } //TODO empty cart
				if(count($_POST) > 0)
				{
					if (!empty($_POST['ch_cart']))
					{
						Cart::gI()->update_cart();
						$this->redirect("ishop/cart");
					}
				}
				foreach($_SESSION[CART] as $id=>$param)
				{
					if($_SESSION[CART][$id]['count'] == 0)
					{
                        error_log("Clear CARD from IS:877");
                  //      echo "Clear CARD from IS:877";
						unset($_SESSION[CART][$id]); //TODO empty cart
					}
				}
				if (!empty($_POST['order']) && !empty($_SESSION[CART]) && count($_SESSION[CART]) > 0)
				{
					if(!empty($_POST['dostavka']))
					{
						$_SESSION['dostavka'] = 1;
					}
					else
					{
						unset($_SESSION['dostavka']);
					}
					$this->redirect("ishop/order");
				}
				$html_cart = '';
				if (!empty($_SESSION[CART]) && count($_SESSION[CART]) > 0)
				{
					$prd = Cart::gI()->get_cart_info();
					$summa = Cart::gI()->cart_sum;
					$html_cart = $this->view('ishop/my_cartw', array('prd'=>$prd, 'summa'=>$summa));
					
				}
				else
				{
					$html_cart .= '<div class="col-md-12  pt50 pb50" id="cart_frm">
                    <div class="area-title bdr">
                        <h2>ВАША КОРЗИНА, к сожалению, пуста..</h2>
                    </div>';
				}
				$content = array (
					'html' => $html_cart,
					'meta_title' => 'Корзина',
					'meta_keys' => 'Корзина',
					'meta_desc' => 'Корзина',
					'left_menu' => false,
					'path' => 'Корзина'
				);
				break;
			}
			case 'advsearch' :
			{
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				if ($page<0) $page=1;
				if(!empty($_POST['advs_sbm']))
				{
					$sparams = array(
						'param_vid' => array('Вид', 'select'),
						'param_gruppa' => array('Группа', 'select'),
						'param_sort' => array('Сорт', 'select'),
						'param_proizvoditel' => array('Производитель', 'select'),
						'param_visota' => array('Высота, см', 'ot_do'),
						'param_shirina' => array('Ширина, см', 'ot_do'),
						'param_aromatdlyapoiska' => array('Аромат', 'select'),
						'param_tsvetenie' => array('Цветение', 'select'),
						'param_razmertsvetka' => array('Размер цветка, см', 'select', array(1 => 'менее 3', '3 – 5', '6 – 9', '10 - 15', 'более 15')),
						'param_tiptsvetka' => array('Тип цветка', 'select'),
						'param_periodtsveteniya' => array('Период цветения', 'select'),
						'param_okraskatsvetkadlyapoiska' => array('Окраска цветка', 'select'),
						'param_svetovoyrezhim' => array('Световой режим', 'select'),
						'param_standartpostavki' => array('Стандарт поставки', 'select'),
						'tsena' => array('Цена', 'ot_do')
					);
					$_POST['asearch_str'] = '';
					foreach($sparams as $id=>$val)
					{
						if($val['1'] == 'select')
						{
							if(!empty($_POST[$id]))
							{
								if(!empty($val['2']))
								{
									switch($_POST[$id]) {
										case 1: {
											$sr[] = "".$id." < 3 && ".$id." > 0"; break;
										}
										case 2: {
											$sr[] = "".$id." >= 3 && ".$id." <= 5"; break;
										}
										case 3: {
											$sr[] = "".$id." >= 6 && ".$id." <= 9"; break;
										}
										case 4: {
											$sr[] = "".$id." >= 10 && ".$id." <= 15"; break;
										}
										case 5: {
											$sr[] = "".$id." > 15"; break;
										}
									}
								}
								else
								{
									if(!empty($_POST[$id]))
									{
										$sr[] = "".$id." LIKE ".quote_smart('%'.$_POST[$id].'%')."";
									}
								}
							}
						}
						elseif($val['1'] == 'ot_do')
						{
							if(!empty($_POST[$id]['0']))
							{
								$sr[] = "".$id." >= ".quote_smart($_POST[$id]['0'])."";
							}
							if(!empty($_POST[$id]['1']))
							{
								$sr[] = "".$id." <= ".quote_smart($_POST[$id]['1'])."";
							}
						}
					}
					$sr[]= 'visible = 1';
					if (!isset($_POST['f_all'])) $sr[]= 'enabled = 1';

					$_POST['asearch_str'] = implode(' && ', $sr);
					if (!isset($_POST['asearch_str'])) {
					$html = $this->view('ishop/adv_srch', array('info'=>1));
					$content = array (
						'html' => $html,
						'meta_title' => 'Поиск',
						'meta_keys' => 'Подбор растений по параметрам',
						'meta_desc' => 'Подбор растений по параметрам',
						'path' => 'Подбор растений по параметрам'
						);
					break;
					}
				}
				$prd_list = '';
				$sort_params = array(
					'0' => array('name' => 'title', 'descr' => 'названию', 'sort'=> 'asc'),
					'1' => array('name' => 'tsena', 'descr' => 'цене', 'sort'=> 'asc'),
					'2' => array('name' => 'views', 'descr' => 'популярности', 'sort'=> 'desc')
				);
				if(!isset($_SESSION['sort']) || !isset($sort_params[$_SESSION['sort']]))
				{
					$_SESSION['sort'] = 0;
				}
				if(!empty($_POST['asearch_str']) || !empty($_SESSION['last_search']))
				{
					$search_str = (!empty($_POST['asearch_str'])) ? $_POST['asearch_str'] : $_SESSION['last_search'];
					if(PAGES != 0)
					{
						$prd_count = $this->getPrdCountInSearch($search_str);
						$c_pages = ceil($prd_count/PAGES);
					}
					$limit = ($page == 0 || PAGES == 0) ? '' : "LIMIT ".(($page-1)*PAGES).",".PAGES;
					$prd_list.='Найдено '.$prd_count.' товаров.<br/>';
					$prds = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE ".$search_str." ORDER BY ".$sort_params[$_SESSION['sort']]['name']." ".$sort_params[$_SESSION['sort']]['sort']." ".$limit."");
					
					if($this->sets['mod_search']) $prd_list .= $this->view('ishop/adv_search');
					if($this->sets['mod_sort'])
					{
						$prd_list .= '<div class="sort_panel">Сортировать по: ';
						for($i=0; $i<count($sort_params);$i++)
						{
							if($i==$_SESSION['sort']) $prd_list .=  '<b>';
							$prd_list .= '<a href="'.SITE_URL.'ishop/sort/'.$i.'">'.$sort_params[$i]['descr'].'</a>';
							if($i==$_SESSION['sort']) $prd_list .=  '</b>';
							if($i!=count($sort_params)-1) $prd_list .=  ', ';
						}
						$prd_list .= '</div>';
					}
					if(PAGES_TOP_ENABLED)
					{
						$prd_list .= get_pages(
							array (
								'class' => 'prd_pages_top',
								'count_pages' => $c_pages,
								'curr_page'=> $page,
								'link' => 'ishop/advsearch/'
							)
						);
					}
					if(!empty($_POST['asearch_str']))
					{
						$_SESSION['last_search'] = $_POST['asearch_str'];
						$prd_list .=$this->getProductListing($this->prd_list_style, $prds);
					}
					elseif(!empty($_SESSION['last_search']))
					{
						$prd_list .=$this->getProductListing($this->prd_list_style, $prds);
					}
					else
					{
						$html = 'Ничего не найдено';
					}
//					if($this->sets['mod_prd_vs']) $prd_list .= $this->view('ishop/sravnenie');
					if(PAGES_BOTTOM_ENABLED)
					{
						$prd_list .= get_pages(
							array (
								'class' => 'prd_pages_bottom',
								'count_pages' => $c_pages,
								'curr_page'=> $page,
								'link' => 'ishop/advsearch/'
							)
						);
					}
				}
				$content = array (
					'html' => $prd_list,
					'meta_title' => 'Поиск',
					'meta_keys' =>  'Поиск',
					'meta_desc' => 'Поиск',
					'path' => 'Результаты поиска'
				);
				break;
			}
			case 'advs' :
			{
					$html = $this->view('ishop/adv_srch', array('info'=>0));
					$content = array (
						'html' => $html,
						'meta_title' => 'Поиск',
						'meta_keys' => 'Подбор растений по параметрам',
						'meta_desc' => 'Подбор растений по параметрам',
						'path' => 'Подбор растений по параметрам'
					);
				break;
			}
			case 'order' :                                 //  Оформление заказа
			{
                IF (isset($_POST['w_oreg']) && $_POST['w_oreg']=="Jk"){
                	$_SESSION['user_vo']=1;
                	unset($_POST['w_oreg']);
                }
				$sumzak=0;
				if (!empty($_SESSION['user']))	{
					$rows = $this->db->get_rows("SELECT sum(od.summa) as ss FROM ".TABLE_ORDERS_PRD." od JOIN ".TABLE_ORDERS." o ON od.order_id = o.id  WHERE o.user_id = ".quote_smart($_SESSION['user'])." and o.status<4 and od.isdel=0 and od.count>od.otgr");
					$sumzak=$rows['0']['ss'];
				}
				$prda = Cart::gI()->get_cart_ord();
				$summa = $prda['summa'];
				if ($prda['count']==0) {
					$content = array (
						'html' => 'Не найдены товары к оформлению.',
						'meta_title' => 'Оформление заказа',
						'meta_keys' => 'Оформление заказа',
						'meta_desc' => 'Оформление заказа',
						'path' => 'Оформление заказа'
					);
					break;
				}
				$sumzak =$sumzak+$summa;
/*				$spdost=array();
				$spdost[]="САМОВЫВОЗ";*/
				$data=array();
				if ($sumzak>=950) $spdost=explode("\n", $this->ebox['spdst']);
/*				else $errmsg='Доставка заказов на сумму менее 1000руб. не осуществляется!<br>У Вас есть возможность самостоятельно забрать Ваш заказ <a href="site/2">со склада в Московской области</a><br>('.$this->ebox['adr'].').';*/
				else $errmsg='Доставка заказов на сумму менее 1000 руб. не осуществляется!';
				if ($sumzak>=950)
				$form_data = array(
					'lname'=> array('Фамилия', 'text', 300, 3),
					'fname'=> array('Имя', 'text', 300, 2),
					'sname'=> array('Отчество', 'text', 300, 3),
					'tel' => array('Телефон c кодом города', 'phone', 300, 1),
					'sms' => array('Я согласен(на) получать СМС уведомления.', 'checkbox', 350, 0),
					'email' => array('E-mail', 'email', 300, 1),
					'emluv' => array('Я согласен(на) получать E-mail уведомления об изменении состояния заказа.', 'checkbox', 300, 0),
					'emlnv' => array('Я не согласен(на) получать E-mail уведомления о новостях.', 'checkbox', 300, 0),
					'adr' => array('Адрес доставки', 'textarea', 350, 1),
					'comm' => array('Дополнительная информация', 'textarea', 350, 0),
					'types' => array('Способ доставки', 'select', 300, $spdost, 0),
					'spo' => array('Способ оплаты', 'select', 300, explode("\n", $this->ebox['spopl']), 0),
					'br'=>'br',
					'agree' => array('Я ознакомлен(на) и согласен(на) с <a class="fancybox fancybox.ajax" href="'.SITE_URL.'ajax/terms">условиями работы</a> нашего магазина.', 'checkbox', 300, 1,"Для продолжения Вы должны согласиться с условиями работы!"),
					
				);
				if(!empty($this->sets['mod_order_reg']) && !isset($_SESSION['user'])){
					$order_html = 'Для оформления заказа необходимо <a href="user/reg">зарегистрироватся</a> или пройти авторизацию.';
				}
               	if  (!isset($_SESSION['user'])  && (!isset($_SESSION['user_vo']) || $_SESSION['user_vo']!=1)){
					$order_html =$this->view('ishop/order_noreg');
				}
				else {
					define('ORDER_NEW_STYLE', 1);
					$order_html = '';
					if (!isset($_SESSION['ok'])) $_SESSION['ok'] = 0;
					if ($_SESSION['ok'] == 1){
	                	unset($_SESSION['user_vo']);
						$order_html .= $this->view('ishop/order_ok');
					}
					if (!empty($_POST['posted']) && $prda['count'] > 0){
						$tcont = '<div>';
						$fio=(!empty($_POST['plname'])) ? $_POST['plname'] : '';
						$fio.=(!empty($_POST['pfname'])) ? ' '.$_POST['pfname'] : '';
						$fio.=(!empty($_POST['psname'])) ? ' '.$_POST['psname'] : '';
						$tcont.="Покупатель: <span style=\"font-weight:bold\">$fio</span><br/>";
						$tcont.="E-mail: <span style=\"font-weight:bold\">{$_POST['pemail']}</span><br/>";
						$tcont.="Телефон: <span style=\"font-weight:bold\">{$_POST['ptel']}</span><br/>";
						$tcont.="Способ доставки: <span style=\"font-weight:bold\">{$_POST['ptypes']}</span><br/>";
						$tcont.="Адрес доставки: <span style=\"font-weight:bold\">{$_POST['padr']}</span><br/>";
						$tcont.="Способ оплаты: <span style=\"font-weight:bold\">{$_POST['pspo']}</span><br/>";
						$tcont.="Получать СМС уведомления: <span style=\"font-weight:bold\">".(($_POST['psms'])?'Да':'Нет')."</span><br/>";
						$tcont.="Получать E-mail уведомления: <span style=\"font-weight:bold\">".(($_POST['pemluv'])?'Да':'Нет')."</span><br/>";
						$tcont.="Получать новсти на E-mail: <span style=\"font-weight:bold\">".((isset($_POST['pemlnv']))?'Да':'Нет')."</span><br/>";
						$tcont.="<span style=\"font-weight:bold\">Я ознакомлен(на) и согласен(на) с условиями работы интернет-магазина.&nbsp;".date('d.m.Y')."</span><br/>";
						if (isset($_POST['pcomm']) && !empty($_POST['pcomm'])) 	$tcont.="Дополнительная информация:  <span style=\"font-weight:bold\">{$_POST['pcomm']}</span>";
						$tcont.='</div>';
						if(!empty($_SESSION['card_nomer'])) $tcont .= '<p>Дисконтная карта: '.$_SESSION['card_nomer'].'</p>';
						$ssum=0;
						$sale=0;
						$summacn=$summa;
						if (isset($_SESSION['user']) && $_SESSION['user']>0){
							$orders1 = $this->db->get_rows("SELECT SUM(summa*(100-skidka)/100) as summa FROM ".TABLE_ORDERS." WHERE user_id = '".$_SESSION['user']."' && status != 6");
							if(!empty($this->sets['mod_prd_skidka']) && !empty($orders1['0']['summa'])){
								$orders = $this->db->get_rows("SELECT percent FROM ".TABLE_DISCOUNTS." WHERE start <= ".$orders1['0']['summa']." && end > ".$orders1['0']['summa']."");
							}
							if ($_SESSION['user']>0) $sale=User::gI()->user['sale'];
							if ($orders['0']['percent']>$sale) $sale=$orders['0']['percent'];

                            $promoValue = PromoEngine::Instance()->getPromoValueAssignedToUser($_SESSION['user']);
                            $sale += $promoValue;

							$summacn=$summa - ($summa*$orders['0']['percent'])/100;

						}
						$tdt=time();
						$dataord=array('user_id' => (!empty($_SESSION['user'])) ? $_SESSION['user'] : 0,
							'summa' => $summa,
							'fio' => $fio,
							'tel' => (!empty($_POST['ptel'])) ? $_POST['ptel'] : '',
							'email' => (!empty($_POST['pemail'])) ? $_POST['pemail'] : '',
							'adr' => (!empty($_POST['padr'])) ? $_POST['padr'] : '',
							'dost' => (!empty($_POST['ptypes'])) ? $_POST['ptypes'] : '',
							'opl' => (!empty($_POST['pspo'])) ? $_POST['pspo'] : '',
							'comment' => (!empty($_POST['pcomm'])) ? $_POST['pcomm'] : '',
							'sms' => (isset($_POST['psms'])) ? $_POST['psms'] : '0',
							'skidka' => $sale,
							'emluv' => (isset($_POST['pemluv'])) ? '1' : '0');
						if (!isset($_POST['pemlnv']) && !empty($_POST['pemail'])){
							$this->db->SetErrMsgOn(false);
							$this->db->insert(TABLE_PODPISKA, array('email' => $_POST['pemail'],'mkey'=>md5($_POST['pemail']),'date' => $tdt));
							$this->db->SetErrMsgOn();
						}
						$prd=$prda['tov'];
						$prdout=array();
						$garray=array();
						$tz='';
						$tp=0;
						$tsum=0;
						$needsave=false;
						$ordsp='';
						$this->db->insert(TABLE_ORDERS_I, array(
							'data' => $tdt,
							'user_id' => (!empty($_SESSION['user'])) ? $_SESSION['user'] : 0,
							'summa' => $summa,
							'fio' => $fio,
							'tel' => (!empty($_POST['ptel'])) ? $_POST['ptel'] : '',
							'email' => (!empty($_POST['pemail'])) ? $_POST['pemail'] : '',
							'comment' => (!empty($_POST['pcomm'])) ? $_POST['pcomm'] : '',
							'skidka' => $sale
						));
						$lord=$this->db->insert_id();
						foreach($prd as $param){
							if (empty($tz) || $tp!=$param['tper']){
								if ($needsave){
									$res=$this->db->get_rows("select sum((summa-summa*skidka/100)*count) as summas,sum(summa*count) as summa from ".TABLE_ORDERS_PRD." where order_id=".$this->db->escape_string($tz));
									$dataord['summa']=$res[0]['summa'];	
									$dataord['skidka']=100-$res[0]['summas']*100/$res[0]['summa'];
									$dataord['comment'] = (!empty($_POST['pcomm'])) ? $_POST['pcomm'] : '';
									$dataord['datan']=$tdt;
									$this->db->update(TABLE_ORDERS,$last_id,$dataord);
									$prdout[$tz]['sum']=$tsum;
									$prdout[$tz]['totsum']=$res[0]['summas'];
								} 
								$tsum=0;
								$last_idt=$this->generate_orderid((!empty($_SESSION['user'])) ? $_SESSION['user'] : 0,$param['tper'],(!empty($_POST['pemail'])) ? $_POST['pemail'] : '');
								if ($last_idt===FALSE) {$this->redirect(SITE_URL);exit;}
								$last_id=$last_idt['id'];
								$cnt=$last_idt['kodstr'];
								$tz=$last_id;
								$tp=$param['tper'];
								$prdout[$tz]['ha']=$last_idt['ha'];
								$prdout[$tz]['new']=$last_idt['new'];
								$prdout[$tz]['tp']=$tp;
								$needsave=true;
								$ordsp.=(empty($ordsp))?'':',';
								$ordsp.=$tz.($last_idt['new']?'':'доп');
							}
							$prdout[$tz]['tov'][]=$param;
							$ins_data = array(
								'kodtov' => $param['param_kodtovara'],
								'kodstr' => $cnt,
								'prd_id' => $param['prdid'],
								'name' => $param['name'],
								'count' => $param['cnt'],
								'summa' => $param['price'],
								'order_id' => $tz,
								'skidka' => $sale
							);
							$gd=array();
							$gd=$ins_data;
							$gd['catname']=$param['catname'];
							$garray[]=$gd;
							$tsum+=$this->skidka($ins_data['summa']*$param['cnt'],$sale);
							$ireg=array('orderid'=>$tz,
							'prdid' => $param['prdid'],'kodstr' => $cnt,
							'cnt' => $param['cnt'],'price' => $param['price'],'sale' => $sale,'isins' => 1);
							if (!$last_idt['new'] && $last_idt['ld']==1){
								$ins_data['todel']=999; 
							} else {
								$ireg['isins']=3;
							}
							$this->db->insert(TABLE_ORDERS_REG, $ireg);
							$this->db->insert(TABLE_ORDERS_PRD, $ins_data);
							$ireg['data']=$tdt;
							$this->db->insert(TABLE_ORDERS_LOG, $ireg);
							$ins_data = array(
								'prd_id' => $param['prdid'],
								'count' => $param['cnt'],
								'summa' => $param['price'],
								'order_id' => $lord,
								'skidka' => $sale,
								'ord'=>$tz
							);
							$this->db->insert(TABLE_ORDERSI_PRD, $ins_data);
							$cnt=$cnt+1;
							
						}
						if ($needsave){
							$res=$this->db->get_rows("select sum((summa-summa*skidka/100)*count) as summas,sum(summa*count) as summa from ".TABLE_ORDERS_PRD." where order_id=".$this->db->escape_string($tz));
							$dataord['summa']=$res[0]['summa'];
							$dataord['skidka']=100-$res[0]['summas']*100/$res[0]['summa'];
							$dataord['comment'] = (!empty($_POST['pcomm'])) ? $_POST['pcomm'] : '';
							$dataord['datan']=$tdt;
							$this->db->update(TABLE_ORDERS,$last_id,$dataord);
							$prdout[$tz]['sum']=$tsum;
							$prdout[$tz]['totsum']=$res[0]['summas'];
						} 
						require_once INC_DIR.'PHPMailer/class.phpmailer.php';
						$cont = $this->view('ishop/sendmail_cart', array('prdout'=>$prdout,'sale'=>$sale));
																		// отправляем сообщение себе						
						$mail = new PHPMailer();
						$mail->CharSet='UTF-8';
						$mail->IsSMTP(); // telling the class to use SMTP
						$mail->SMTPSecure='tls';
						$mail->SMTPAuth   = true;                  // enable SMTP authentication
						$mail->Host       = $this->sets['SMTPHost']; // SMTP server
						$mail->Port       = $this->sets['SMTPPort'];                    // set the SMTP port for the GMAIL server
						$mail->Username   = $this->sets['SMTPUser']; // SMTP account username
						$mail->Password   = $this->sets['SMTPPass'];        // SMTP account password
						$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
						$mail->SetFrom($this->sets['SMTPUser'],"Оформление заказа");
						$mail->AddAddress($this->sets['ishop_mail'], '');
						$mail->Subject ='Заказ на сайте № '.$ordsp;
						$mail->IsHTML(true);
						
						$mail->Body=mailhead_a("Заказы {$ordsp} от ".date('d.m.Y')."<br/>{$tcont}{$cont}");
						@$mail->Send();
													// отправляем сообщение клиенту					
						$tcont=mailhead_a(Sitemenu::gI()->get_html(11).$tcont.$cont,'UTF-8','?utm_source=email_transaction&utm_medium=email&utm_campaign=orderinfo');
						$tcont=str_replace('="data/','="http://www.chudoclumba.ru/data/',$tcont);
						$tcont=str_ireplace('{Name}',$fio,$tcont);
						$mail = new PHPMailer();
						$mail->CharSet='UTF-8';
						$mail->IsSMTP(); // telling the class to use SMTP
						$mail->SMTPAuth   = true;                  // enable SMTP authentication
						$mail->SMTPSecure='tls';
						$mail->Host       = $this->sets['SMTPHost']; // SMTP server
						$mail->Port       = $this->sets['SMTPPort'];                    // set the SMTP port for the GMAIL server
						$mail->Username   = $this->sets['SMTPUser1']; // SMTP account username
						$mail->Password   = $this->sets['SMTPPass1'];        // SMTP account password
						$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
						$mail->AddReplyTo($this->ebox['email'],'Отдел заказов');
						$mail->SetFrom($this->sets['SMTPUser1'], "Оформление заказа");
						$mail->AddAddress($_POST['pemail'], $_POST['pemail']);
						$mail->Subject    = 'ИМ "Чудо-Клумба". Заказ на сайте № '.$ordsp;
						$mail->IsHTML(true);
						$mail->Body=$tcont;
						@$issend=$mail->Send();
						if(!empty($this->sets['mod_order_reg']) && Reg::gI()->is_logged())
							Reg::gI()->update(array('fio'=>$_POST['name'],'email'=>$_POST['em'],'tel'=>$_POST['tel'],'adr'=>$_POST['ad']));
						Cart::gI()->clear_cart();
						$_SESSION['ok'] = 1;
	                	unset($_SESSION['user_vo']);
						$order_html=$this->view('ishop/order_ok_info', array('prdout' => $prdout,'issend'=>$issend,'mail'=>$_POST['pemail']));;
						$summacn=0;
						$id='';
						foreach($prdout as $key=>$val){
							$summacn+=$val['sum'];
							if ($val['new'] || empty($id)) $id=$key; 
						}
						unset($_SESSION['payment']);
						$order_html.= $this->view('ishop/order_ok', array('summacn' => $summacn,'last_id'=>$ordsp,'id'=>$id,'opl'=>$_POST['pspo']));
						$order_html.=$this->view('ishop/yanalitics', array('summacn' => $summacn,'last_id'=>$ordsp,'garray'=>$garray));
						$order_html.=$this->view('ishop/ganalitics', array('summacn' => $summacn,'last_id'=>$ordsp,'garray'=>$garray));
						if (!(strpos($_POST['pspo'],'QIWI Кошелек')===FALSE))
							$order_html .=$this->view('ishop/qiwi1', array('sum' => round($summacn,2),'id'=>$ordsp,'tel'=>$dataord['tel']));
						
					}else{
						if (Cart::gI()->cart_cnt == 0 && $_SESSION['ok'] != 1)
							$this->redirect('ishop/cart');
						else{
							if ($_SESSION['ok'] != 1){
								if(!empty($_POST['order']) && Cart::gI()->cart_cnt > 0) $order_html .= '<div style="color:red; font-size:14px; padding:10px 0px;">Некорректно заполнены поля</div>';
								$dr = array();
								if(class_exists('User') && !empty($_SESSION['user'])){
									$usr = User::gI()->user;
									$inf = unserialize($usr['info']);
									$dr = array(
										'email' => $usr['login'],
										'tel' => $inf['14'],
										'lname' => $inf['9'],
										'fname' => $inf['8'],
										'sname' => $inf['11'],
										'adr' => $inf['19'].', '.$inf['17'].', '.$inf['12'].', '.$inf['18'].', '.$inf['20'],
										'sms'=>1,'emluv'=>1
									);
								}
								if (count($dr)==0) $dr=array('sms'=>1,'emluv'=>1);
								if ($sumzak>=950)
								$order_html .= $this->view('feedback/feedback_new', array('form_data'=>$form_data,'txt_data'=>$dr, 'position' => 'top','sumzak' => $sumzak,'errmsg'=>$errmsg,'header'=>'Контактные данные и адрес доставки','btn_name'=>'Оформить заказ'));
								else $order_html .= $this->view('feedback/feedback_new2', array('form_data'=>$form_data,'txt_data'=>$dr, 'position' => 'top','sumzak' => $sumzak,'errmsg'=>$errmsg,'header'=>'Контактные данные и адрес доставки','btn_name'=>'Оформить заказ'));
								$order_html.='<script>$(document).ready(function() {$(".fancybox").fancybox({maxWidth:800,maxHeight:900,	fitToView:false,
width:\'90%\',height:\'90%\',autoSize:false,closeClick:false,openEffect	: \'none\',	closeEffect	: \'none\'});});</script>';

								
							}
						}
					}
					if ($_SESSION['ok'] == 1)  unset($_SESSION['ok']);
				}
				$content = array (
					'html' => $order_html,
					'meta_title' => 'Оформление заказа',
					'meta_keys' => 'Оформление заказа',
					'meta_desc' => 'Оформление заказа',
					'path' => 'Оформление заказа',
					'left_menu'=>false
				);
				break;
			}
			case 'add_filter' :
			{
				$gi = explode('_', $_GET['id']);
				$_SESSION['filter_id'] = $gi['0'];
				$_SESSION['filter'][$gi['1']] = $gi['2'];
				$this->redirect('ishop/'.$gi['0'].'');
				break;
			}
			case 'add_vs' :
			{
				$_SESSION['vs_prds'][] = $_GET['id'];
				$_SESSION['vs_prds'] = array_unique($_SESSION['vs_prds']);
				echo count($_SESSION['vs_prds']);
				exit;
				break;
			}
			case 'show_prd_vs' :
			{
				if(count($_SESSION['vs_prds']) > 0)
				{
					$prds = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE id IN (".implode(', ', $_SESSION['vs_prds']).")");
					$prd_list = $this->getProductListing((($this->sets['mod_prd_dsc']) ? 'products_listing_sd' : $this->prd_list_style), $prds,
					array(
						'icon' => 0,
						'vs_delete' => 1
					));
				}
				else
				{
					$prd_list = 'Нет товаров к сравнению';
				}
				$content = array (
					'html' => $prd_list,
					'meta_title' => 'Добавлено к сравнению',
					'meta_keys' =>  'Добавлено к сравнению',
					'meta_desc' => 'Добавлено к сравнению',
					'path' => 'Добавлено к сравнению'
				);
				break;
			}
			case 'show_remove_prd_vs' :
			{
				foreach($_SESSION['vs_prds'] as $id=>$val)
				{
					if($val == $_GET['id']) unset($_SESSION['vs_prds'][$id]);
				}
				if(count($_SESSION['vs_prds']) > 0)
				{
					$prds = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE id IN (".implode(', ', $_SESSION['vs_prds']).")");
					$prd_list = $this->getProductListing((($this->sets['mod_prd_dsc']) ? 'products_listing_sd' : $this->prd_list_style), $prds,
					array(
						'icon' => 0,
						'vs_delete' => 1
					));
					echo $prd_list;
				}
				else
				{
					echo 'Нет товаров к сравнению';
				}
				exit;
				break;
			}
			case 'del_filter' :
			{
				$gi = explode('_', $_GET['id']);
				unset($_SESSION['filter'][$gi['1']]);
				$this->redirect('ishop/'.$gi['0'].'');
				break;
			}
			case 'clear_filter' :
			{
				unset($_SESSION['filter']);
				$this->redirect('ishop/'.$_GET['id'].'');
				break;
			}
			case 'prd_vs' :
			{
				if(!$this->sets['mod_prd_vs']) exit;
				$ids_ar = array_unique($_SESSION['vs_prds']);
				if(count($_SESSION['vs_prds']) > 0){
				$prds = $this->db->get_rows("SELECT * FROM ".TABLE_PRODUCTS." WHERE id IN (".(implode(', ',$ids_ar)).")");
				$html = '<table class="prd_vs">';
				$prd_va_params = array(
					'Фото' => 'foto',
					'Наименование' => 'title',
					'Высота, см' => 'param_visota',
					'Ширина, см' => 'param_shirina',
					'Аромат' => 'param_aromat',
					'Цветение' => 'param_tsvetenie',
					'Размер цветка, см' => 'param_razmertsvetka',
					'Тип цветка' => 'param_tiptsvetka',
					'Период цветения' => 'param_periodtsveteniya',
					'Окраска цветка' => 'param_okraskatsvetka',
					'Окраска листвы' => 'param_okraskalistvi',
					'Морозостойкость' => 'param_morozostoykost',
					'мучнистая роса' => 'param_muchnistayarosa',
					'черная пятнистость' => 'param_chernayapyatnistost',
					'Тип почвы' => 'param_tippochvi',
					'Световой режим' => 'param_svetovoyrezhim',
					'Количество шт. в упаковке' => 'param_kolichestvo',
					'Стандарт поставки' => 'param_standartpostavki',
					'Цена' => 'tsena'
				);
				$chars = $this->db->get_rows("
				SELECT ".TABLE_CHARS.".name, ".TABLE_CHARS.".char_id
				FROM ".TABLE_PRODUCTS.", ".TABLE_CHARS."
				WHERE ".TABLE_PRODUCTS.".id=".$prds['0']['id']." && ".TABLE_CHARS.".cat_id = ".TABLE_PRODUCTS.".cat_id
				 ");
				$chars_values = $this->db->get_rows("SELECT * FROM ".TABLE_CHARS_VALUES." WHERE prd_id IN (".(implode(', ',$ids_ar)).")");
				$chars_vals = array();
				foreach($chars_values as $id=>$value){
					$chars_vals[$value['prd_id']][$value['char_id']] = $value['value'];
				}
				foreach($prd_va_params as $param_name=>$param){
					$html .= '<tr>';
					$html .= '<td>'.$param_name.'</td>';
					foreach($prds as $prd){
						if($param == 'foto'){
							$html .= '<td><img src="'.SITE_URL.'thumb.php?id='.$prd[$param].'&x=100" alt="" /></td>';
						}
						elseif($param == 'tsena'){
							$html .= '<td>'.$this->s_price($prd[$param],$prd['skidka']).'</td>';
						}
						else{
							$html .= '<td>'.$prd[$param].'</td>';
						}
					}
					$html .= '</tr>';
				}
					$html .= '<tr><td>Убрать из сравнения</td>';
					foreach($prds as $prd){
						$html .= '<td><span class="icon cancel" onclick="rmcmp('.$prd['id'].');location.href=\'ishop/prd_vs\';"></td>';
					}
					$html .= '</tr>';

				foreach($chars as $char_id=>$char){
					$html .= '<tr>';
					$html .= '<td>'.$char['name'].'</td>';
					foreach($prds as $prd){
						$value = (isset($chars_vals[$prd['id']][$char['char_id']])) ? $chars_vals[$prd['id']][$char['char_id']] : '';
						$html .= '<td>'.$value.'</td>';
					}
					$html .= '</tr>';
				}
				$html .= '</table>';
				}
				else{
					$html = 'Нет товаров к сравнению';
				}
				$content = array (
					'html' => $html,
					'meta_title' => 'Сравнение товаров',
					'meta_keys' =>  'Сравнение товаров',
					'meta_desc' => 'Сравнение товаров',
					'path' => 'Сравнение товаров'
				);
				break;
			}

			case 'filter' :
			{
				if(class_exists('Filter'))
				{
					$filter = Filter::gI();
					$content = $filter->get($this);
				}
				else
				{
                    error_log("Call 404 from ishop2.php: 1569");
					$this->_404();
				}
				break;
			}
			case 'addcart' :  // добавление товара в корзину. вход ajax
			{
				$rm=array(
					'res'=>2,
					'msg'=>'<div class="msg_cart" id="sid"><img src="'.TEMP_FOLDER.'images/attention.png" alt="" height="60" width="60" /><em>Внимание:</em>Товар не идентифицирован!</div>'
				);
				$id=0;
				if (isset($_GET['id']) && !empty($_GET['id'])) $id=$_GET['id'];
				if (!is_numeric($id)) $id=substr($id,5);
				if ($id>0){
						if (Cart::gI()->addprd($id,1)){
							$cnt='';
							if ($_SESSION[CART][$id]['count']>0) $cnt='(Уже '.$_SESSION[CART][$id]['count'].')';
							$rm=array(
								'res'=>1,
								'msg'=>'<div class="msg_cart" id="sid"><img src="'.TEMP_FOLDER.'images/ico_cartm.png" alt="" height="60" width="60" /><em>Корзина:</em>Товар добавлен в корзину.</div>',
								'cnt'=>$cnt,
								'cbtn'=>Cart::gI()->cart()
							);
						}else{
							$rm=array(
								'res'=>2,
								'msg'=>'<div class="msg_cart" id="sid"><img src="'.TEMP_FOLDER.'images/attention.png" alt="" height="60" width="60" /><em>Внимание:</em>Этот товар весь зарезервирован<br>и не может быть добавлен в корзину...</div>'
							);
						}
				}
				die(json_encode($rm)); 
				break;
			}
			case 'add' :  // добавление товара в корзину. (вход по ссылке)
			{
				
				if (Cart::gI()->cart_cnt==6 && !isset($_SESSION['carttr']) && empty($_SESSION['user']))
				{
					Cart::gI()->addprd($_GET['id'],3);
				}
				elseif (Cart::gI()->cart_cnt>=6 && !isset($_SESSION['carttr']) && empty($_SESSION['user']))
				{
					
				}
				elseif (!empty($_GET['id']))
				{
					//Cart::gI()->addprd($_GET['id'],0);
				}
				header("HTTP/1.1 302 Found");
				$this->redirect($_SERVER['HTTP_REFERER']);
				break;
			}
			case 'sort' :
			{
				$_SESSION['sort'] = empty($_GET['id']) ? 0 : $_GET['id'];
				$this->redirect($_SERVER['HTTP_REFERER']);
				break;
			}
			case 'additions' :
			{
				$page = ($_GET['id']>-1) ? $_GET['id'] : 1;
				$sort_params = array(
					'0' => array('name' => 'id', 'descr' => 'очереди', 'sort'=> 'desc'),
					'1' => array('name' => 'tsena', 'descr' => 'цене', 'sort'=> 'asc'),
					'2' => array('name' => 'views', 'descr' => 'популярности', 'sort'=> 'desc')
				);
				if(!isset($_SESSION['sort']) || !isset($sort_params[$_SESSION['sort']]))
				{
					$_SESSION['sort'] = 0;
				}
				$c_pages = 0;
				if(PAGES != 0)
				{
					$prd_count = 100;
					$c_pages = ceil($prd_count/PAGES);
				}
				$limit = ($page == 0 || PAGES == 0) ? 'LIMIT 100' : "LIMIT ".(($page-1)*PAGES).",".PAGES;
				if($this->sets['mod_sort'])
				{
					$sort_s = $sort_params[$_SESSION['sort']]['name']." ".$sort_params[$_SESSION['sort']]['sort'];
				}
				else
				{
					$sort_s = 'sort ASC, id ASC';
				}
				$prds = $this->addin_products( $sort_s, $limit);
				$prd_list = '';
				if (count($prds) > 0)
				{
					if($this->sets['mod_filter_top']) $prd_list .= $this->filter_top($_GET['id']);
					if($this->sets['mod_sort'])
					{
						$prd_list .= '<div class="sort_panel">Сортировать по: ';
						for($i=0; $i<count($sort_params);$i++)
						{
							if($i==$_SESSION['sort']) $prd_list .=  '<b>';
							$prd_list .= '<a href="'.SITE_URL.'ishop/sort/'.$i.'">'.$sort_params[$i]['descr'].'</a>';
							if($i==$_SESSION['sort']) $prd_list .=  '</b>';
							if($i!=count($sort_params)-1) $prd_list .=  ', ';
						}
						$prd_list .= '</div>';
					}
					if(PAGES_TOP_ENABLED)
					{
						$prd_list .= get_pages(
							array (
								'class' => 'prd_pages_top',
								'count_pages' => $c_pages,
								'curr_page'=> $page,
								'link' => 'ishop/additions/'
							)
						);
					}
					$prd_list .= $this->getProductListing( 'prdlistnew' , $prds);
//					if($this->sets['mod_prd_vs']) $prd_list .= $this->view('ishop/sravnenie');
					if(PAGES_BOTTOM_ENABLED)
					{
						$prd_list .= get_pages(
							array (
								'class' => 'prd_pages_bottom',
								'count_pages' => $c_pages,
								'curr_page'=> $page,
								'link' => 'ishop/additions/'
							)
						);
					}
				}
				$content = array (
				'html' =>$prd_list,
				'meta_title' => 'Новинки каталога | Чудо-клумба',
				'meta_keys' =>  'Новинки',
				'meta_desc' => 'Новинки нашего каталога',
				'path' => 'Новинки каталога');
				break;
				
			}
			default:
			{
				if(!empty($_GET['type'])) {
                    error_log("Call 404 from ishop2.php: 1709");
				    $this->_404();
                }
				define('CAT_SIZE',4);
				$row_size = '1';
				$col_size = '4';
				$catalog = '';
				$nav_ar = explode('_', $_GET['id']);
				$_GET['id'] = (!empty($nav_ar['0'])) ? $nav_ar['0'] : '0';
				$cat_inf = $this->cat($_GET['id']);
				$cat_text=$cat_inf['text'];
				if (User::gI()->user_role>0) $cat_text.="<br><a href=\"\" onclick=\"window.open('/wq121/include.php?place=ishop#update_cat_html({$cat_inf['id']})'); return false;\">Редактировать категорию в СУС</a>";
				$cats_dir = $this->cats($_GET['id']);
				$this->update_all();
				if(!empty($cat_inf['vlink']) && $this->sets['cpucat']==1 && $cat_inf['vlink'] != substr($_SERVER['REQUEST_URI'], 1) && !isset($_GET['page'])){
                    error_log("Fatal in iShop2.php:1719");
				    header("HTTP/1.1 301 Moved Permanently");
					$this->redirect($cat_inf['vlink']);
					exit();
				}
				if(($cat_inf === false ) || in_array($_GET['id'], $this->disabled))	{
                    error_log("Call 404 from ishop2.php: 1730");
					$catalog .= $this->_404();
				}
				if($this->sets['mod_search']) $catalog .= $this->view('ishop/adv_search');
				if (count($cats_dir) > 0)
					$catalog .= $this->view('ishop/cat_list_new', array('cats'=>$cats_dir, 'type' => 'ishop'));
				$page = (isset($nav_ar['1'])) ? $nav_ar['1'] : 1;
				if (isset($_GET['page']) && $this->sets['cpucat']==1) $page=$_GET['page'];
				$sort_params = array(
					'0' => array('name' => 'title', 'descr' => 'названию', 'sort'=> 'asc'),
					'1' => array('name' => 'tsena', 'descr' => 'цене', 'sort'=> 'asc'),
					'2' => array('name' => 'views', 'descr' => 'популярности', 'sort'=> 'desc')
				);
				if(!isset($_SESSION['sort']) || !isset($sort_params[$_SESSION['sort']])){
					$_SESSION['sort'] = 0;
				}
				$c_pages = 0;
				if(PAGES != 0){
					$prd_count = $this->getPrdCountInCat($_GET['id']);
					$c_pages = ceil($prd_count/PAGES);
				}
				$limit = ($page == 0 || PAGES == 0) ? '' : "LIMIT ".(($page-1)*PAGES).",".PAGES;
				if($this->sets['mod_sort']){
					$sort_s = $sort_params[$_SESSION['sort']]['name']." ".$sort_params[$_SESSION['sort']]['sort'];
				}else{
					$sort_s = 'sort ASC, id ASC';
				}
				$prds = $this->products($_GET['id'], $sort_s, $limit);
				$prd_list = '';
				if (count($prds) > 0){
					if($this->sets['mod_filter_top']) $prd_list .= $this->filter_top($_GET['id']);
					if($this->sets['mod_sort'])
					{
						$prd_list .= '<div class="sort_panel">Сортировать по: ';
						for($i=0; $i<count($sort_params);$i++)
						{
							if($i==$_SESSION['sort']) $prd_list .=  '<b>';
							$prd_list .= '<a href="'.SITE_URL.'ishop/sort/'.$i.'">'.$sort_params[$i]['descr'].'</a>';
							if($i==$_SESSION['sort']) $prd_list .=  '</b>';
							if($i!=count($sort_params)-1) $prd_list .=  ', ';
						}
						$prd_list .= '</div>';
					}
					$lnk='ishop/'.$_GET['id'].'_';
					if (!empty($cat_inf['vlink']) && $this->sets['cpucat']==1) $lnk=$cat_inf['vlink'].'/p';
					if(PAGES_TOP_ENABLED){
						$pp=str_replace('_1"','"',get_pages(array ('class' => 'prd_pages_top','count_pages' => $c_pages,'curr_page'=> $page,'link' => $lnk)));
						$pp=str_replace('/p1"','"',$pp);
						$prd_list .= $pp;
					}
					if (class_exists('Filter'))	Filter::gI()->catid=$_GET['id'];
					$prd_list .= $this->getProductListing((($this->sets['mod_prd_dsc']) ? 'prdlistnew' : $this->prd_list_style), $prds);
					if(PAGES_BOTTOM_ENABLED){
						$pp=str_replace('_1"','"',get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $c_pages,'curr_page'=> $page,'link' => $lnk)));
						$pp=str_replace('/p1"','"',$pp);
						$prd_list .= $pp;
					}
				} else {
					if(count($cats_dir) == 0) $prd_list='<div style="padding:20px 0">В данной группе товаров нет. Перейдите в другую группу, или воспользуйтесь поиском.</div>';
				}
				if(count($cat_inf) > 0){
					if ($this->sets['cpucat']==1) $path = $this->get_catnm($_GET['id'],1,$_GET['id']); else  $path = $this->get_path($_GET['id']);
					$ga='<script type="text/javascript">dataLayer = [{\'pageCategory\': \'CategoryPage\', \'ProductCategory\': \''.$this->top_cat.'\',\'ProductCategory_1\': \''.$cat_inf["title"].'\'}];</script>';
					if(empty($nav_ar['1']))	{
						$txt = ($this->sets['mod_text_cat']==1) ? ((!empty($cat_text) ? $cat_text : '').$catalog.$prd_list) :	($catalog.$prd_list.(!empty($cat_text) ? $cat_text : ''));
					}else{
						$txt = $catalog.$prd_list;
					}
					$content = array (
						'html' => $txt.$ga,
						'meta_title' => (!empty($cat_inf['metatitle']) ? $cat_inf['metatitle'] : ''),
						'meta_keys' => (!empty($cat_inf['metakeys']) ? $cat_inf['metakeys'] : ''),
						'meta_desc' => (!empty($cat_inf['metadesc']) ? $cat_inf['metadesc'] : ''),
						'path' => $path);
				}
			}
		}//				 end case
		return $content;
	}	// END FUNCTION Get()

}
$ishop = new Ishop($db, $sets, $ebox);
if ($_GET['module'] == 'ishop') 
{
	$content = $ishop->get();
}
