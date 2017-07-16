<?php



class Settings {

    public $SHOP_PASSWORD = "Chudo2016Clumba";
    public $SECURITY_TYPE;
    public $LOG_FILE;
    public $YPATH = "https://money.yandex.ru/eshop.xml";
    public $SHOP_ID = 52961;
    public $SCID = 50197;
    public $CURRENCY = 10643;
    public $request_source;
    public $mws_cert;
    public $mws_private_key;
    public $mws_cert_password = "123456";

    function __construct($SECURITY_TYPE = "MD5" /* MD5 | PKCS7 */, $request_source = "php://input",$test_mode = true) {
        $this->SECURITY_TYPE = $SECURITY_TYPE;
        $this->request_source = $request_source;
        $this->LOG_FILE = dirname(__FILE__)."/log.txt";
        $this->mws_cert = dirname(__FILE__)."/mws/shop.cer";
        $this->mws_private_key = dirname(__FILE__)."/mws/private.key";
        if ($test_mode) $this->YPATH="https://money.yandex.ru/eshop.xml";
    }
}
$gmess.="/yak.settings";
