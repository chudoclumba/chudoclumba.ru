<?			
ob_start();

$art = $cat[$row['param_descr']];

//print_r($art);

$artics = get_elements($art,'articul');
$a = 0;


foreach($artics as $artic)
{
	$valuesu = count(get_elements($artic,'param'));
	if($valuesu > $a)
	{
		 $a = $valuesu;
	}
}

$arts =  '<table id="tbl_arts" class="tbl_arts">';
$arts .= '<tr>
	<td>Удалить</td>
	<td align="center">Размер</td>
	<td align="center">Цена</td>
</tr>';


$g = 0;
$k = 0;

foreach($artics as $artic)
{
	$values = get_elements($artic,'param');
		$arts .= '
	<tr>
	 <td align="center"><input name="del_art['.$g.']" value="1" type="checkbox" /></td>
	 <td><input class="ar_p" type="text" name="value['.$g.'][0]" value="'.$values['0'].'" /></td>
	 <td><input class="ar_p" type="text" name="value['.$g.'][1]" value="'.$values['1'].'" /></td>
	</tr>';	
	$g++;
}
$arts .= '</table>';

?>
<div class="arts_d"><?=$arts?></div>
 <div>
  <input onclick="addRow()" style="text-align:center;" type="button" value="добавить значение" class="button2" />
 </div>
<?
			$dd_ff = ob_get_contents();
			ob_end_clean();
			
			
			$module['html'] .= 'Типы товаров:<br>
'.$dd_ff.'
			<br><br>
			'; 
			