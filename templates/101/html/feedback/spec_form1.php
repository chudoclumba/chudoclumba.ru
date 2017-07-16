<form method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">ФИО</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p1" value="<?=(!empty($data['p1'])  ? htmlspecialchars($data['p1'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Контактный телефон</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p2" value="<?=(!empty($data['p2'])  ? htmlspecialchars($data['p2'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Cтанция метро</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p3" value="<?=(!empty($data['p3'])  ? htmlspecialchars($data['p3'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Количество комнат</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p4" value="<?=(!empty($data['p4'])  ? htmlspecialchars($data['p4'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Наличие балкона или лоджии(Застекленна/Не застекленна)</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p5" value="<?=(!empty($data['p5'])  ? htmlspecialchars($data['p5'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Входная дверь Металическая или деревянная</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p6" value="<?=(!empty($data['p6'])  ? htmlspecialchars($data['p6'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Общая площадь</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p7" value="<?=(!empty($data['p7'])  ? htmlspecialchars($data['p7'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Кухня</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p8" value="<?=(!empty($data['p8'])  ? htmlspecialchars($data['p8'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Площадь комнат</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p9" value="<?=(!empty($data['p9'])  ? htmlspecialchars($data['p9'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Санузел совмещенный/раздельный</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p10" value="<?=(!empty($data['p10'])  ? htmlspecialchars($data['p10'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Этаж/Этажность дома</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p11" value="<?=(!empty($data['p11'])  ? htmlspecialchars($data['p11'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>
				
<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Описание Мебели Присутствующей в Квартире</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p12" value="'.(!empty($data['p12'])  ? htmlspecialchars($data['p12'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>
				
<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Описание бытовой техники в квартире</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p13" value="<?=(!empty($data['p13'])  ? htmlspecialchars($data['p13'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>
				
<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Наличие телефона</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p14" value="<?=(!empty($data['p14'])  ? htmlspecialchars($data['p14'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>
				
<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Наличие Интернета</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p15" value="<?=(!empty($data['p15'])  ? htmlspecialchars($data['p15'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>
				
<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px;">Стоимость квартиры</td>
 <td class="fb_input"><input class="fbinp" type="text" name="p16" value="<?=(!empty($data['p16'])  ? htmlspecialchars($data['p16'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td>
</tr>

<tr>
 <td class="fb_id" style="text-align:right; padding-right:10px; vertical-align:top;">Дополнительная информация</td>
 <td class="fb_input"><textarea class="fxtxt" name="p17"><?=(!empty($data['p17'])  ? htmlspecialchars($data['p17'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?></textarea></td>
</tr>

<tr><td>&nbsp;</td><td class="fb_input" align="left"><input class="fbb" type="submit" value="Отправить"></td></tr>
</table>
<input type="hidden" name="posted" value="1">
</form>