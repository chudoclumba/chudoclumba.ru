<?
	$cb = (!empty($_POST['param_tsvet'])) ? $_POST['param_tsvet'] : '';
	$ccb = (!empty($_POST['param_razmer'])) ? $_POST['param_razmer'] : '';
	
	$slcr = array(
		'������ (�)' => array('razmer', array(array('�����','1'),array('�����','2'))),
		'����' => array('cvet', array('������ �����')),
		'����' => array('uzor', array('��� �������')),
		'��������' => array('material', array('�����')),
		'������' => array('strans', array('�����')),
		'������������' => array('przv', array('��� �������')),
		'�����' => array('form', array('����� �����')),
		'�����������' => array('osb', array('���')),
		'���� (���)' => array('prs', array('��� �������'))
	);
	
?>
<div class="ishop_select">
 <form name="forma_filter" action="ishop/filter" method="post">
<?foreach($slcr as $id=>$val) {?> 
<?if($val['0']=='razmer') {?>
  <div class="fltr_ttl"><?=$id?>:</div>
  <select name="param_tsvet" onchange="forma_filter.submit()" style="width:80px;">
   <?foreach($val['1']['0'] as $v_id=>$v_val) {?>
   <option value="<?=$v_id?>"><?=$v_val?></option>
   <?}?>
  </select> � <select name="param_tsvet" onchange="forma_filter.submit()" style="width:80px;">
   <?foreach($val['1']['1'] as $v_id=>$v_val) {?>
   <option value="<?=$v_id?>"><?=$v_val?></option>
   <?}?>
  </select>
<?} else {?>
  <div class="fltr_ttl"><?=$id?>:</div>
  <select name="param_razmer" onchange="forma_filter.submit()" style="width:197px;">
   <?foreach($val['1'] as $v_id=>$v_val) {?>
   <option value="<?=$v_id?>"><?=$v_val?></option>
   <?}?>
  </select>
<?}?>
<?}?>
 </form>
</div>