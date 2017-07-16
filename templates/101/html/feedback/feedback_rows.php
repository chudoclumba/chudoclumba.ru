<? if(isSet($data['posted'])) { ?>
<div style="color:red; font-size:14px; padding:20px;">Некорректно заполнены поля</div>
<? } ?>
<form method="post" action="" id="frmFeedback">
<table cellpadding=0 cellspacing=3 border=0>
<tr><td class="fb_id">Фамилия Имя Отчество</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" id="p1" name="p1" value="<?=(!empty($data['p1'])  ? htmlspecialchars($data['p1'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td></tr>
<tr><td class="fb_id">Телефон</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" id="p2" name="p2" value="<?=(!empty($data['p2'])  ? htmlspecialchars($data['p2'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td></tr>
<tr><td class="fb_id">Email</td></tr>
<tr><td class="fb_input"><input class="fbinp" type="text" id="p3" name="p3" value="<?=(!empty($data['p3'])  ? htmlspecialchars($data['p3'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?>"></td></tr>
<tr><td class="fb_id">Ваше сообщение</td></tr>
<tr><td class="fb_input"><textarea class="fxtxt" id="p4" name="p4"><?=(!empty($data['p4'])  ? htmlspecialchars($data['p4'],ENT_COMPAT | ENT_XHTML,'cp1251') : '')?></textarea></td></tr>
<tr><td class="fb_id"><p>D чистовой – желаемый диаметр колеса, после наварки.</p>
<p>D по металлу – начальный/исходный диаметр колеса, без полиуретана.</p>                   
<p>Н – ширина колеса.</p>
</td></tr>
<?if(1!=1){?>
<tr><td class="fb_input">
<table class="kls col " id="tbl_arts">
 <tr>
  <th>D чистовой</th>
  <th>D по металлу</th>
  <th>Н (ширина)</th>
  <th>нагрузка</th>
 </tr>
 <tr>
  <td><input name="value[0][0]" type="text" /></td>
  <td><input name="value[0][1]" type="text" /></td>
  <td><input name="value[0][2]" type="text" /></td>
  <td><input name="value[0][3]" type="text" /></td>
 </tr>
</table>
<div><input value="+" type="button" onclick="addRow()" /></div>
<script>
function addRow()
{
	var d = document;
   // Находим нужную таблицу
    var tbody = d.getElementById('tbl_arts').getElementsByTagName('TBODY')[0];
	var rows = d.getElementById('tbl_arts').getElementsByTagName('tr')[1];
	var rows2 = d.getElementById('tbl_arts').getElementsByTagName('tr');
	var tds = rows.getElementsByTagName('td');
		
	var count_col = tds.length;
	var count_rows = rows2.length;
	
	var g = count_rows - 1;
	
    // Создаем строку таблицы и добавляем ее
    var row = d.createElement("TR");
    tbody.appendChild(row);

    // Создаем ячейки в вышесозданной строке
    // и добавляем тх

	for(var i=0;i<(count_col);i++)
	{
		var td2 = d.createElement("TD");
		row.appendChild(td2);
		td2.innerHTML = '<input name="value[' + g + '][' + i + ']" type="text" value="" />';
	}
}
</script>
</td></tr>
<?}?>
<tr><td class="fb_input" align="left"><input name="send" class="fbb" type="submit" value="Отправить"></td></tr>
</table>
<input type="hidden" name="posted" value="1">
</form>
<script>
    jQuery().ready(function(){
        jQuery("#frmFeedback").validate({
            rules : {
                p1    : {required : true, minlength: 4},
                p2   : {required : true, minlength: 4},
                p3 : {required : true, email : true},
                p4 : {required : true, minlength: 4}
            },
            messages : {
                p1 : {
                    required : "<span class=\"frm_err\">Введите ваше Фамилия Имя Отчество</span>",
                    minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа.</span>"
                },
                p2 : {
                    required : "<span class=\"frm_err\">Введите ваш Телефон</span>",
                    minlength : "<span class=\"frm_err\">Введите не менее, чем 4 символа.</span>"
                },
                p3 : {
                    required : "<span class=\"frm_err\">Введите ваш Email</span>",
                    email : "<span class=\"frm_err\">Введите правильно ваш Email</span>"
                },
				p4 : {
                    required : "<span class=\"frm_err2\">Введите ваше Сообщение</span>",
                    minlength : "<span class=\"frm_err2\">Введите не менее, чем 4 символа.</span>"
                }
            }
        });
        jQuery("send").click(function(){
            jQuery("#frmFeedback").validate();
        });
    });
</script>