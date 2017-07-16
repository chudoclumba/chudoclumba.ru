<table>
<?foreach($users as $id=>$user) {?>
<tr>
 <td>
<a href="user/anketa/<?=$user['id']?>"><?=$user['fio']?></a>
</td></tr>
<?}?>
</table>