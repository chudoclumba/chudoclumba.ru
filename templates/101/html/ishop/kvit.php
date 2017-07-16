
<?php
$sumr=sprintf('%1$d руб. %2$02d коп.', floor($sum), round(($sum-floor($sum))*100));
?>

<div>
	<br /><br />
	<table border=1 cellspacing=0 cellpadding=0 bgcolor=#FFFFFF bordercolor=#000000 height=500>
		<tr>
			<td width=170 rowspan=9 align=center valign=top>
					ИЗВЕЩЕНИЕ
			</td>
			<td colspan=3 nowrap><b>
				&nbsp;	ООО "Клумба"</b>
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;
				<b>
					ИНН
				</b>&nbsp;5018184855&nbsp;
			</td>
			<td colspan=2 nowrap align=right>
				&nbsp;<b>Р/счет&nbsp;</b>40702810110000009476&nbsp;
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;в&nbsp;АО «Тинькофф Банк»&nbsp;
			</td>
			<td colspan=2 nowrap align=right>
				<b>БИК&nbsp;</b>044525974&nbsp;
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;<font size=2>Номер кор/сч. банка получателя</font>&nbsp;
			</td>
			<td colspan=2 nowrap align=right>
				30101810145250000974&nbsp;
				
			</td>
		</tr>
		<tr>
			<td nowrap>
				<font size="2">
					<nobr>
						&nbsp;Оплата заказа №&nbsp;<?=$zak['id']?>
						&nbsp;<br>&nbsp;в интернет-магазине "Чудо-клумба"
						&nbsp;
					</nobr>
				</font>
			</td>
			<td align="center" colspan=2 nowrap>
				<br>
				<u>
					<font size=1>номер лицевого счета (код) плательщика</font>
				</u>&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="3" nowrap>
				&nbsp;<font size=1><b>ФИО плательщика:&nbsp;<?=$fio?><br>&nbsp;
					Адрес плательщика:&nbsp;<?=$adr?>
				</b></font>
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;
				<b>
					<font size=1>Сумма платежа:</font>
				</b>
					&nbsp; &nbsp;<?=$sumr?>
			</td>
			<td colspan=2 nowrap align=right>
				&nbsp;
				<b>
					<font size=1>Сумма платы за услуги:</font>
				</b>
				<u>
					 &nbsp; &nbsp; &nbsp; &nbsp; руб.&nbsp; &nbsp; &nbsp; коп.
				</u>&nbsp;
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;
				<b>
					<font size=2>Итого:</font>
				</b>
					&nbsp; &nbsp;<?=$sumr?>
			</td>
			<td nowrap colspan=2 nowrap align=right>
				&nbsp;<b>"___"___________ 2016г.&nbsp;</b>
			</td>
		</tr><tr>
		<td colspan="3" align=right>
			&nbsp;
			<font size=1>
				С условиями приема указанной в платежном документе суммы, в т.ч. взимаемой платы за услуги  &nbsp; &nbsp; &nbsp;<br>банка, ознакомлен и согласен &nbsp;  &nbsp;
			</font>
			&nbsp; &nbsp; &nbsp;<font size=2> Подпись плательщика</font>
			<u>
				&nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			</u>&nbsp;
		</td></tr>


		<tr>
			<td width=170 rowspan=9 align=center valign=top>
					КВИТАНЦИЯ
			</td>
			<td colspan=3 nowrap>
				<b>
					&nbsp;ООО "Клумба"
				</b>
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;
				<b>
					ИНН
				</b>&nbsp;5018184855&nbsp;
			</td>
			<td colspan=2 nowrap align=right>
				&nbsp;<b>Р/счет&nbsp;</b>40702810110000009476&nbsp;
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;в&nbsp;АО «Тинькофф Банк»&nbsp;
			</td>
			<td colspan=2 nowrap align=right>
				<b>БИК&nbsp;</b>044525974&nbsp;
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;<font size=2>Номер кор/сч. банка получателя&nbsp;</font>
			</td>
			<td colspan=2 nowrap align=right>
				30101810145250000974&nbsp;
			</td>
		</tr>
		<tr>
			<td  nowrap>
				<font size="2">
					<nobr>
						&nbsp;Оплата заказа №&nbsp;<?=$zak['id']?>
						&nbsp;<br>&nbsp;в интернет-магазине "Чудо-клумба"&nbsp;
					</nobr>
				</font>
			</td>
			<td align="center" colspan=2 nowrap>
				<br>
				<u><font size=1>номер лицевого счета (код) плательщика</font></u>&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="3" nowrap>
				&nbsp;<font size=1><b>ФИО плательщика:&nbsp;<?=$fio?><br>&nbsp;
					Адрес плательщика:&nbsp;<?=$adr?>
				</b></font>
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;
				<b>
					<font size=1>Сумма платежа:</font>
				</b>
					&nbsp; &nbsp;<?=$sumr?>
			</td>
			<td colspan=2 nowrap align=right>
				&nbsp;
				<b>
					<font size=1>Сумма платы за услуги:</font>
				</b>
				<u>
					 &nbsp; &nbsp; &nbsp; &nbsp; руб.&nbsp; &nbsp; &nbsp; коп.
				</u>&nbsp;
			</td>
		</tr>
		<tr>
			<td nowrap>
				&nbsp;
				<b>
					<font size=2>Итого:</font>
				</b>
					&nbsp; &nbsp;<?=$sumr?>
			</td>
			<td nowrap colspan=2 nowrap align=right>
				&nbsp;<b>"___"___________ 2016г.&nbsp;</b>
			</td>
		</tr><tr>
		<td colspan="3" align=right>
			&nbsp;
			<font size=1>
				С условиями приема указанной в платежном документе суммы, в т.ч. взимаемой платы за услуги  &nbsp; &nbsp; &nbsp;<br>банка, ознакомлен и согласен &nbsp;  &nbsp;
			</font>
			&nbsp; &nbsp; &nbsp;<font size=2> Подпись плательщика</font>
			<u>
				&nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			</u>&nbsp;
		</td></tr>
	</table>
</div>
<br />
<div class="noprint">
	<button name="Print" type="button" value="ok" onclick="window.print()" class="albutton alorange">
		<span>
			<span>
				<span class="print">
					Распечатать
				</span>
			</span>
		</span>
	</button>
</div>