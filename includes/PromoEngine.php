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


    public function getPromoById($promoId)
    {
        $res = null;
        $query = "select * from ".TABLE_PROMO." where id=".$promoId;
        Logger::Info("PromoEngine::getPromoById. ".$query);
        $rows=$this->db->get_rows($query);
        if (count($rows)>0)
        {
            try
            {
                $res = new Promo;
                $res->setPromoId($rows[0]["id"]);
                $res->setPromoCode($rows[0]["promo"]);

                $format = 'Y-m-d';
                $date = DateTime::createFromFormat($format, $rows[0]["expiration_date"]);
                $res->setExpirationdate($date);

                $res->setValue($rows[0]["value"]);

                Logger::Info("PromoEngine::getPromoById. ".$res);

            }
            catch (CantCreateException $e)
            {
                Logger::Info('Caught exception: ' .  $e->getMessage());
            }
        }
        else
        {
            Logger::Info("PromoEngine::getPromoById. Failed to get promo for ID: ".$promoId);
        }
        return $res;
    }

    public function isValidPromoCode($promoCode)
    {
        Logger::Info("PromoEngine::isValidPromoCode. Enter");
        $bRes = false;
        $promo = $this->getPromoByCode($promoCode);
        if(isset($promo))
        {
            Logger::Info("PromoEngine::isValidPromoCode. Got promo");
            $dateNow = new DateTime();
            if($promo->getExpirationdate() >= $dateNow)
            {
                Logger::Info("PromoEngine::isValidPromoCode. Date is OK");
                $bRes = true;
            }
            else
            {
                Logger::Info("PromoEngine::isValidPromoCode. Promo is expired");
            }
        }
        Logger::Info("PromoEngine::isValidPromoCode. Leave with result: ".$bRes);
        return $bRes;
    }

    public function resetAssigneeForPromoCode($promoCode)
    {
        $promo = $this->getPromoByCode($promoCode);
        if(isset($promo))
        {
            $this->db->delete(TABLE_USER_PROMO, array('promo_id'=>$promo->getPromoId()));
        }
    }

    public function assignPromoToUser($userId, $promoCode)
    {
        $promo = $this->getPromoByCode($promoCode);
        if(isset($promo))
        {
            $query = "INSERT INTO " . TABLE_USER_PROMO . " (user_id, promo_id, expired) VALUES (".$userId.", ".$promo->getPromoId().", false";
            $this->db->query($query);
        }
    }

    public function getPromoAssignedToUser($userId)
    {
        $pRes = null;
        $query = "select promo_id from ".TABLE_USER_PROMO." where user_id=".$userId." AND expired=false";
        Logger::Info("PromoEngine::getPromoAssignedToUser. ".$query);
        $rows=$this->db->get_rows($query);
        if (count($rows)>0)
        {
            $promoId = $rows[0]["promo_id"];
            $pRes = $this->getPromoById($promoId);
        }
        return $pRes;
    }

    public function isPromoAssignedToUser($userId, $promoCode)
    {
        $bRes = false;
        $promo = $this->getPromoByCode($promoCode);
        if(isset($promo))
        {
            $query = "SELECT * FROM " . TABLE_USER_PROMO . " WHERE user_id" . $userId . " AND promo_id=".$promo->getPromoId()." AND expired=false";
            $rows=$this->db->get_rows($query);
            if (count($rows)>0)
                $bRes=true;
        }
        return $bRes;
    }
}