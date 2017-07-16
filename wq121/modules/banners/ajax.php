<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

include_once "../../../includes/kanfih.php";
include_once "../../vars.php";
include_once SA_DIR."inc/susfunction.php";
include_once INC_DIR."dbconnect.php";
include_once INC_DIR."functions.php";
include_once INC_DIR."site.class.php";

$site = Site::gI();
$sets = $site->GetSettings();
$ebox = $site->GetEditBoxes();
if (function_exists($_GET['act'])) {
	echo $_GET['act']();
	die();
}

function test(){
	die( 'test');
}
function savenewslbanner(){
	global $db;
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
		$ret=$db->insert(TABLE_SLBANNERS,$_POST);
	}
	if ($ret>0) echo '1'; else echo '0';
}
function savenew(){
	global $db;
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
		$ret=$db->insert(TABLE_BANNERS,$_POST);
	}
	if ($ret>0) echo '1'; else echo '0';
}
function savenewsl(){
	global $db;
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
		$ret=$db->insert(TABLE_SLIDERS,$_POST);
	}
	if ($ret>0) echo '1'; else echo '0';
}
function saveslbanner(){
	global $db;
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
        if (!empty($_POST['id']))
        {	$id=$_POST['id'];
        	unset($_POST['id']);
			$ret=$db->update(TABLE_SLBANNERS, array('id' => $id), $_POST);
		}
	}
	if (!($ret===false)) echo '1'; else echo '0';
	exit;
	
}

