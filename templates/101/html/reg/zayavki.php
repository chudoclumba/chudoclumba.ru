<?if($_GET['type'] != 'arh_zayavki'){?>
<p>��������� ������:</p>
<?} else {?>
<p>��� ������:</p>
<?}?>
<table class="col w100 zayavki_tbl">
 <tr>
  <td>����</td>
  <td>��������</td>
  <td>������</td>
  <td>�������</td>
 </tr>
<?foreach($zayavki as $id=>$val){?> 
 <tr>
  <td><?=date('d.m.Y H:i:s', $val['date'])?></td>
  <td>������ �<?=$val['id']?></td>
  <td><?=$val['status']?></td>
  <td style="width:110px;"><a href="user/zayavki/<?=$val['id']?>">���������</a>&nbsp;&nbsp;&nbsp;<a href="user/download/<?=$val['id']?>">�������</a></td>
 </tr>
<?}?> 
</table>
<?if($_GET['type'] != 'arh_zayavki'){?>
<div style="padding:7px 0px;"><a href="user/arh_zayavki">����������� ���</a></div>
<?} else {?>
<div style="padding:7px 0px;"><a href="user/zayavki">�����</a></div>
<?}?>
<div style="padding:10px 0px;">
<h2>������ �� ��������� �����</h2>
<form method="post" action="">
<table class="col">
 <tr>
  <td class="pdtt">��������</td>
 </tr>
 <tr>
  <td><input name="g1" class="fbinp" value="<?=(!empty($inf['zakazchik']) ? htmlspecialchars($inf['zakazchik'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>" /></td>
 </tr>
 <tr>
  <td class="pdtt">���������� ����</td>
 </tr>
 <tr>
  <td><input name="g2" class="fbinp" value="<?=(!empty($inf['contlico']) ? htmlspecialchars($inf['contlico'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>" /></td>
 </tr>
 <tr>
  <td class="pdtt">����������������</td>
 </tr>
 <tr>
  <td><input name="g3" class="fbinp" value="<?=(!empty($inf['grusootpravitel']) ? htmlspecialchars($inf['grusootpravitel'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>" /></td>
 </tr> 
 <tr>
  <td class="pdtt">���� �������� �� �������</td>
 </tr>
 <tr>
  <td><input name="g4" class="fbinp" value="<?=(!empty($inf['data']) ? htmlspecialchars($inf['data'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>" /></td>
 </tr>
 <tr>
  <td class="pdtt">����� ��������</td>
 </tr>
  <tr>
  <td><input name="g5" class="fbinp" value="<?=(!empty($inf['mesto']) ? htmlspecialchars($inf['mesto'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>" /></td>
 </tr>
<tr>
 <td class="zayavki_list">
 <table class="col w100 zayavki_tbl">
  <tr>
   <td>�</td>
   <td>����� ��������</td>
   <td>����� ��������, ���������� ����, �������</td>
   <td>���-�� ������</td>
   <td>����.  �����</td>
  </tr>

<?
$cnt = 0;
if(!empty($inf_list))
{
	$cnt = count($inf_list);
	if($cnt > 0)
	{
		foreach($inf_list as $id=>$val){?>
  <tr>
   <td class="z_num"><?=($id+1)?></td>
   <td><input class="j1" name="h[<?=$id?>][]" type="text" value="<?=$val['town']?>" /></td>
   <td><input class="j1" name="h[<?=$id?>][]" type="text" value="<?=$val['adr']?>" /></td>
   <td><input class="j1" name="h[<?=$id?>][]" type="text" value="<?=$val['kolvo']?>" /></td>
   <td><input class="j1" name="h[<?=$id?>][]" type="text" value="<?=$val['temp']?>" /></td>
  </tr>
		<?

		}
	}
}
?>
<?for($i=$cnt;$i<$cnt+1;$i++){?>  
  <tr>
   <td class="z_num"><?=($i+1)?></td>
   <td><input class="j1" name="h[<?=$i?>][]" type="text" value="" /></td>
   <td><input class="j1" name="h[<?=$i?>][]" type="text" value="" /></td>
   <td><input class="j1" name="h[<?=$i?>][]" type="text" value="" /></td>
   <td><input class="j1" name="h[<?=$i?>][]" type="text" value="" /></td>
  </tr>
<?}?>
 </table>
 <div class="btn_ad_fx"><input type="button" class="fbbs" value="�������� ������" /></div>
 </td>
</tr>
 <tr>
  <td><input class="fbb" name="z_add" type="submit" value="���������" /></td>
 </tr>
</table>
<script>
$('.fbbs').click(function(){
	var num = (parseInt($('.z_num:last').html()));
	$('.zayavki_tbl > tbody > tr:last').after(
	'<tr>' +
   '<td class="z_num">' + (num + 1) + '</td>' +
   '<td><input class="j1" name="h[' + num + '][]" type="text" value="" /></td>' +
   '<td><input class="j1" name="h[' + num + '][]" type="text" value="" /></td>' +
   '<td><input class="j1" name="h[' + num + '][]" type="text" value="" /></td>' +
   '<td><input class="j1" name="h[' + num + '][]" type="text" value="" /></td>' +
  '</tr>');
})
</script>
</form>
</div>