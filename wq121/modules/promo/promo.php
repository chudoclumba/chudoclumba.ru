<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 28.08.17
 * Time: 17:24
 */
$btns = array(
    'Список кодов'=>array('id'=>1,'href'=>''),
    'Держатели кодов'=>array('id'=>2,'href'=>'javascript:location.href=\'include.php?place=promo&action=users\'')

);

if (!empty($_POST['copy_news']))
{
    foreach($_POST['box'] as $id => $val)
    {
        $res = $db->get(TABLE_NEWS,$id);
        unset($res['id']);
        $db->insert(TABLE_NEWS,$res);
    }
    header("Location:include.php?place=".$global_place);
}


if(isSet($_GET['rID']))
{
    $db->delete(TABLE_NEWS,array('id' => $_GET['rID']));
    header("Location:include.php?place=".$global_place);
}

if (!empty($_POST['delete_news']) && $_POST['delete_news'] == 1)
{
    foreach ($_POST['box'] as $id => $v)
        $db->delete(TABLE_NEWS,array('id' => $id));

    header("Location:include.php?place=".$global_place);
}

ob_start();
?>
    <div class="news_pan">
        <?php if(ModuleExists('podpiska')) echo button1('Тестовая рассылка', "Send_mtest();",'id="btnst" disabled','sendm')?>
        <?php if(ModuleExists('podpiska')) echo button1('Разослать подписчикам', "Send_m();",'id="btns" disabled','sendm')?>
        <?php echo button1('Копировать', "$('#copy_news').val('1'); forma.submit()",'id="btnc" disabled','copy')?>
        <?php echo button1('Удалить', "if (confirm('Вы действительно хотите удалить выделенные новости?')) { $('#delete_news').val('1'); forma.submit()}",'id="btnd" disabled','delete')?>
    </div>
    <div class="adv_news_pan">
        <?php echo button1('Добавить промо код', "javascript: location.href = '".$prelink.'&action=sub&eID=-1'."'",'','add')?>
    </div>
    <div class="clear"></div>
    <form id="newsfrm" style="margin:3px;" id="frmparts" action="include.php?place=<?php echo $global_place?>" method="post">
        <input type="hidden" id="copy_news" name="copy_news" value="0" />
        <input type="hidden" id="delete_news" name="delete_news" value="0" />
        <?

        $pg_count = $db->count(TABLE_NEWS);

        $page=(isset($_GET['page'])&&$_GET['page']>-1)?$_GET['page']:1;
        $lin = $sets['sus_lines'];
        $pages = ceil($pg_count/$lin);
        $limit =($page != 0 && $pages>1)?" LIMIT ".(($page-1)*$lin).",".$lin:'';
        $mes=($pg_count>0)?(($page>0)?(($page-1)*$lin+1).'-'.(($page*$lin>$pg_count)?$pg_count:$page*$lin).' из '.$pg_count:'1-'.$pg_count.' из '.$pg_count):'';


        $q = $db->get_rows("SELECT * FROM ".TABLE_NEWS." order by cdate DESC, id DESC ".$limit);


        echo get_pages(array ('class' => 'prd_pages_top','count_pages' => $pages, 'curr_page'=> $page, 'link' => 'include.php?place=news&page=','info'=>$mes));
        echo show_news($q);
        echo get_pages(array ('class' => 'prd_pages_bottom','count_pages' => $pages,'curr_page'=> $page,'link' => 'include.php?place=news&page=','info'=>$mes));
        ?>
    </form>
<?php
$module['html'] .= ob_get_contents();
ob_end_clean();