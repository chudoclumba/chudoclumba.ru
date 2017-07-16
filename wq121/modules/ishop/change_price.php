<?php
$cat_c = array();

function get_catp($ids)
{
 global $db;

 $list = implode("','",$ids);

 $nk = array();
 $cats = $db->get_rows("SELECT id FROM ".TABLE_CATEGORIES." WHERE parent_id IN ('".$list."')");
 foreach($cats as $cat)
 {
  $nk[] = $cat['id'];
 }
 return $nk;
}

function get_all_cat_in_cat($cat_id)
{
 global $cat_c;
 $ids = get_catp($cat_id);
 $cat_c = array_merge($ids,$cat_c);
 
 if(count($ids)!=0)
 {
  $ids = get_all_cat_in_cat($ids);
 }
 return $ids;
}

function get_ids_prds($ids)
{
 global $db;
 $list = implode("','",$ids);
 $nk = array();
 $prds = $db->get_rows("SELECT id FROM ".TABLE_PRODUCTS." WHERE cat_id IN ('".$list."')");
 foreach($prds as $prd)
 {
  $nk[] = $prd['id'];
 } 
 return $nk;
}

if(!empty($_POST['f_submit']) && !empty($_POST['p_izm']))
{
 get_all_cat_in_cat(array($_POST['p_cat']));
 $cat_c = array_merge(array($_POST['p_cat']),$cat_c);
 
 $id_prds = get_ids_prds($cat_c);
 $list = implode("','",$id_prds);
 
 
 if($_POST['p_znak'] == 0)
 {
  $form = 'tsena=tsena+';
 }
 else
 {
  $form = 'tsena=tsena-';
 }
 
 if($_POST['p_val'] == 0)
 {
  $form = $form.$_POST['p_izm'];
 }
 else
 {
  $form = ''.$form.'('.$_POST['p_izm'].'/100)*tsena';
 } 
 
 $query = "UPDATE ".TABLE_PRODUCTS." SET ".$form." WHERE id IN ('".$list."')";
 $db->exec($query);
}

$module['html'] .= '
<style>
.f_ch_price {
 text-align:left;
 margin:20px;
}
.t_ch_price td{
 border-collapse:collapse;
 color:#000;
 padding:3px;
 font-size:12px;
}
</style>
<div class="f_ch_price">
<form action="" method="post">
<table class="t_ch_price">
 <tr>
  <td>Категория:</td>
  <td>
  <select name="p_cat">
  <option value="0">все</option>';
	$module['html'] .= getcategoriesoptions(-1,-1,0,0);
$module['html'] .= '</select>
  </td>
 </tr>
 <tr>
  <td>Изменение цены:</td>
  <td>
  <select name="p_znak">
   <option value="0">увеличить на</option>
   <option value="1">уменьшить на</option>
  </select> 
  <input name="p_izm" type="text" value="0" />  
  <select name="p_val">
   <option value="0">руб.</option>
   <option value="1">%</option>
  </select> 
  </td>
 </tr>
 <tr>
  <td>&nbsp</td>
  <td><input class="button2" type="submit" name="f_submit" value="Изменить" /></td>
 </tr>
</table>
</form>
</div>';
