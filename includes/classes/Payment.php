<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PureOSC;

use OndraKoupil\Csob\Config;
use OndraKoupil\Csob\Exception;
use OndraKoupil\Csob\GatewayUrl;

/**
 * Description of Payment
 *
 * @author vitex
 */
class Payment extends \OndraKoupil\Csob\Payment {

    /**
     * Api Helper 
     * @var \OndraKoupil\Csob\Client
     */
    private $api;

    /**
     * 
     * @var string
     */
    public $payId = null;

    /**
     * Current payment status
     * @var int
     */
    public $status = 0;

    /**
     * 
     * @var DateTime
     */
    private $updated;
    private $order;

    /**
     * Payment 
     * 
     * @param \order $order
     * @param boolean $oneClickPayment
     */
    public function __construct(\order $order, $oneClickPayment = false) {
        if (empty($order->id)) {
            throw new Exception('Order without number');
        }

        $this->order = $order;

        $order_query = tep_db_query("SELECT * FROM csob WHERE orders_id = " . (int) $order->id);
        $paymentData = $order_query->fetch_assoc();
        if (!empty($paymentData && array_key_exists('payId', $paymentData))) {
            $this->payId = $paymentData['payId'];
            $this->status = $paymentData['paymentStatus'];
            $this->updated = $paymentData['updated'];
        }

        $merchantInfo = [
            'orderId' => $order->get_id(),
            'customerId' => $order->customer['id'],
        ];

        if (array_key_exists('payment', $_SESSION)) {
            $merchantInfo['payment'] = array_key_exists('payment', $_SESSION) ? $_SESSION['payment'] : $this;
        }
        if (array_key_exists('sessiontoken', $_SESSION)) {
            $merchantInfo['sessiontoken'] = $_SESSION['sessiontoken'];
        }

        $merchantData = $order->get_id() ? json_encode($merchantInfo) : [];
        parent::__construct($order->get_id(), $merchantData, $order->customer['id'], $oneClickPayment);
    }

    public function orderToPayment() {
        $lang_query = tep_db_query("SELECT code FROM languages WHERE languages_id = " . (int) $_SESSION['languages_id']);
        $lang = tep_db_fetch_array($lang_query);

        if ($lang['code'] == 'cs') {
            $lang['code'] = 'cz';
        }

        $this->language = strtoupper($lang['code']);
        $order_total_modules = new \order_total();
        $this->order->totals = $order_total_modules->process();

        if (!empty($this->order->totals)) {
            if (isset($this->order->totals[0]['value'])) {
                $this->addCartItem($this->order->totals[0]['title'], 1, $this->order->totals[0]['value']);
            }
            if (isset($this->order->totals[1]['value'])) {
                $this->addCartItem($this->order->totals[1]['title'], 1, $this->order->totals[1]['value']);
            }
        }
    }

    /**
     * obtain Current Payment status
     * @return int status id
     */
    public function getStatus() {
        $start_date = new \DateTime($this->updated);
        $since_start = $start_date->diff(new \DateTime());
        if ($since_start->i) {
            $this->requestStatus(); // Load from Bank API
        } else {
            $this->loadPayment($this->payId); //Load from DB Cache
        }
        return $this->status;
    }

    /**
     * Load payment's info by payId
     * @param type $payId
     */
    public function loadPayment($payId) {
        $order_query = tep_db_query("SELECT * FROM csob WHERE payId = \"" . $payId . '"');
        $paymentData = $order_query->fetch_assoc();
        if (!empty($paymentData && array_key_exists('payId', $paymentData))) {
            $this->payId = $paymentData['payId'];
            $this->status = $paymentData['paymentStatus'];
            $this->updated = $paymentData['updated'];
        }
    }

    /**
     * store status in 
     * @param int $status
     */
    public function setStatus(int $status) {
        $this->status = $status;
    }

    /**
     * API Client
     * @return \OndraKoupil\Csob\Client
     */
    public function getApi() {
        if (is_null($this->api)) {
            $this->api = new \OndraKoupil\Csob\Client(self::apiconfig());
        }
        return $this->api;
    }

    /**
     * Establish new payment record
     */
    public function saveNewPayment() {
        if ($this->getPayId()) {
            tep_db_query("INSERT INTO csob (payId,paymentStatus, orders_id, updated) VALUES ('" . $this->payId . "', " . $this->getStatus() . ", " . $this->orderNo . ",NOW())");
        }
    }

    /**
     * Save payment's status into database
     */
    public function savePaymentState() {
        tep_db_query("UPDATE csob SET paymentStatus = " . $this->status . ", `updated`=NOW() WHERE payId = '" . $this->payId . "'");
        $this->updated = (new \DateTime())->format('Y-m-d H:i:s');
    }

    /**
     * Initiate card payment
     * 
     * @return array payment gw response
     */
    public function requestPayment() {
        $this->orderToPayment();
        $paymentData = $this->getApi()->paymentInit($this);
        if (array_key_exists('paymentStatus', $paymentData)) {
            $this->status = $paymentData['paymentStatus'];
            $this->payId = $paymentData['payId'];
            $this->saveNewPayment();
        }
        return $paymentData;
    }

    public function requestStatus() {
        $this->status = $this->getApi()->paymentStatus($this->payId);
        $this->foreignId = $this->payId;
        $this->savePaymentState();
        return $this->status;
    }

