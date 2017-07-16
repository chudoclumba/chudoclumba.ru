<style>
.ishop_cont, .slrs_add {
 margin:20px;
 text-align:left;
}

.prd_slrs {
 border:1px solid black;
 border-collapse:collapse;
}

.prd_slrs td {
 font-size:12px;
 font-family:Tahoma;
 border:1px solid black;
 padding:2px;
}

.slrs_add td {
 font-size:12px;
 font-family:Tahoma;
 padding:4px;
}

.prd_slr_header {
 background:#eee;
}
</style>

<?


echo tinymce(); 

define('SLR_FOLDER', ROOT_DIR.'data/ishop_sellers/');
define('SLR_URL', $url.'data/ishop_sellers/');

include INC_DIR.'sellers.inc.php';

function empt_text($text)
{
 return empty($text) ? '&nbsp;' : htmlspecialchars($text,ENT_COMPAT | ENT_XHTML,'cp1251');
}

function slrs_cont()
{
 $slrs = get_sellers();
 $slr_c = '
 <div class="ishop_cont">
 <table class="prd_slrs">
 ';
 
  $slr_c .= '
   <tr class="prd_slr_header">
    <td>#</td>
    <td>Название</td>
    <td>Логотип</td>
    <td>Функции</td>
   </tr>';
 
 $s_link = 'include.php?place='.$_GET['place'].'&action='.$_GET['action'].'';
 
 foreach($slrs as $slr)
 {
  $slr_c .= '
   <tr>
    <td>'.empt_text($slr['id']).'</td>
    <td>'.empt_text($slr['name']).'</td>
    <td><img alt="" src="'.SLR_URL.empt_text($slr['logo']).'" /></td>
    <td>
     <a href="'.$s_link.'&eID='.$slr['id'].'">Редактировать <img class="img_sa_btn" height="16" border="0" width="16" alt="редактировать" src="images/icons/b_edit.gif"/></a>
     <a href="'.$s_link.'&dID='.$slr['id'].'">Удалить <img class="img_sa_btn" height="16" border="0" width="16" alt="удаление" src="images/icons/b_drop.png"/></a>
    </td> 
   </tr>';
 }
 $slr_c .= '</table></div>';
 
 
 return $slr_c;
}

function slrs_add()
{
 $slrs_add = '
 <div class="slrs_add">
  <form action="" method="post" enctype="multipart/form-data">
   <table>
    <tr>
     <td>Название:</td>
     <td><input name="slr_name" type="text" /></td>
    </tr>
    <tr>
     <td>Описание:</td>
     <td><textarea style="width:80%; height:300px;" name="slr_descr"></textarea></td>
    </tr>
    <tr>
     <td>Логотип:</td>
     <td><input name="slr_logo" type="file" /></td>
    </tr>
    <tr>
     <td>&nbsp;</td>
     <td><input value="Добавить" name="slr_submmit" type="submit" /></td>
    </tr>
   </table>
  </form>
 </div>
 ';
 return $slrs_add;
}

function slrs_edit($id)
{
 global $db;
 $slr = $db->get(TABLE_SELLERS,$id);
 $slrs_add = '
 <div class="slrs_add">
  <form action="" method="post" enctype="multipart/form-data">
   <table>
    <tr>
     <td>Название:</td>
     <td><input name="slr_name" value="'.$slr['name'].'" type="text" /></td>
    </tr>
    <tr>
     <td>Описание:</td>
     <td><textarea style="width:80%; height:300px;" name="slr_descr">'.$slr['descr'].'</textarea></td>
    </tr>  
    <tr>
     <td>Логотип:</td>
     <td><input name="slr_logo" type="file" /><input id="del_l" type="checkbox" name="del_logo" /><label for="del_l" >удалить</label></td>
    </tr>
    <tr>
     <td>&nbsp;</td>
     <td><input value="Сохранить" name="slr_save" type="submit" /></td>
    </tr>
   </table>
  </form>
 </div>
 ';
 return $slrs_add;
}

function del_file($file)
{
 if(!empty($file))
 {
  if(file_exists(SLR_FOLDER.$file))
  {
   unlink(SLR_FOLDER.$file);
  }
 }
}

if(!empty($_GET['eID']))
{
 if(!empty($_POST['slr_save']))
 {
  if(!empty($_POST['slr_name']))
  {
   $slr = $db->get(TABLE_SELLERS,$_GET['eID']);
   $new_slr['name'] = $_POST['slr_name'];
   $new_slr['descr'] = $_POST['slr_descr'];
   
   
   if(!empty($_POST['del_logo']))
   {
    del_file($slr['logo']);
    $new_slr['logo'] = '';
   }
   
   if(!empty($_FILES['slr_logo']['tmp_name']))
   {
    $new_file = SLR_FOLDER.$_FILES['slr_logo']['name'];
    
    del_file($slr['logo']);
    
    if(copy($_FILES['slr_logo']['tmp_name'],$new_file))
    {
     $new_slr['logo'] = $_FILES['slr_logo']['name'];
    }
   }
   
   
   $db->update(TABLE_SELLERS,$_GET['eID'],$new_slr);
  }
  redir_a("Сохранение...",'include.php?place='.$_GET['place'].'&action='.$_GET['action'].'');
 }
 echo slrs_edit($_GET['eID']);
}
else
{
 if(!empty($_GET['dID']))
 {
  $slr = $db->get(TABLE_SELLERS,$_GET['dID']);
  $db->delete(TABLE_SELLERS,array('id'=>$_GET['dID']));
  if(!empty($slr['logo']))
  {
   if(file_exists(SLR_FOLDER.$slr['logo']))
   {
    unlink(SLR_FOLDER.$slr['logo']);
   }
  }
 }

 if(!empty($_POST['slr_submmit']))
 {
  if(!empty($_POST['slr_name']))
  {
   $new_slr['name'] = $_POST['slr_name'];
   $new_slr['descr'] = $_POST['slr_descr'];
   
   if(!empty($_FILES['slr_logo']))
   {
    $new_file = SLR_FOLDER.$_FILES['slr_logo']['name'];
    if(copy($_FILES['slr_logo']['tmp_name'],$new_file))
    {
     $new_slr['logo'] = $_FILES['slr_logo']['name'];
    }
   }
   $db->insert(TABLE_SELLERS,$new_slr);
  }
 }

 echo slrs_add();
 echo slrs_cont();
}

?>