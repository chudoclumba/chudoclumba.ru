<?php

/**
 * Created by PhpStorm.
 * User: victor
 * Date: 08.08.17
 * Time: 16:00
 */

include "Promo.php";
include_once "./Logger.php";

class CantCreateException extends Exception{
}

class PromoEngine
{

    private static $instance;

    private $db;

    /**
     * PromoEngine constructor.
     */
    public function __construct()
    {
        $this->db = Site::gI()->db;
    }

    public static function Instance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }




    public function getPromoByCode($promoCode)
    {
        $res = null;
        $query = "select * from ".TABLE_PROMO." where promo=".$this->db->escape_string($promoCode);
        Logger::Info("PromoEngine::getPromoByCode. ".$query);
        $rows=$this->db->get_rows($query);
        if (count($rows)>0)
        {
            try
            {
                $res = new Promo;
                $res->setPromoId($rows[0]["id"]);
                $res->setPromoCode($promoCode);

                $format = 'Y-m-d';
                $date = DateTime::createFromFormat($format, $rows[0]["expiration_date"]);
                $res->setExpirationdate($date);

                $res->setValue($rows[0]["value"]);

                Logger::Info("PromoEngine::getPromoByCode. ".$res);

            }
            catch (CantCreateException $e)
            {
                Logger::Info('Caught exception: ' .  $e->getMessage());
            }
        }
        else
        {
            Logger::Info("PromoEngine::getPromoByCode. Failed to get promo for code: ".$promoCode);
        }
        return $res;
    }

}