<?php

require_once 'Utils.php';
require_once 'Log.php';

//use DateTime;

/**
 * The implementation of payment notification methods.
 */
class YaMoneyCommonHttpProtocol {

    private $action;
    private $settings;
    private $log;
    private $params;

    public function __construct($action, Settings $settings,$params = NULL) {
        $this->action = $action;
        $this->settings = $settings;
        $this->params = $params;
        $this->log = new Log($settings);
    }
    public function getform($sum=150,$user,$orderNumber,$orderid,$mess='') {
 		$res='';
		$sum=number_format($sum,2,'.','');
		ob_start();
/*<tr><td align="right">Фамилия:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="Firstname" VALUE="<?=$user["inf_dop"][9]?>"></td></tr>
<tr><td align="right">Имя:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="Lastname" VALUE="<?=$user["inf_dop"][8]?>"></td></tr>
<tr><td align="right">Отчество:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="Middlename" VALUE="<?=$user["inf_dop"][11]?>"></td></tr>
<input name="paymentType" value="AC" type="hidden"/>
<p style="color: #a80000;font-size: 14px;font-weight: bold;">Внимание, платежи на сумму менее 100 руб. не принимаются платежной системой!</p>
*/
?>
<div class="prdcard" style="width:390px;left:50%;margin: 0 0 0 -210px;position: relative;">
<form id="pfrm" action="<?=$this->settings->YPATH?>" method="post">
<p>Пожалуйста, проверьте данные платежа. После нажатия на кнопку оплатить Вы будете перенаправлены на страницу платежной системы Яндекс Касса</p>
<INPUT TYPE="HIDDEN" NAME="shopId" VALUE="<?=$this->settings->SHOP_ID?>">
<input name="scid" value="<?=$this->settings->SCID?>" type="hidden"/>
<INPUT TYPE="HIDDEN" NAME="OrderNumber" VALUE="<?=$orderNumber?>">
<INPUT TYPE="HIDDEN" NAME="OrderId" VALUE="<?=$orderid?>">
<input name="customerNumber" value="<?=$user["id"]?>" type="hidden"/>

<?if ($orderNumber!=$orderid){?>
<p>Всвязи с частичной оплатой для Вас сформирован новый номер заказа в платежной системе: <?=$orderNumber?></p>
<p>Номер заказа в личном кабинете остался прежним: <?=$orderid?></p>
<?}
if (!empty($mess)){?>

<p><?=$mess?></p>	
<?}?>
<table class="pay" >
<tr><td align="left" colspan="2">Заказ №&nbsp;<?=$orderNumber?><br/></td></tr>
<tr><td align="right">Сумма платежа</td><td align="left"><input name="sum" id="sum" value="<?=$sum?>" class="fbinp" <?echo (!empty($mess))?'type="text"':'type="hidden"'?> style="width:60px"><?=(!empty($mess))?'':$sum?></td></tr>
<tr><td align="left" colspan="2">Плательщик:</td></tr>
<tr><td align="right">E-mail:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="cps_email" VALUE="<?=$user['login']?>"></td></tr>
<tr><td align="right">Телефон:</td><td align="left"><INPUT TYPE="text" class="fbinp" NAME="cps_phone" VALUE="<?=$user["inf_dop"][14]?>"></td></tr>
</table>
<div id="err" class=""></div>
</FORM>
<script>
jQuery().ready(function(){jQuery("#pfrm").validate({rules : {sum:{required : true, min:1}},
messages : {sum : {required : "<span class=\"frm_err\">Заполните поле Cумма</span>", min : "<span class=\"frm_err\">Введите число больше 1!</span>"}},submitHandler: function(form) {		var m_data=$(form).serialize();
		$.ajax({type: "post",url: "service/savepay",data: m_data,success: function(html){
			var res=JSON.parse(html);
			if (res.result == 1){
				form.submit();
			} else {
				$('#err').html(res.error);
			}}
		});
  }});
});
function f_send(){
	$('#pfrm').submit();
}
</script>
<br><button style="left:140px;position:relative" onclick="f_send(); return false;" class="albutton alorange" name="send" type="button" value="Оплатить"><span><span><span class="ok">Оплатить</span></span></span></button></div>
<?
		$res.=ob_get_contents();
		ob_end_clean();
		return $res;    	
    }

