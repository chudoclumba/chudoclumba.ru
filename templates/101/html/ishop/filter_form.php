<?
	$cb = (!empty($_POST['param_tsvet'])) ? $_POST['param_tsvet'] : '';
	$ccb = (!empty($_POST['param_razmer'])) ? $_POST['param_razmer'] : '';
	
	$slcr = array(
		'Размер (м)' => array('razmer', array(array('любой','1'),array('любой','2'))),
		'Цвет' => array('cvet', array('любого цвета')),
		'Узор' => array('uzor', array('без разницы')),
		'Материал' => array('material', array('любой')),
		'Страна' => array('strans', array('любая')),
		'Производство' => array('przv', array('без разницы')),
		'Форма' => array('form', array('любая форма')),
		'Особенность' => array('osb', array('нет')),
		'Цена (руб)' => array('prs', array('без разницы'))
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
  </select> х <select name="param_tsvet" onchange="forma_filter.submit()" style="width:80px;">
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