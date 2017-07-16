<div class="adv_search">
<form action="ishop/advsearch" method="post">
<div style="padding: 0px 0px 20px;">
<span style="color: red; font-weight: 600; font-size: 12px; " id="espan"><?=(($info==1) ? 'Выберите хотя бы один параметр!' : '')?></span>
</div>
<table class="col">
<?
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

	$findpan='';
	foreach($sparams as $id=>$val)
	{
		if($val['1'] == 'select')
		{
			$findpan.='<tr><td class="pr10tr">'.$val['0'].'</td><td>';

			$q = "SELECT ".$id." FROM ".TABLE_PRODUCTS." WHERE enabled = 1 GROUP BY ".$id." ORDER BY ".$id."";
			$rows = $this->db->get_rows($q);
			$findpan.='<select name="'.$id.'" style="width:400px;">';
			$findpan.='<option value="0">Любой</option>';
			if(!empty($val['2']))
			{
				foreach($val['2'] as $rid=>$rval)
				{
					$findpan.='<option value="'.$rid.'">'.$rval.'</option>';
				}
			}
			else
			{
				foreach($rows as $rid=>$rval)
				{
					if(!empty($rval[$id]))
					{
						$findpan.='<option value="'.$rval[$id].'">'.$rval[$id].'</option>';
					}
				}
			}
			$findpan.='</select></td></tr>';
		}
		elseif($val['1'] == 'ot_do')
		{
			$findpan.='<tr><td class="pr10tr">'.$val['0'].'</td><td>от <input style="width:50px;" name="'.$id.'[0]" type="text" /> до <input style="width:50px;" name="'.$id.'[1]" type="text" /></td></tr>';
		}
		else
		{
			$findpan.='<tr><td class="pr10tr">'.$val['0'].'</td><td><input name="'.$id.'" type="text" /></td></tr>';
		}

	}

		
		echo $findpan;

?>
</table>
<input type="checkbox" name="f_all" value="f_all" id="f_all"/>Так-же, искать закрытые к продаже.
<button  onClick="change_but()" name="advs_sbm" type="submit" value="Найти" class="albutton alorange" style="padding: 20px 0px 0px 60px;"><span><span><span class="search">Найти</span></span></span></button>
</form>
<script>
var ch=false;
jQuery().ready(function(){
 jQuery("button[name=\'advs_sbm\']").click(function(){
   //         alert(ch);
			return true;
        });
    });
function change_but(){
	$("#espan").html('');
	}
	
$('select[name="param_vid"]').change(function(){
	if (document.getElementById('f_all').checked)  {
		k1=1;
	} else {
		k1=0;
	};
	$.ajax({
		url: '<?=SITE_URL?>ajax?atype=param_vid' + '&title=' + escape($('select[name="param_vid"]').val())+'&sall='+k1,
		cache: false,
		success: function(html){
			$('select[name="param_gruppa"]').html(html);
		}
	});
	$.ajax({
		url: '<?=SITE_URL?>ajax?atype=param_vid1' + '&title=' + escape($('select[name="param_vid"]').val())+'&sall='+k1,
		cache: false,
		success: function(html){
			$('select[name="param_sort"]').html(html);
		}
	});
});

$('select[name="param_gruppa"]').change(function(){
	$.ajax({
		url: '<?=SITE_URL?>ajax?atype=param_gruppa' + '&title=' + escape($(this).val())+ '&vid=' + escape($('select[name="param_vid"]').val())+'&sall='+k1 ,
		cache: false,
		success: function(html){
			$('select[name="param_sort"]').html(html);
		}
	});
});
$('input[name="f_all"]').change(function(){
	if (document.getElementById('f_all').checked)  {
		k1=1;
	} else {
		k1=0;
	};
	$.ajax({
		url: '<?=SITE_URL?>ajax?atype=param_vid' + '&title=' + escape($('select[name="param_vid"]').val())+'&sall='+k1,
		cache: false,
		success: function(html){
			$('select[name="param_gruppa"]').html(html);
		}
	});
	$.ajax({
		url: '<?=SITE_URL?>ajax?atype=param_vid1' + '&title=' + escape($('select[name="param_vid"]').val())+'&sall='+k1,
		cache: false,
		success: function(html){
			$('select[name="param_sort"]').html(html);
		}
	});
});

</script>

</div>