    /**
     * PayGW Configuration helper
     * 
     * @return Config
     */
    private static function apiconfig() {
        return new Config(
                \Ease\Functions::cfg('MODULE_PAYMENT_CSOB_MERCHANT_ID'),
                \Ease\Functions::cfg('MODULE_PAYMENT_CSOB_SECRET_KEY'),
                \Ease\Functions::cfg('MODULE_PAYMENT_CSOB_PUBLIC_KEY'),
                \Ease\Functions::cfg('STORE_NAME'),
                tep_href_link('ext/modules/payment/csob/welcomeback.php', 'ceid=' . session_id(), null, true),
                // URL adresa API - výchozí je adresa testovacího (integračního) prostředí,
                // až budete připraveni přepnout se na ostré rozhraní, sem zadáte
                // adresu ostrého API. Nezapomeňte také na ostrý veřejný klíč banky.
                \Ease\Functions::cfg('MODULE_PAYMENT_CSOB_PRODUCTION') == 'True' ? GatewayUrl::PRODUCTION_LATEST : GatewayUrl::TEST_LATEST
        );
    }

    /**
     * Obtain return code meaning
     * 
     * @param int $code
     * @return string
     * 
     * @throws Exception
     */
    static public function resultCodeMeaning($code) {
        switch ($code) {
            case 0:
                $error = 'OK'; //(operace proběhla korektně, transakce založena, stav aktualizován apod.)
                break;
            case 100:
                $error = 'Missing parameter {name}'; // (chybějící povinný parametr)
                break;
            case 110 :
                $error = 'Invalid parameter {name} '; //(chybný formát parametru)
                break;
            case 120:
                $error = 'Merchant blocked'; // (obchodník nemá povoleny platby)
                break;
            case 130:
                $error = 'Session expired'; //(vypršela platnost požadavku)
                break;
            case 140:
                $error = 'Payment not found'; // (platba nenalezena)
                break;
            case 150:
                $error = 'Payment not in valid state'; //(nesprávný stav platby, operaci nelze provést)
                break;
            case 160:
                $error = 'Payment method disabled'; //(operace není povolena, obchodník si o nastavení musí smluvně zažádat) 
                break;
            case 170:
                $error = 'Payment method unavailable'; //(nedostupost poskytovatele metody, služba není v tomto čase dosažitelná)
                break;
            case 180:
                $error = 'Operation not allowed'; //(nepovolená operace)
                break;
            case 190:
                $error = 'Payment method error'; //(chyba ve zpracování u poskytovatele metody)
                break;
            case 230:
                $error = 'Merchant not onboarded for MasterPass'; // (obchodník není registrovaný v MasterPass)
                break;
            case 240:
                $error = 'MasterPass request token already initialized'; // (MasterPass token byl již inicializován)
                break;
            case 250:
                $error = 'MasterPass request token does not exist'; // (nenalezen MasterPass token, nelze dokončit platbu pomocí MasterPass)
                break;
            case 270:
                $error = 'MasterPass canceled by user'; // (zákazník nedokončil výběr karty/adresy v MasterPass wallet)
                break;
            case 500:
                $error = 'EET Rejected'; //(EET hlášení bylo odmítnuto FS)
                break;
            case 600:
                $error = 'MALL Pay payment declined in precheck'; //operaci mallpay/init nelze dokončit z důvodu zamítnutí žádosti v systému MALL Pay
                break;
            case 700:
                $error = 'Oneclick template not found'; //(šablona pro platbu na klik nebyla nalezena)
                break;
            case 710:
                $error = 'Oneclick template payment expired'; //(šablona pro platbu nebyla použita více jak 12 měsíců, platba expirovala)
                break;
            case 720:
                $error = 'Oneclick template card expired'; //(karta pro šablonu pro platbu na klik expirovala)
                break;
            case 730:
                $error = 'Oneclick template customer rejected'; // (šablona pro platbu na klik byla zrušena na pokyn zákazníka)
                break;
            case 740:
                $error = 'Oneclick template payment reversed'; //(šablona pro platbu na klik byla reverzována)
                break;
            case 800:
                $error = 'Customer not found'; //(zákazník identifikovaný pomocí customerId nenalezen)
                break;
            case 810:
                $error = 'Customer found, no saved card(s)'; //(zákazník identifikovaný pomocí customerId byl nalezen, ale nemá žádné dříve uložené karty na platební bráně)
                break;
            case 820:
                $error = 'Customer found, found saved card(s)'; //(zákazník identifikovaný pomocí customerId byl nalezen a má uložené karty na platební bráně)
                break;
            case 900:
                $error = 'Internal error'; // (interní chyba ve zpracování požadavku)
                break;
            default:
                throw new Exception('Unknown Payment response code: ' . $code);
                break;
        }
        return $error;
    }

    /**
     * Meaning of given payment status code
     * 
     * @param int $status 
     * 
     * @return string
     */
    public static function paymentStatusMeaning(int $status) {
        switch ($status) {
            case 0:
                $meaning = 'Payment does not exist yet';
                break;
            case 1:
                $meaning = 'Payment established';
                break;
            case 2:
                $meaning = 'Payment in progress';
                break;
            case 3:
                $meaning = 'Payment cancelled';
                break;
            case 4:
                $meaning = 'Payment confirmed';
                break;
            case 5:
                $meaning = 'Payment canceled';
                break;
            case 6:
                $meaning = 'Payment denied';
                break;
            case 7:
                $meaning = 'Wait for Accounting';
                break;
            case 8:
                $meaning = 'Payment accounted';
                break;
            case 9:
                $meaning = 'Refund requested';
                break;
            case 10:
                $meaning = 'Payment refunded';
                break;
        }
        return $meaning;
    }

}