function savebanner(){
	global $db;
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
        if (!empty($_POST['id']))
        {	$id=$_POST['id'];
        	unset($_POST['id']);
			$ret=$db->update(TABLE_BANNERS, array('id' => $id), $_POST);
		}
	}
	if (!($ret===false)) echo '1'; else echo '0';
	exit;
	
}
function saveslider(){
	global $db;
	$ret=FALSE;
	if(count($_POST)>0)  // Обработка кнопки сохранить.
	{
        if (!empty($_POST['id']))
        {	$id=$_POST['id'];
        	unset($_POST['id']);
			$ret=$db->update(TABLE_SLIDERS, array('id' => $id), $_POST);
		}
	}
	if (!($ret===false)) echo '1'; else echo '0';
	exit;
	
}
function EditSlBanner(){
	global $db;
	$res = $db->get(TABLE_SLBANNERS, $_GET['id']);
?>
<div align="left"><form id="fsban" action="" enctype="multipart/form-data">
<textarea style="display:none" class="mceEditor" style="width: 70%" id="editor1"></textarea>
<input name="id" type="hidden" value="<?=$res['id']?>"/>
<div class="block"><label>Страница:</label><input readonly="readonly" id="fum" class="finput" style="width:450px" type="text" name="page" value="<?=$res['page']?>"/></div>
<div class="block"><label>Позиция:</label><input readonly="readonly" class="finput" style="width:50px" type="text" name="place" value="<?=$res['place']?>"/></div>
<div class="block"><label>Ссылка:</label><input readonly="readonly" class="finput" style="width:450px" type="text" name="vlink" value="<?=$res['vlink']?>"/></div>
<div class="block">
<label>Файл картинки:</label>
<div style="display: inline-block;float:left "><input readonly="readonly" name="foto" class="finput" value="<?=$res['foto']?>" id="url_abs_nohost" onchange="document.getElementById('foto_main').src = '<?=SITE_URL?>thumb.php?id=' + document.getElementById('url_abs_nohost').value + '&x=409&y=176&crop'"/></div>
<div class="btn btn1" onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<?=SITE_URL?>',use_url_path : true,url:$('#url_abs_nohost').val()});">Обзор...</div>
</div>
<div class="block"><label>Ширина:</label><input  readonly="readonly" style="width:50px" type="text" name="width" value="<?=$res['width']?>" class="finput" onchange="nwhch(this);"/></div>
<div class="block"><label>Высота:</label><input  readonly="readonly" style="width:50px" type="text" name="height" value="<?=$res['height']?>" class="finput" onchange="nwhch(this);"/></div>
<div class="block">
<p>Рекомендованный размер картинки 369х207 пикселей.</p>
<img style="margin:0px 20px 5px 5px;width: <?=$res['width']?>px;height: <?=$res['height']?>px" id="foto_main" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$res['foto']?>&amp;x=<?=$res['width']?>&amp;y=<?=$res['height']?>&amp;crop" onclick="return hs.expand(this, { src: '<?=SITE_URL?>'+$('#url_abs_nohost').val() } )"/></div>
	 
	<?=button1('Редактировать',"SaveSlBanner(this);",'','edit')?>
	<br/>
	</form>
	</div>		
<?	
}
function NewSlBanner(){?>
<div align="left"><form id="fsban" action="" enctype="multipart/form-data">
<div class="block"><label>Страница:</label><input  style="width:450px" type="text" name="page" value="" class="finput"/></div>
<textarea style="display:none" class="mceEditor" style="width: 70%" id="editor1"></textarea>
<div class="block"><label>Позиция:</label><input class="finput" style="width:50px" type="text" name="place" value=""/></div>
<div class="block"><label>Ссылка:</label><input class="finput" style="width:450px" type="text" name="vlink" value=""/></div>
<div class="block">
<label>Файл картинки:</label>
<div style="display: inline-block;float:left "><input  name="foto" class="finput" value="" id="url_abs_nohost" onchange="nwhch(this);"/></div>
<div class="btn btn1" onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<?=SITE_URL?>',use_url_path : true,url:$('#url_abs_nohost').val()});">Обзор...</div>
</div>
<div class="block"><label>Ширина:</label><input  style="width:50px" type="text" name="width" value="369" class="finput" onchange="nwhch(this);"/></div>
<div class="block"><label>Высота:</label><input  style="width:50px" type="text" name="height" value="207" class="finput" onchange="nwhch(this);"/></div>
<div class="block">
<p>Рекомендованный размер картинки 369х207 пикселей.</p>
<img style="margin:0px 20px 5px 5px; border: 1px solid black;width: 369px;height: 207px" id="foto_main" alt="" src="s.gif" onclick="return hs.expand(this, { src: '<?=SITE_URL?>'+$('#url_abs_nohost').val() } )"/>
</div>	 
	<?=button1('Cохранить',"SaveNewSlBanner(this);",'','save')?>
	<br/>
	</form>
	</div>	<?
}
function del_ban(){
	$res=0;
	global $db;
	$tb='';
	if ($_GET['type']=='ban') {
		$tb=''.TABLE_BANNERS;
	}
	elseif ($_GET['type']=='slb') {
		$tb=''.TABLE_SLBANNERS;
	}
	elseif ($_GET['type']=='sld') {
		$tb=''.TABLE_SLIDERS;
	} 
	if (!empty($tb) && $_GET['id']>0){
		$res=$db->delete($tb,array('id'=>$_GET['id']));
	}
	return $res;
}

