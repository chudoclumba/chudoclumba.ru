<?php

/**
 * Created by PhpStorm.
 * User: victor
 * Date: 08.08.17
 * Time: 16:00
 */

include_once "../Logger.php";

class Promo
{
    private $promo_id;
    private $promo_code;
    private $expirationdate;
    private $value;


    /**
     * Promo constructor.
     */
    public function __construct()
    {
        Logger::Info("Constructor enter");
        $this->promo_id = 0;
        Logger::Info("1");
        $this->promo_code = '';
        Logger::Info("2");
        $this->expirationdate = new DateTime();
        Logger::Info("3");
        $this->value = 0;
        Logger::Info("Constructor leave");
    }

    /**
     * @return int
     */
    public function getPromoId()
    {
        return $this->promo_id;
    }

    /**
     * @param int $promo_id
     */
    public function setPromoId($promo_id)
    {
        $this->promo_id = $promo_id;
    }

    /**
     * @return int
     */
    public function getPromoCode()
    {
        return $this->promo_code;
    }

    /**
     * @param int $promo_code
     */
    public function setPromoCode($promo_code)
    {
        $this->promo_code = $promo_code;
    }

    /**
     * @return int
     */
    public function getExpirationdate()
    {
        return $this->expirationdate;
    }

    /**
     * @param int $expirationdate
     */
    public function setExpirationdate($expirationdate)
    {
        $this->expirationdate = $expirationdate;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    function __toString()
    {
        return "PROMO: ID=" . $this->promo_id . "; CODE=" . $this->promo_code . "; EXP_DATE=" . $this->expirationdate->format('Y-m-d') . "; VALUE=" . $this->value;
    }


}