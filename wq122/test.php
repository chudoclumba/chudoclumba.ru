<?php
//include the library
include_once "../includes/kanfih.php";
//require_once('RussianPostAPI.php');

//try {
  //init the client
  $client = new SoapClient("http://voh.russianpost.ru:8080/niips-operationhistory-web/OperationHistory?wsdl", array('trace' => 1,'exceptions' => 0));
//  $data=$client->__soapCall("getSmsHistory",array(Array('Barcode'=>'14102077870951')), NULL, new SoapHeader("http://russianpost.org/operationhistory/data","AutorizationHeader",array(new SoapParam('SERmitMih', "login"),new SoapParam('SERkhTPHz', "password"))));
//  $data=$client->PostalOrderEventsForMail(Array('Barcode'=>'14102077870951'));
$data=$client->getoperationhistory(Array('Barcode'=>'14102077870951','MessageType'=>0));
//	var_dump($client->__getTypes());
if (is_soap_fault($data)) {
    trigger_error("SOAP Fault: (faultcode: {$data->faultcode}, faultstring: {$data->faultstring})");
} else
{
	var_dump($data);
}
echo "Request:\n" . $client->__getLastRequest();
echo "\nRESPONSE:\n" . $client->__getLastResponse();
//  $client = new RussianPostAPI();

  //fetch info
/*  var_dump($client->getOperationHistory('14102077873969'));
} catch(RussianPostException $e) {
  die('Something went wrong: ' . $e->getMessage() . "\n");
}*/
?>