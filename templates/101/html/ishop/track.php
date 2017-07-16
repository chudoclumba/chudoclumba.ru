<div class="prdcard">

<div class="" style="display: inline-block; ">
<table class="col">
	<tr>
	  <td class="ord_ir">Вес:</td>
	  <td class="ord_il"><?=$data[0]->itemWeight?><br/></td>
	</tr>
	<tr>
	  <td class="ord_ir">Наложенный платеж:</td>
	  <td class="ord_il"><?=$data[0]->collectOnDeliveryPrice." руб."?></td>
	</tr>
	<tr>
	  <td class="ord_ir">Адрес:</td>
	  <td class="ord_il"><?=$data[0]->destinationPostalCode.' '.$data[0]->destinationAddress?></td>
	</tr>
</table>
</div>
<div style="clear: both"></div><br/>
<table class="ppr_mt cart_t">
 <tr>
  <th class="tcen">Дата</th>
  <th class="tcen">Индекс</th>
  <th class="tcen" >Место</th>
  <th class="tcen" >Операция</th>
  <th class="tcen">Информация</th>
  </tr>
<? // TODO Периписать форму
	$num=0;
  foreach ($data as $message) {
  $num++;
  echo "<tr>";
  echo "<td>".date("d.m.Y H:i", strtotime($message->operationDate))."</td>";//Дата
  echo '<td class="tcen" style="width:80px">'.$message->operationPlacePostalCode."</td>";//Место проведения операции->Индекс
  echo "<td>".$message->operationPlaceName."</td>";//Место проведения операции->Название ОПС
  echo "<td>".$message->operationType."</td>";//Операция
  echo "<td>".$message->operationAttribute."</td>\n";//Атрибут операции
  echo "</tr>\n";
  }

 ?>
</table>
</div>
	