    /**
     * Processes "checkOrder" and "paymentAviso" requests.
     * @param array $request payment parameters
     */
    public function processRequest($request) {
        $this->log("Start " . $this->action);
        $this->log("Security type " . $this->settings->SECURITY_TYPE);
        if ($this->settings->SECURITY_TYPE == "MD5") {
            $this->log("Request: " . print_r($request, true));
            // If the MD5 checking fails, respond with "1" error code
            if (!$this->checkMD5($request)) {
                $response = $this->buildResponse($this->action, $request['invoiceId'], 1);
                $this->sendResponse($response);
                return false;
            }
        } else if ($this->settings->SECURITY_TYPE == "PKCS7") {
            // Checking for a certificate sign. If the checking fails, respond with "200" error code.
            if (($request = $this->verifySign()) == null) {
                $response = $this->buildResponse($this->action, null, 200);
                $this->sendResponse($response);
                return false;
            }
            $this->log("Request: " . print_r($request, true));
        }
        $response = null;
        if ($this->action == 'checkOrder') {
            $response = $this->checkOrder($request);
        } else {
            $response = $this->paymentAviso($request);
        }
        $this->sendResponse($response);
        return true;
    }

    /**
     * CheckOrder request processing. We suppose there are no item with price less
     * than 100 rubles in the shop.
     * @param  array $request payment parameters
     * @return string         prepared XML response
     */
    private function checkOrder($request) {
        $response = null;
//        if ($request['orderSumAmount'] > 5000) {
//            $response = $this->buildResponse($this->action, $request['invoiceId'], 100, "The amount should be less than 5000 rubles.");
//        } else {
            $response = $this->buildResponse($this->action, $request['invoiceId'], 0);
//        }
        return $response;
    }

    /**
     * PaymentAviso request processing.
     * @param  array $request payment parameters
     * @return string prepared response in XML format
     */
    private function paymentAviso($request) {
        return $this->buildResponse($this->action, $request['invoiceId'], 0);
    }

    /**
     * Checking the MD5 sign.
     * @param  array $request payment parameters
     * @return bool true if MD5 hash is correct 
     */
    private function checkMD5($request) {
        $str = $request['action'] . ";" .
            $request['orderSumAmount'] . ";" . $request['orderSumCurrencyPaycash'] . ";" .
            $request['orderSumBankPaycash'] . ";" . $request['shopId'] . ";" .
            $request['invoiceId'] . ";" . $request['customerNumber'] . ";" . $this->settings->SHOP_PASSWORD;
        $this->log("String to md5: " . $str);
        $md5 = strtoupper(md5($str));

        if ($md5 != strtoupper($request['md5'])) {
            $this->log("Wait for md5:" . $md5 . ", recieved md5: " . $request['md5']);
            return false;
        }
        return true;
    }

    /**
     * Building XML response.
     * @param  string $functionName  "checkOrder" or "paymentAviso" string
     * @param  string $invoiceId     transaction number
     * @param  string $result_code   result code
     * @param  string $message       error message. May be null.
     * @return string                prepared XML response
     */
    private function buildResponse($functionName, $invoiceId, $result_code, $message = null) {
        try {
            $performedDatetime = Utils::formatDate(new DateTime());
            $response = '<?xml version="1.0" encoding="UTF-8"?><' . $functionName . 'Response performedDatetime="' . $performedDatetime .
                '" code="' . $result_code . '" ' . ($message != null ? 'message="' . $message . '"' : "") . ' invoiceId="' . $invoiceId . '" shopId="' . $this->settings->SHOP_ID . '"/>';
            return $response;
        } catch (\Exception $e) {
            $this->log($e);
        }
        return null;
    }

    /**
     * Checking for sign when XML/PKCS#7 scheme is used.
     * @return array if request is successful, returns key-value array of request params, null otherwise.
     */
    private function verifySign() {
        $descriptorspec = array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("pipe", "w"));
        $certificate = 'yamoney.pem';
        $process = proc_open('openssl smime -verify -inform PEM -nointern -certfile ' . $certificate . ' -CAfile ' . $certificate,
            $descriptorspec, $pipes);
        if (is_resource($process)) {
            // Getting data from request body.
            $data = file_get_contents($this->settings->request_source); // "php://input"
            fwrite($pipes[0], $data);
            fclose($pipes[0]);
            $content = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $resCode = proc_close($process);
            if ($resCode != 0) {
                return null;
            } else {
                $this->log("Row xml: " . $content);
                $xml = simplexml_load_string($content);
                $array = json_decode(json_encode($xml), TRUE);
                return $array["@attributes"];
            }
        }
        return null;
    }

    private function log($str) {
        $this->log->info($str);
    }

    private function sendResponse($responseBody) {
        $this->log("Response: " . $responseBody);
        header("HTTP/1.0 200");
        header("Content-Type: application/xml");
        echo $responseBody;
        return true;
    }
}
global $gmess;
$gmess.="/yak.YaMoneyCommonHttpProtocol";

?>
