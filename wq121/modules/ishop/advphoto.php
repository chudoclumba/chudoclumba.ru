<?php
$module['html'] .= '
<style>

.new_foto_d {
 margin:20px 3px;
 text-align:left;
}
.nf_tb td {
 padding:3px;
 font-size:12px;
 font-family:Tahoma;
}

.prd_fts {
 border-collapse:collapse;
}

.prd_fts td { 
 padding:3px;
 border:1px solid #ccc;
 font-size:12px;
 font-family:Tahoma;
}

.prd_fts_add {
 text-align:left;
 padding-left:7px;
}

</style>';

ob_start();
?><!---->
<div id="second">
<div id="p_add_f" class="new_foto_d">
 <p class="inf_title">Дополнительные фото:</p>
 <div style="padding-left:2px;">
<table>
<tr>
 <td><img id="adv_foto_main" alt="" src="" onclick="return hs.expand(this, { src: '<?=SITE_URL?>'+$('#url_abs_nohost_adv').val() } )"/></td>
 <td><input type="text" class="ti_inside" style="width:400px" onchange="$('#adv_foto_main').attr('src','<?php echo SITE_URL?>thumb.php?id=' + $('#url_abs_nohost_adv').val() + '&x=100&y=100');" name="adv_fotos" value="" id="url_abs_nohost_adv" /><a style="margin:0px 10px" href="javascript:;" onclick="mcImageManager.browse({fields : 'url_abs_nohost_adv', relative_urls : true, document_base_url : '<?php echo SITE_URL?>',use_url_path : true,url:''+ $('#url_abs_nohost_adv').val()});">Выбрать файл</a><a style="margin:0px 10px" href="javascript:;" onclick="load_file(<?php echo $prd_id?>)">Добавить в список</a></td>
</tr>
</table> 
 </div>
</div>
<div id="adv_photosc" class="prd_fts_add"><?php echo get_adv_photo($prd_id);?></div>
</div>
<?php

$module['html'] .= ob_get_contents();
ob_end_clean();