function EditBanner(){
	global $db;
	$res = $db->get(TABLE_BANNERS, $_GET['id']);
?>
<div align="left"><form id="fban" action="" enctype="multipart/form-data">
<textarea style="display:none" class="mceEditor" style="width: 70%" id="editor1"></textarea>
<input name="id" type="hidden" value="<?=$res['id']?>"/>
<div class="block"><label>Страница:</label><input readonly="readonly" id="fum" class="finput" style="width:450px" type="text" name="page" value="<?=$res['page']?>"/></div>
<div class="block"><label>Позиция:</label><input readonly="readonly" class="finput" style="width:50px" type="text" name="place" value="<?=$res['place']?>"/></div>
<div class="block"><label>Ссылка:</label><input readonly="readonly" class="finput" style="width:450px" type="text" name="vlink" value="<?=$res['vlink']?>"/></div>
<div class="block">
<label>Файл картинки:</label>
<div style="display: inline-block;float:left "><input readonly="readonly" name="foto" class="finput" value="<?=$res['foto']?>" id="url_abs_nohost" onchange="document.getElementById('foto_main').src = '<?=SITE_URL?>thumb.php?id=' + document.getElementById('url_abs_nohost').value + '&x=409&y=176&crop'"/></div>
<div class="btn btn1" onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<?=SITE_URL?>',use_url_path : true,url:$('#url_abs_nohost').val()});">Обзор...</div>
</div>
<div class="block"><label>Ширина:</label><input  readonly="readonly" style="width:50px" type="text" name="width" value="<?=$res['width']?>" class="finput" onchange="nwhch(this);"/></div>
<div class="block"><label>Высота:</label><input  readonly="readonly" style="width:50px" type="text" name="height" value="<?=$res['height']?>" class="finput" onchange="nwhch(this);"/></div>
<div class="block">
<p>Рекомендованный размер картинки 412х200 пикселей для страниц с левым меню и 555х200 пикселей для страниц без левого меню.</p>
<img style="margin:0px 20px 5px 5px;width: <?=$res['width']?>px;height: <?=$res['height']?>px" id="foto_main" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$res['foto']?>&amp;x=<?=$res['width']?>&amp;y=<?=$res['height']?>&amp;crop" onclick="return hs.expand(this, { src: '<?=SITE_URL?>'+$('#url_abs_nohost').val() } )"/></div>
	 
	<?=button1('Редактировать',"SaveBanner(this);",'','edit')?>
	<br/>
	</form>
	</div>		
<?	
}
function NewBanner(){?>
<div align="left"><form id="fban" action="" enctype="multipart/form-data">
<div class="block"><label>Страница:</label><input  style="width:450px" type="text" name="page" value="" class="finput"/></div>
<textarea style="display:none" class="mceEditor" style="width: 70%" id="editor1"></textarea>
<div class="block"><label>Позиция:</label><input class="finput" style="width:50px" type="text" name="place" value=""/></div>
<div class="block"><label>Ссылка:</label><input class="finput" style="width:450px" type="text" name="vlink" value=""/></div>
<div class="block">
<label>Файл картинки:</label>
<div style="display: inline-block;float:left "><input  name="foto" class="finput" value="" id="url_abs_nohost" onchange="nwhch(this);"/></div>
<div class="btn btn1" onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<?=SITE_URL?>',use_url_path : true,url:$('#url_abs_nohost').val()});">Обзор...</div>
</div>
<div class="block"><label>Ширина:</label><input  style="width:50px" type="text" name="width" value="412" class="finput" onchange="nwhch(this);"/></div>
<div class="block"><label>Высота:</label><input  style="width:50px" type="text" name="height" value="200" class="finput" onchange="nwhch(this);"/></div>
<div class="block">
<p>Рекомендованный размер картинки 412х200 пикселей для страниц с левым меню и 555х200 пикселей для страниц без левого меню.</p>
<img style="margin:0px 20px 5px 5px; border: 1px solid black;width: 412px;height: 200px" id="foto_main" alt="" src="s.gif" onclick="return hs.expand(this, { src: '<?=SITE_URL?>'+$('#url_abs_nohost').val() } )"/>
</div>	 
	<?=button1('Cохранить',"SaveNewBanner(this);",'','save')?>
	<br/>
	</form>
	</div>	<?
}
function banner_on(){
	global $db;
	if ($_GET['type']=='ban') {
		$row = $db->get(TABLE_BANNERS,$_GET['id']);
		$db->update(TABLE_BANNERS,$_GET['id'],array('enabled'=>($row['enabled']==1)?0:1));
		$row = $db->get(TABLE_BANNERS,$_GET['id']);
		$text = ($row['enabled']==1)?'Включен':'Выключен';
		return $text;
	}
	if ($_GET['type']=='slb') {
		$row = $db->get(TABLE_SLBANNERS,$_GET['id']);
		$db->update(TABLE_SLBANNERS,$_GET['id'],array('enabled'=>($row['enabled']==1)?0:1));
		$row = $db->get(TABLE_SLBANNERS,$_GET['id']);
		$text = ($row['enabled']==1)?'Включен':'Выключен';
		return $text;
	}
	if ($_GET['type']=='sld') {
		$row = $db->get(TABLE_SLIDERS,$_GET['id']);
		$db->update(TABLE_SLIDERS,$_GET['id'],array('enabled'=>($row['enabled']==1)?0:1));
		$row = $db->get(TABLE_SLIDERS,$_GET['id']);
		$text = ($row['enabled']==1)?'Включен':'Выключен';
		return $text;
	}
}
function NewSlider(){?>
<div align="left"><form id="fslide" action="" enctype="multipart/form-data">
<div class="block"><label>Порядок:</label><input  style="width:50px" type="text" name="ord" value="1" class="finput"/></div>
<textarea style="display:none" class="mceEditor" id="editor1"></textarea>
<div class="block"><label>Ссылка:</label><input class="finput" style="width:450px" type="text" name="vlink" value=""/></div>
<div class="block"><label>Строка1:</label><input class="finput" style="width:450px" type="text" name="str1" value=""/></div>
<div class="block"><label>Строка2:</label><input class="finput" style="width:450px" type="text" name="str2" value=""/></div>
<div class="block">
<label>Файл картинки:</label>
<div style="display: inline-block;float:left "><input  name="foto" class="finput" value="" id="url_abs_nohost" onchange="nswhch(this);"/></div>
<div class="btn btn1" onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<?=SITE_URL?>',use_url_path : true,url:$('#url_abs_nohost').val()});">Обзор...</div>
</div>
<div class="block">
<p>Pазмер картинки только 770х437 пикселей.</p>
<img style="margin:0px 20px 5px 5px; border: 1px solid black;width: 385px;height: 218px" id="foto_main" alt="" src="s.gif" onclick="return hs.expand(this, { src: '<?=SITE_URL?>'+$('#url_abs_nohost').val() } )"/>
</div>	 
	<?=button1('Cохранить',"SaveNewSlider(this);",'','save')?>
	<br/>
	</form>
	</div>	<?
}
function EditSlider(){
	global $db;
	$res = $db->get(TABLE_SLIDERS, $_GET['id']);
?>
<div align="left"><form id="fslide" action="" enctype="multipart/form-data">
<textarea style="display:none" class="mceEditor" id="editor1"></textarea>
<input name="id" type="hidden" value="<?=$res['id']?>"/>
<div class="block"><label>Порядок:</label><input readonly="readonly" id="fum" class="finput" style="width:450px" type="text" name="ord" value="<?=$res['ord']?>"/></div>
<div class="block"><label>Ссылка:</label><input readonly="readonly" class="finput" style="width:450px" type="text" name="vlink" value="<?=$res['vlink']?>"/></div>
<div class="block"><label>Строка1:</label><input readonly="readonly" class="finput" style="width:450px" type="text" name="str1" value="<?=$res['str1']?>"/></div>
<div class="block"><label>Строка2:</label><input readonly="readonly" class="finput" style="width:450px" type="text" name="str2" value="<?=$res['str2']?>"/></div>
<div class="block">
<label>Файл картинки:</label>
<div style="display: inline-block;float:left "><input readonly="readonly" name="foto" class="finput" value="<?=$res['foto']?>" id="url_abs_nohost" onchange="document.getElementById('foto_main').src = '<?=SITE_URL?>thumb.php?id=' + document.getElementById('url_abs_nohost').value + '&x=770&y=437'"/></div>
<div class="btn btn1" onclick="mcImageManager.browse({fields : 'url_abs_nohost', relative_urls : true, document_base_url : '<?=SITE_URL?>',use_url_path : true,url:$('#url_abs_nohost').val()});">Обзор...</div>
</div>
<div class="block">
<p>Pазмер картинки только 770х437 пикселей.</p>
<img style="margin:0px 20px 5px 5px;border: 1px solid black; width: 385px;height: 218px" id="foto_main" alt="" src="<?=SITE_URL?>thumb.php?id=<?=$res['foto']?>&x=770&y=437" onclick="return hs.expand(this, { src: '<?=SITE_URL?>'+$('#url_abs_nohost').val() } )"/></div>
	 
	<?=button1('Редактировать',"SaveSlider(this);",'','edit')?>
	<br/>
	</form>
	</div>		
<?	
}

?>