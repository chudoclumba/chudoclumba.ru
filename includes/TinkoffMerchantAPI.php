<?php
/**
 * File TinkoffMerchantAPI
 *
 * PHP version 5.3
 *
 * @category Tinkoff
 * @package  Tinkoff
 * @author   Shuyskiy Sergey <s.shuyskiy@tinkoff.ru>
 * @license  http://opensource.org/licenses/MIT MIT license
 * @link     http://tinkoff.ru
 */
//namespace Tinkoff;

//use HttpException;

/**
 * Class TinkoffMerchantAPI
 *
 * @category Tinkoff
 * @package  Tinkoff
 * @author   Shuyskiy Sergey <s.shuyskiy@tinkoff.ru>
 * @license  http://opensource.org/licenses/MIT MIT license
 * @link     http://tinkoff.ru
 * @property integer     orderId
 * @property integer     Count
 * @property bool|string error
 * @property bool|string response
 * @property bool|string customerKey
 * @property bool|string status
 * @property bool|string paymentUrl
 * @property bool|string paymentId
 */
class TinkoffMerchantAPI
{
    private $_api_url='https://securepay.tinkoff.ru/rest/';
    private $_terminalKey='imchudoclumba';
    private $_secretKey='x6mptxjtkzlhv7cl';
    private $_paymentId;
    private $_status;
    private $_error;
    private $_response;
    private $_paymentUrl;

    /**
     * Constructor
     *
     * @param string $terminalKey Your Terminal name
     * @param string $secretKey   Secret key for terminal
     * @param string $api_url     Url for API
     */
    public function __construct($params=array())
    {
        if (isset($params['api_url'])) $this->_api_url = $params['api_url'];
        if (isset($params['TerminalKey'])) $this->_terminalKey = $params['TerminalKey'];
        if (isset($params['SecretKey'])) $this->_secretKey = $params['SecretKey'];
    }

    /**
     * Get class property or json key value
     *
     * @param mixed $name Name for property or json key
     *
     * @return bool|string
     */
    public function __get($name)
    {
        switch ($name) {
        case 'paymentId':
            return $this->_paymentId;
        case 'status':
            return $this->_status;
        case 'error':
            return $this->_error;
        case 'PaymentUrl':
            return $this->_paymentUrl;
        case 'TerminalKey':
            return $this->_terminalKey;
        case 'response':
            return htmlentities($this->_response);
        default:
            if ($this->_response) {
                if ($json = json_decode($this->_response, true)) {
                    foreach ($json as $key => $value) {
                        if (strtolower($name) == strtolower($key)) {
                            return $json[$key];
                        }
                    }
                }
            }

            return false;
        }
    }

    /**
     * Initialize the payment
     *
     * @param mixed $args mixed You could use associative array or url params string
     *
     * @return bool
     */
    public function init($args)
    {
        return $this->buildQuery('Init', $args);
    }

    /**
     * Get state of payment
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function getState($args)
    {
        return $this->buildQuery('GetState', $args);
    }

    /**
     * Confirm 2-staged payment
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function confirm($args)
    {
        return $this->buildQuery('Confirm', $args);
    }

    /**
     * Performs recursive (re) payment - direct debiting of funds from the
     * account of the Buyer's credit card.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function charge($args)
    {
        return $this->buildQuery('Charge', $args);
    }

    /**
     * Registers in the terminal buyer Seller. (Init do it automatically)
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function addCustomer($args)
    {
        return $this->buildQuery('AddCustomer', $args);
    }

    /**
     * Returns the data stored for the terminal buyer Seller.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function getCustomer($args)
    {
        return $this->buildQuery('GetCustomer', $args);
    }

    /**
     * Deletes the data of the buyer.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function removeCustomer($args)
    {
        return $this->buildQuery('RemoveCustomer', $args);
    }

    /**
     * Returns a list of bounded card from the buyer.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function getCardList($args)
    {
        return $this->buildQuery('GetCardList', $args);
    }

    /**
     * Removes the customer's bounded card.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function removeCard($args)
    {
        return $this->buildQuery('RemoveCard', $args);
    }

    /**
     * Cancel payment.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function cancel($args)
    {
        return $this->buildQuery('Cancel', $args);
    }
    
    /**
     * The method is designed to send all unsent notification
     *
     * @return mixed
     */
    public function resend()
    {
        return $this->buildQuery('Resend', array());
    }

    /**
     * Builds a query string and call sendRequest method.
     * Could be used to custom API call method.
     *
     * @param string $path API method name
     * @param mixed  $args query params
     *
     * @return mixed
     * @throws HttpException
     */
    public function buildQuery($path, $args)
    {
        $url = $this->_api_url;
        if (is_array($args)) {
           if (! array_key_exists('TerminalKey', $args)) {
                $args['TerminalKey'] = $this->_terminalKey;
           }
            if (! array_key_exists('Token', $args)) {
                $args['Token'] = $this->genToken($args);
            }
        }
        $url = $this->_combineUrl($url, $path);

        return $this->_sendRequest($url, $args);
    }

