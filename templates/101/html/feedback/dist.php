<table>
 <tr>
  <td>Откуда:</td>
  <td><select name="town1">
<?
$all_towns = array_unique(array_merge($towns['first'], $towns['second']));
sort($all_towns);
foreach($all_towns as $id=>$val){?>
   <option value="<?=$val?>"><?=$val?></option>
<?}?>
  </select></td>
 </tr>
  <tr>
  <td>Куда:</td>
  <td><select name="town2">
<?foreach($all_towns as $id=>$val){?>
   <option value="<?=$val?>"><?=$val?></option>
<?}?>
  </select></td>
 </tr>
  <tr>
  <td>Расстояние:</td>
  <td class="dist"></td>
 </tr>
</table>
<script>
$("select[name='town1'], select[name='town2']").change(function() {update()});

function update()
{
	var val = '0';
<?foreach($towns['first'] as $id=>$val){?>
	if($("select[name='town1']").val() == '<?=$towns['first'][$id]?>' && $("select[name='town2']").val() == '<?=$towns['second'][$id]?>')
	{
		val = '<?=$towns['dist'][$id]?>';
	}
<?}?>
<?foreach($towns['first'] as $id=>$val){?>
	if($("select[name='town1']").val() == '<?=$towns['second'][$id]?>' && $("select[name='town2']").val() == '<?=$towns['first'][$id]?>')
	{
		val = '<?=$towns['dist'][$id]?>';
	}
<?}?>
	$(".dist").html(val);
}
update();
</script>