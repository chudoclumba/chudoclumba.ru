<?header('Content-type: text/javascript');?>
function addRow()
{
	var d = document;
   // Находим нужную таблицу
    var tbody = d.getElementById('tbl_arts').getElementsByTagName('TBODY')[0];
	var rows = d.getElementById('tbl_arts').getElementsByTagName('tr')[0];
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

	var td1 = d.createElement("TD");
	row.appendChild(td1);
	td1.innerHTML = '<input name="del_art[' + g + ']" value="1" type="checkbox" />';
	td1.align = "center";
	for(var i=0;i<(count_col-1);i++)
	{
		var td2 = d.createElement("TD");
		row.appendChild(td2);
		td2.innerHTML = '<input class="ar_p" type="text" name="value[' + g + '][' + i + ']" value="" />';
	}
}