    /**
     * Generates token
     *
     * @param array $args array of query params
     *
     * @return string
     */
    public function genToken($args)
    {
        $token = '';
        unset($args['Token']);
        $args['Password'] = $this->_secretKey;
        ksort($args);
        foreach ($args as $arg) {
            $token .= $arg;
        }
        $token = hash('sha256', $token);

        return $token;
    }

    /**
     * Combines parts of URL. Simply gets all parameters and puts '/' between
     *
     * @return string
     */
    private function _combineUrl()
    {
        $args = func_get_args();
        $url = '';
        foreach ($args as $arg) {
            if (is_string($arg)) {
                if ($arg[strlen($arg) - 1] !== '/') {
                    $arg .= '/';
                }
                $url .= $arg;
            } else {
                continue;
            }
        }

        return $url;
    }

    /**
     * Main method. Call API with params
     *
     * @param string $api_url API Url
     * @param array  $args    API params
     *
     * @return mixed
     * @throws HttpException
     */
    private function _sendRequest($api_url, $args)
    {
        $this->_error = '';
        //todo add string $args support
        //$proxy = 'http://192.168.5.22:8080';
        //$proxyAuth = '';
        if (is_array($args)) {
            $args = http_build_query($args);
        }
        Debug::log($args);
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
            $out = curl_exec($curl);

            $this->_response = $out;
            $json = json_decode($out);
            if ($json) {
                if (@$json->ErrorCode !== "0") {
                    $this->_error = @$json->Details;
                } else {
                    $this->_paymentUrl = @$json->PaymentURL;
                    $this->_paymentId = @$json->PaymentId;
                    $this->_status = @$json->Status;
                }
            }

            curl_close($curl);

            return $out;

        } else {
            throw new HttpException(
                'Can not create connection to ' . $api_url . ' with args '
                . $args, 404
            );
        }
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
<div class="row">
<div class="col-lg-3 col-md-2 col-sm-1 hidden-xs">
</div>
<div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">

<form id="pfrm" action="<?=$this->settings->YPATH?>" method="post">
<div class="form-fields">
<h2>Заказ №&nbsp;<?=$orderNumber?></h2>
<p>Пожалуйста, проверьте данные платежа. После нажатия на кнопку оплатить Вы будете перенаправлены на страницу платежной системы Тинькофф Онлайн Платежи</p>
<INPUT TYPE="HIDDEN" NAME="OrderNumber" VALUE="<?=$orderNumber?>">
<INPUT TYPE="HIDDEN" NAME="OrderId" VALUE="<?=$orderid?>">
<input name="customerNumber" value="<?=$user["id"]?>" type="hidden"/>

<?if ($orderNumber!=$orderid){?>
<p>В связи с частичной оплатой для Вас сформирован новый номер заказа в платежной системе: <?=$orderNumber?></p>
<p>Номер заказа в личном кабинете остался прежним: <?=$orderid?></p>
<?}
if (!empty($mess)){?>

<p><?=$mess?></p>	
<?}?>
<p><label>Сумма платежа</label><input name="sum" id="sum" value="<?=$sum?>" class="fbinp" <?echo (!empty($mess))?'type="text"':'type="hidden"'?>><?=(!empty($mess))?'':$sum?></p>
<p><label>E-mail:</label><INPUT TYPE="text" class="fbinp" NAME="cps_email" VALUE="<?=$user['login']?>"></p>
<p><label>Телефон:</label><INPUT TYPE="text" class="fbinp" NAME="cps_phone" VALUE="<?=$user["inf_dop"][14]?>"></p>
<div id="err" style="color: red"></div>
</div>
<div class="form-action">
    <input class="floatleft" type="submit" value="Оплатить" onclick="f_send(); return false;" >
</div>

</FORM>
<script>
jQuery().ready(function(){jQuery("#pfrm").validate({rules : {sum:{required : true, min:1}},
messages : {sum : {required : "<span class=\"frm_err\">Заполните поле Cумма</span>", min : "<span class=\"frm_err\">Введите число больше 1!</span>"}},submitHandler: function(form) {		var m_data=$(form).serialize();
		$.ajax({type: "post",url: "service/savepay/tin",data: m_data,success: function(html){
			var res=JSON.parse(html);
			if (res.result == 1){
				document.location.href=res.result_url;
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
</div></div>
<?
		$res.=ob_get_contents();
		ob_end_clean();
		return $res;    	
    }
}
