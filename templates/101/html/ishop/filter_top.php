<div class="filter_wnd_t">
 <div class="fl_wnd_t">Выбор товаров по характеристикам</div>
 <div class="fl_wnd_ex"><a href="ishop/clear_filter/<?=$_GET['id']?>">Отмнить все фильтры</a></div>
 <table class="fl_ex_tt col w100">
<?foreach($chars as $id=>$value) {?>
  <tr>
   <td class="p0 fl_w_1"><?=$value['name']?></td>
   <td class="p0 fl_w_2"><?
	$g = 0;
	foreach($chars_v[$value['char_id']] as $c_id=>$c_val)
	{
		if(!isset($_SESSION['filter'][$value['char_id']]) || $_SESSION['filter'][$value['char_id']] == $c_id)
		{
			$st = ($curr_c == $value['char_id'] && $curr_v == $c_id) ? ' style="font-weight:bold;"' : '';
			$f = (count($chars_v[$value['char_id']])-1 == $g) ? '' : ' · ';
			
			if(isset($_SESSION['filter'][$value['char_id']]) && $_SESSION['filter'][$value['char_id']] == $c_id)
			{
				echo '<b>'.$c_val.'</b>';
			}
			else
			{
				echo '<a'.$st.' href="ishop/add_filter/'.$_GET['id'].'_'.$value['char_id'].'_'.$c_id.'">'.$c_val.'</a>'.$f;
			}
		}
		$g++;
	}
	
	if(isset($_SESSION['filter'][$value['char_id']]))
	{
		echo ' · <a href="ishop/del_filter/'.$_GET['id'].'_'.$value['char_id'].'">Показать все</a>';
	}
   ?></td>
  </tr>
<?}?>  
 </table>
</div>