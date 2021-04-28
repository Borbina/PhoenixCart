<?php

use Ease\Functions;
use Ease\WebPage;

/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
 */

/**
 * Description of csob
 *
 * @author vitex
 */
class csob extends abstract_payment_module {

    const CONFIG_KEY_BASE = 'MODULE_PAYMENT_CSOB_';
    const CSOB_LOGO = '<svg logo="default" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.756 192.756" height="2500" width="2500"><path d="M97.104 88.016c14.988 0 27.139-12.162 27.139-27.16 0-14.999-12.15-27.16-27.139-27.16-14.991 0-27.143 12.162-27.143 27.16s12.152 27.16 27.143 27.16z" fill="#00acec" clip-rule="evenodd" fill-rule="evenodd"/><path d="M117.121 82.832c-5.172 5.608-14.291 11.219-25.99 11.219-9.078 0-16.894-3.529-21.864-7.454-29.633 3.11-51.669 7.522-51.669 7.522v12.005l157.041-.016.018-24.756c-.001.001-26.798-.167-57.536 1.48z" fill="#00acec" clip-rule="evenodd" fill-rule="evenodd"/><path d="M50.375 157.443c-2.796.807-6.936 1.617-10.739 1.617-12.976 0-23.379-7.041-23.379-21.408 0-13.504 10.851-20.258 23.379-20.258 4.139 0 6.6.52 10.627 1.271v10.617c-2.461-.809-4.979-1.502-7.494-1.502-6.377 0-11.187 3.521-11.187 10.102 0 6.922 4.472 10.441 10.683 10.441 2.74 0 5.37-.576 8.11-1.559zm35.459-28.336a34.398 34.398 0 00-11.579-2.018c-2.348 0-6.432.061-6.432 2.654 0 5.254 20.528.289 20.528 15.811 0 10.793-10.35 13.506-19.019 13.506-5.762 0-10.515-.58-16.108-1.906v-10.443c4.027 1.729 8.95 2.652 13.424 2.652 3.523 0 6.711-.748 6.711-2.768 0-5.424-20.526-.52-20.526-16.16 0-11.078 11.186-13.041 20.078-13.041 4.196 0 8.837.52 12.922 1.387v10.326zm26.453 29.954c-13.648 0-21.646-7.1-21.646-20.832 0-13.504 7.997-20.834 21.646-20.834 13.646 0 21.811 7.33 21.811 20.834 0 13.732-8.164 20.832-21.811 20.832zm0-9.698c5.928 0 6.096-6.811 6.148-11.135-.053-3.752-.443-11.139-6.148-11.139-5.537 0-5.984 7.387-5.984 11.139 0 4.325.613 11.135 5.984 11.135zm25.508-31.275h24.551c6.77 0 13.426 2.252 13.426 9.926 0 5.541-2.854 8.48-8.055 9.869v.115c5.428.748 8.781 5.135 8.781 9.174 0 10.156-7.941 11.193-16.053 11.193h-22.65zm14.096 31.971h3.914c2.74 0 5.705-1.039 5.705-4.158 0-3.463-2.797-4.096-5.592-4.096h-4.027zm0-16.565h3.744c2.631 0 5.482-.807 5.482-3.865 0-3-2.461-3.922-5.146-3.922h-4.08z" fill="#003a66" clip-rule="evenodd" fill-rule="evenodd"/><path d="M24.027 114.469s4.71 4.049 11.83 4.078c6.729.023 11.717-3.32 14.33-6.133l-1.139-.869s-3.413 3.768-9.898 3.701c-6.877-.07-10.789-4.756-10.789-4.756z" fill="#003a66" clip-rule="evenodd" fill-rule="evenodd"/></svg>';
    const CSOB_ERROR = '<svg logo="error"  xmlns="http://www.w3.org/2000/svg" width="2500" height="2500" viewBox="0 0 192.756 192.756"><defs><filter color-interpolation-filters="sRGB" id="b"><feColorMatrix type="hueRotate" values="296" result="color1"/><feColorMatrix type="saturate" values="1" result="fbSourceGraphic"/><feColorMatrix result="fbSourceGraphicAlpha" in="fbSourceGraphic" values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/><feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.21 0.72 0.07 0 0" result="result1" in="fbSourceGraphic"/><feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 2 0" result="result9"/><feComposite in2="result9" in="fbSourceGraphic" operator="in" result="result4"/><feFlood result="result2" flood-color="#000"/><feComposite in2="result9" operator="in" result="result10"/><feComposite in2="result4" operator="atop"/><feGaussianBlur stdDeviation="3" result="result8"/><feOffset dx="3" dy="3" result="result3" in="result8"/><feFlood flood-opacity="1" flood-color="#DBAD3E" result="result5"/><feMerge result="result6"><feMergeNode in="result5"/><feMergeNode in="result3"/><feMergeNode in="result4"/></feMerge><feComposite in2="fbSourceGraphic" operator="in" result="fbSourceGraphic"/><feColorMatrix result="fbSourceGraphicAlpha" in="fbSourceGraphic" values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/><feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.21 0.72 0.07 0 0" result="result1" in="fbSourceGraphic"/><feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 2 0" result="result9"/><feComposite in2="result9" in="fbSourceGraphic" operator="in" result="result4"/><feFlood result="result2" flood-color="#000"/><feComposite in2="result9" operator="in" result="result10"/><feComposite in2="result4" operator="atop"/><feGaussianBlur stdDeviation="3" result="result8"/><feOffset dx="3" dy="3" result="result3" in="result8"/><feFlood flood-opacity="1" flood-color="#DBAD3E" result="result5"/><feMerge result="result6"><feMergeNode in="result5"/><feMergeNode in="result3"/><feMergeNode in="result4"/></feMerge><feComposite in2="fbSourceGraphic" operator="in" result="result7"/></filter><filter color-interpolation-filters="sRGB" id="a"><feGaussianBlur result="result0" in="SourceAlpha" stdDeviation="4"/><feOffset dx="5" dy="5" result="result4"/><feComposite in="SourceGraphic" in2="result4" operator="xor" result="result3"/></filter><filter color-interpolation-filters="sRGB" id="c"><feFlood flood-opacity=".498" flood-color="#000" result="flood"/><feComposite in="flood" in2="SourceGraphic" operator="in" result="composite1"/><feGaussianBlur in="composite1" stdDeviation="3" result="blur"/><feOffset dx="6" dy="6" result="offset"/><feComposite in="SourceGraphic" in2="offset" result="composite2"/></filter></defs><g fill-rule="evenodd" clip-rule="evenodd" filter="url(#a)" fill="#a00"><path filter="url(#b)" d="M97.104 88.016c14.988 0 27.139-12.162 27.139-27.16 0-14.999-12.15-27.16-27.139-27.16-14.991 0-27.143 12.162-27.143 27.16s12.152 27.16 27.143 27.16z"/><path filter="url(#b)" d="M117.121 82.832c-5.172 5.608-14.291 11.219-25.99 11.219-9.078 0-16.894-3.529-21.864-7.454-29.633 3.11-51.669 7.522-51.669 7.522v12.005l157.041-.016.018-24.756c-.001.001-26.798-.167-57.536 1.48z"/></g><path filter="url(#b)" fill-rule="evenodd" clip-rule="evenodd" fill="#003a66" d="M50.375 157.443c-2.796.807-6.936 1.617-10.739 1.617-12.976 0-23.379-7.041-23.379-21.408 0-13.504 10.851-20.258 23.379-20.258 4.139 0 6.6.52 10.627 1.271v10.617c-2.461-.809-4.979-1.502-7.494-1.502-6.377 0-11.187 3.521-11.187 10.102 0 6.922 4.472 10.441 10.683 10.441 2.74 0 5.37-.576 8.11-1.559zm35.459-28.336a34.398 34.398 0 00-11.579-2.018c-2.348 0-6.432.061-6.432 2.654 0 5.254 20.528.289 20.528 15.811 0 10.793-10.35 13.506-19.019 13.506-5.762 0-10.515-.58-16.108-1.906v-10.443c4.027 1.729 8.95 2.652 13.424 2.652 3.523 0 6.711-.748 6.711-2.768 0-5.424-20.526-.52-20.526-16.16 0-11.078 11.186-13.041 20.078-13.041 4.196 0 8.837.52 12.922 1.387v10.326zm26.453 29.954c-13.648 0-21.646-7.1-21.646-20.832 0-13.504 7.997-20.834 21.646-20.834 13.646 0 21.811 7.33 21.811 20.834 0 13.732-8.164 20.832-21.811 20.832zm0-9.698c5.928 0 6.096-6.811 6.148-11.135-.053-3.752-.443-11.139-6.148-11.139-5.537 0-5.984 7.387-5.984 11.139 0 4.325.613 11.135 5.984 11.135zm25.508-31.275h24.551c6.77 0 13.426 2.252 13.426 9.926 0 5.541-2.854 8.48-8.055 9.869v.115c5.428.748 8.781 5.135 8.781 9.174 0 10.156-7.941 11.193-16.053 11.193h-22.65zm14.096 31.971h3.914c2.74 0 5.705-1.039 5.705-4.158 0-3.463-2.797-4.096-5.592-4.096h-4.027zm0-16.565h3.744c2.631 0 5.482-.807 5.482-3.865 0-3-2.461-3.922-5.146-3.922h-4.08z"/><path filter="url(#b)" fill-rule="evenodd" clip-rule="evenodd" fill="#003a66" d="M24.027 114.469s4.71 4.049 11.83 4.078c6.729.023 11.717-3.32 14.33-6.133l-1.139-.869s-3.413 3.768-9.898 3.701c-6.877-.07-10.789-4.756-10.789-4.756z"/><text filter="url(#c)" stroke-width="2.754" fill="red" word-spacing="0" letter-spacing="0" font-family="sans-serif" font-size="110.172" font-weight="400" style="line-height:1.25" x="73.755" y="140.864" transform="matrix(2.32742 0 0 1.33682 -125.308 -42.718)" stroke="#ffb400"><tspan x="73.755" y="140.864">!</tspan></text></svg>';
    const CSOB_OK = '<svg logo="ok"  xmlns="http://www.w3.org/2000/svg" width="2500" height="2500" viewBox="0 0 192.756 192.756"><defs><filter color-interpolation-filters="sRGB" id="a"><feMorphology result="result1" in="SourceAlpha" operator="dilate" radius="3.6"/><feGaussianBlur stdDeviation="3.6" in="result1" result="result0"/><feDiffuseLighting surfaceScale="-5"><feDistantLight elevation="45" azimuth="225"/></feDiffuseLighting><feComposite in2="result0" operator="in" result="result91"/><feComposite in="SourceGraphic" in2="result91"/></filter><filter color-interpolation-filters="sRGB" id="b"><feMorphology result="result1" in="SourceAlpha" operator="dilate" radius="3.6"/><feGaussianBlur stdDeviation="3.6" in="result1" result="result0"/><feDiffuseLighting surfaceScale="-5"><feDistantLight elevation="45" azimuth="225"/></feDiffuseLighting><feComposite in2="result0" operator="in" result="result91"/><feComposite in="SourceGraphic" in2="result91"/></filter></defs><g fill-rule="evenodd" clip-rule="evenodd" filter="url(#a)" fill="#0f0"><path d="M97.104 88.016c14.988 0 27.139-12.162 27.139-27.16 0-14.999-12.15-27.16-27.139-27.16-14.991 0-27.143 12.162-27.143 27.16s12.152 27.16 27.143 27.16z"/><path d="M117.121 82.832c-5.172 5.608-14.291 11.219-25.99 11.219-9.078 0-16.894-3.529-21.864-7.454-29.633 3.11-51.669 7.522-51.669 7.522v12.005l157.041-.016.018-24.756c-.001.001-26.798-.167-57.536 1.48z"/></g><path filter="url(#b)" fill-rule="evenodd" clip-rule="evenodd" fill="#003a66" d="M50.375 157.443c-2.796.807-6.936 1.617-10.739 1.617-12.976 0-23.379-7.041-23.379-21.408 0-13.504 10.851-20.258 23.379-20.258 4.139 0 6.6.52 10.627 1.271v10.617c-2.461-.809-4.979-1.502-7.494-1.502-6.377 0-11.187 3.521-11.187 10.102 0 6.922 4.472 10.441 10.683 10.441 2.74 0 5.37-.576 8.11-1.559zm35.459-28.336a34.398 34.398 0 00-11.579-2.018c-2.348 0-6.432.061-6.432 2.654 0 5.254 20.528.289 20.528 15.811 0 10.793-10.35 13.506-19.019 13.506-5.762 0-10.515-.58-16.108-1.906v-10.443c4.027 1.729 8.95 2.652 13.424 2.652 3.523 0 6.711-.748 6.711-2.768 0-5.424-20.526-.52-20.526-16.16 0-11.078 11.186-13.041 20.078-13.041 4.196 0 8.837.52 12.922 1.387v10.326zm26.453 29.954c-13.648 0-21.646-7.1-21.646-20.832 0-13.504 7.997-20.834 21.646-20.834 13.646 0 21.811 7.33 21.811 20.834 0 13.732-8.164 20.832-21.811 20.832zm0-9.698c5.928 0 6.096-6.811 6.148-11.135-.053-3.752-.443-11.139-6.148-11.139-5.537 0-5.984 7.387-5.984 11.139 0 4.325.613 11.135 5.984 11.135zm25.508-31.275h24.551c6.77 0 13.426 2.252 13.426 9.926 0 5.541-2.854 8.48-8.055 9.869v.115c5.428.748 8.781 5.135 8.781 9.174 0 10.156-7.941 11.193-16.053 11.193h-22.65zm14.096 31.971h3.914c2.74 0 5.705-1.039 5.705-4.158 0-3.463-2.797-4.096-5.592-4.096h-4.027zm0-16.565h3.744c2.631 0 5.482-.807 5.482-3.865 0-3-2.461-3.922-5.146-3.922h-4.08z"/><path fill-rule="evenodd" clip-rule="evenodd" fill="#003a66" d="M24.027 114.469s4.71 4.049 11.83 4.078c6.729.023 11.717-3.32 14.33-6.133l-1.139-.869s-3.413 3.768-9.898 3.701c-6.877-.07-10.789-4.756-10.789-4.756z"/><text stroke-width="3.005" stroke="#e6ff00" fill="#4a0" word-spacing="0" letter-spacing="0" font-family="sans-serif" font-size="120.196" font-weight="400" style="line-height:1.25" x="53.386" y="144.098"><tspan x="53.386" y="144.098">✔</tspan></text></svg>';

    /**
     * Last GW Response
     * @var string
     */
    private $lastMessage = '';

    /**
     * Image body
     * @var string
     */
    public $logo = '';

    /**
     * Payment's nest
     * @var \PureOSC\Payment
     */
    public $payment = null;

    /**
     * Order's place 
     * @var PureOSC\Order
     */
    public $order = null;

    /**
     * CSOB PayGW
     */
    public function __construct() {
        parent::__construct();

        //$this->signature = 'purehtml|csob|2.3';
        $this->api_version = '1.8';

        $this->public_title = MODULE_PAYMENT_CSOB_TEXT_TITLE;
        $this->sort_order = $this->sort_order ?? 0;
//        $this->order_status = defined('MODULE_PAYMENT_CSOB_PREPARE_ORDER_STATUS_ID') && ((int) MODULE_PAYMENT_CSOB_PREPARE_ORDER_STATUS_ID > 0) ? (int) MODULE_PAYMENT_CSOB_PREPARE_ORDER_STATUS_ID : 0;

        $this->sort_order = defined('MODULE_PAYMENT_CSOB_SORT_ORDER') ? MODULE_PAYMENT_CSOB_SORT_ORDER : 0;

        $this->order_status = defined('MODULE_PAYMENT_CSOB_PREPARE_ORDER_STATUS_ID') && ((int) MODULE_PAYMENT_CSOB_PREPARE_ORDER_STATUS_ID > 0) ? (int) MODULE_PAYMENT_CSOB_PREPARE_ORDER_STATUS_ID : 0;

        $exts = array_filter(['xmlwriter', 'SimpleXML', 'openssl', 'dom', 'hash', 'curl'], function ($extension) {
            return !extension_loaded($extension);
        });

        $csob_error = null;
        if (!empty($exts)) {
            $csob_error = sprintf(self::get_constant('MODULE_PAYMENT_CSOB_ERROR_ADMIN_PHP_EXTENSIONS'), implode('<br>', $exts));
        }

        if (!isset($csob_error) && defined('MODULE_PAYMENT_CSOB_STATUS')) {
            if (!tep_not_null(MODULE_PAYMENT_CSOB_MERCHANT_ID) || !tep_not_null(MODULE_PAYMENT_CSOB_PUBLIC_KEY) || !tep_not_null(MODULE_PAYMENT_CSOB_SECRET_KEY) || !tep_not_null(MODULE_PAYMENT_CSOB_PUBLIC_KEY)) {
                $csob_error = MODULE_PAYMENT_CSOB_ERROR_ADMIN_CONFIGURATION;
            }
        }

        if (!isset($csob_error) && defined('MODULE_PAYMENT_CSOB_STATUS')) {
            
        }

        if (isset($csob_error)) {
            $this->description = '<div class="alert alert-warning">' . $csob_error . '</div>' . $this->description;

            $this->enabled = false;
        }

        if (array_key_exists('admin', $_SESSION)) {
            if (defined('MODULE_PAYMENT_CSOB_MERCHANT_ID')) {
//TODO FIX SOMEHOW           $this->description .= '<br>' . $this->checkPayGwSatus();            
            }
            $this->description .= '<br>' . str_replace('<svg ', '<svg class="img-fluid" ', $this->logo);
        }
    }

    /**
     * Perform initial payment request to be redirected int payment page
     */
    private function prepare_payment() {
        //tep_redirect(tep_href_link('checkout_payment.php', 'payment_error=' . 'csob'));
        $this->order->save();
        $response = $this->getPayment()->requestPayment($this->order);
        $this->lastMessage = $response['resultMessage'];
        $_SESSION['csob_error'] = $response['resultMessage'];
        if ($response['resultCode'] != 0) {
            tep_redirect(tep_href_link('checkout_payment.php', 'payment_error=' . 'csob' . '&error=' . $response['resultCode']));
        }

        tep_redirect(htmlspecialchars($this->payment->getApi()->getPaymentProcessUrl($this->payment)));
    }

    /**
     * Update payment status
     *  
     * @return null
     */
    public function update_status() {
        if (!$this->enabled || !isset($GLOBALS['order'])) {
            return;
        }

        // disable the module if the order only contains virtual products
        if ('virtual' === $GLOBALS['order']->content_type) {
            $this->enabled = false;
            return;
        }

        if (isset($GLOBALS['order']->delivery['country']['id'])) {
            $this->update_status_by($GLOBALS['order']->delivery);
        }

        if (array_key_exists('payId', $_REQUEST)) {
            $this->getPayment()->setPayId($_REQUEST['payId']);
            $this->getPayment()->setStatus(intval($_REQUEST['paymentStatus']));
            $this->getPayment()->savePaymentState();
        }

        $this->setOrderStatusByPaymentState();
    }

    /**
     * 
     * @return \PureOSC\Payment
     */
    public function getPayment() {
        if (is_null($this->order)) {
            if (array_key_exists('order_id', $GLOBALS) === false) { //We need OrderNumber first
                $this->getOrder()->save();
            }
        }

        if (is_object($this->payment) === false) {
            $this->payment = new \PureOSC\Payment($this->getOrder()); //Guarantor::ensure_global('order') not work here
        }
        return $this->payment;
    }

    public function pre_confirmation_check() {
        PureOSC\Order::redirectForOrderStatus($this->getOrder()->getStatus());
    }

    public function confirmation() {
        return false;
    }

    public function process_button() {
        return false;
    }

    public function before_process() {
        global $order;
        $order->info['order_status'] = MODULE_PAYMENT_CSOB_ORDER_STATUS_ID;

        switch (WebPage::getRequestValue('resultCode')) {
            case '0':
            default:
                $order->info['order_status'] = MODULE_PAYMENT_CSOB_PROBLEM_ORDER_STATUS_ID;
                break;
        }

        tep_db_query("UPDATE orders SET orders_status = " . (int) $order->info['order_status'] . ", last_modified = NOW() WHERE orders_id = " . (int) $order->get_id());

        return true;
    }

    public function after_process() {
        $this->prepare_payment();
        return $this->success;
    }

    public function get_error() {
        global $order;
        $message = MODULE_PAYMENT_CSOB_ERROR_GENERAL;

        if (isset($_SESSION['csob_error'])) {
            $message = $_SESSION['csob_error'] . ' ' . $message;
            unset($_SESSION['csob_error']);
        } else {
            if (!empty($_GET['error'])) {
                $message .= "\n" . self::resultCodeMeaning($_GET['error']) . "\n";
            }
        }


        $nextOrderid = current(tep_db_fetch_array(tep_db_query('SELECT MAX(orders_id) FROM orders'))) + 1;

        tep_db_query("UPDATE orders SET orders_id = " . (int) $nextOrderid . ", last_modified = NOW() WHERE orders_id = " . (int) $order->get_id());
        $order->info['orders_id'] = $nextOrderid;

        $error = [
            'title' => MODULE_PAYMENT_CSOB_ERROR_TITLE,
            'error' => $message,
        ];

        return $error;
    }

    public function getOrder($orderId = null) {
        if (is_null($this->order)) {
            $this->order = Guarantor::ensure_global('PureOSC\Order', array_key_exists('order_id', $GLOBALS) ? $GLOBALS['order_id'] : $orderId);
        }
        return $this->order;
    }

    /**
     * Check payment gate api availbility
     * 
     * @return string
     */
    public function checkPayGwSatus() {
        try {
            $this->getPayment()->getApi()->testGetConnection();
            $this->getPayment()->getApi()->testPostConnection();
            $this->logo = self::CSOB_OK;
            $status = 'PayGW Online';
        } catch (Exception $e) {
            $status = new Ease\TWB4\Label('warning', 'CSOB PayGW problem ' . $e->getMessage());
            $this->logo = self::CSOB_ERROR;
        }
        return $status;
    }

    public function setOrderStatusByPaymentState($forceStatus = null) {
        if (array_key_exists('order_id', $GLOBALS)) {

            $paymentStatus = is_null($forceStatus) ? $this->getPayment()->getStatus() : $forceStatus;
        } else {
            $paymentStatus = null;
        }
        $newOrderStatus = 0;
        $currentOrderStatus = $this->getOrder()->getStatus();

        switch ($paymentStatus) {
            case 3: // Payment cancelled
                $newOrderStatus = intval(MODULE_PAYMENT_CSOB_CANCELED_ORDER_STATUS_ID);
                break;
            case 4: // wait for process
            case 7: // Wait for Accounting
                $newOrderStatus = intval(MODULE_PAYMENT_CSOB_PROCESSING_ORDER_STATUS_ID);
                $_SESSION['cart'] = new shoppingCart();
                break;
            case 8: // Payment Accounted
                $newOrderStatus = intval(MODULE_PAYMENT_CSOB_DONE_ORDER_STATUS_ID);
                $_SESSION['cart'] = new shoppingCart();
                break;
            case 5:
            case 6:
                $newOrderStatus = intval(MODULE_PAYMENT_CSOB_PROBLEM_ORDER_STATUS_ID);
                break;
        }

        if ($newOrderStatus && ($newOrderStatus != $currentOrderStatus)) {
            $this->getOrder()->setOrderState($newOrderStatus);
            if ($paymentStatus) {
                $this->getOrder()->addStatusHistory($newOrderStatus, \PureOSC\Payment::paymentStatusMeaning($paymentStatus) . true);
            }
        }
    }

    /**
     * Payment method configuration keys definitoin
     * 
     * @return array
     */
    protected function get_parameters() {
        return [
            'MODULE_PAYMENT_CSOB_STATUS' => [
                'title' => 'Enable CSOB Payment gateway Module',
                'value' => 'True',
                'desc' => 'Do you want to accept CSOB Payment gateway payments?',
                'set_func' => "tep_cfg_select_option(['True', 'False'], ",
            ],
            'MODULE_PAYMENT_CSOB_ZONE' => [
                'title' => 'Payment Zone',
                'value' => '0',
                'desc' => 'If a zone is selected, only enable this payment method for that zone.',
                'use_func' => 'tep_get_zone_class_title',
                'set_func' => 'tep_cfg_pull_down_zone_classes(',
            ],
            'MODULE_PAYMENT_CSOB_SORT_ORDER' => [
                'title' => 'Sort order of display.',
                'value' => '0',
                'desc' => 'Sort order of display. Lowest is displayed first.',
            ],
            'MODULE_PAYMENT_CSOB_ORDER_STATUS_ID' => [
                'title' => 'Set Order Status',
                'value' => self::ensure_order_status('MODULE_PAYMENT_CSOB_PROCESSING_ORDER_STATUS_ID', 'Card payment pending'),
                'desc' => 'Set the status of orders made with this payment module to this value',
                'set_func' => 'tep_cfg_pull_down_order_statuses(',
                'use_func' => 'tep_get_order_status_name',
            ],
            'MODULE_PAYMENT_CSOB_MERCHANT_ID' => [
                'title' => _('Your merchant id'),
                'value' => '00000000',
                'desc' => _('Your merchant unique identifier (supplied by csob)')
            ],
            'MODULE_PAYMENT_CSOB_SECRET_KEY' => [
                'title' => _('Your secret key'),
                'desc' => _('Your secret key (supplied by csob on https://platebnibrana.csob.cz/keygen/)')
            ],
            'MODULE_PAYMENT_CSOB_PUBLIC_KEY' => [
                'title' => _('Your public key'),
                'value' => 'mips_iplatebnibrana.csob.cz.pub',
                'desc' => _('Your public key (supplied by csob)')
            ],
            'MODULE_PAYMENT_CSOB_PROCESSING_ORDER_STATUS_ID' => [
                'title' => 'Wait for payment Order Status',
                'desc' => 'Include transaction information in this order status level',
                'value' => self::ensure_order_status('MODULE_PAYMENT_CSOB_PROCESSING_ORDER_STATUS_ID', 'Card payment pending'),
                'set_func' => 'tep_cfg_pull_down_order_statuses(',
                'use_func' => 'tep_get_order_status_name',
                'public' => true
            ],
            'MODULE_PAYMENT_CSOB_DONE_ORDER_STATUS_ID' => [
                'title' => 'All OK settled payment Order Status',
                'desc' => 'Include transaction information in this order status level',
                'value' => self::ensure_order_status('MODULE_PAYMENT_CSOB_DONE_ORDER_STATUS_ID', 'Settled by Card'),
                'set_func' => 'tep_cfg_pull_down_order_statuses(',
                'use_func' => 'tep_get_order_status_name',
                'public' => true
            ],
            'MODULE_PAYMENT_CSOB_CANCELED_ORDER_STATUS_ID' => [
                'title' => 'Canceled payment Order Status',
                'desc' => 'State for orders with cancelled paymen',
                'value' => self::ensure_order_status('MODULE_PAYMENT_CSOB_CANCELED_ORDER_STATUS_ID', 'Payment canceled'),
                'set_func' => 'tep_cfg_pull_down_order_statuses(',
                'use_func' => 'tep_get_order_status_name',
                'public' => true
            ],
            'MODULE_PAYMENT_CSOB_PROCESSING_ORDER_STATUS_ID' => [
                'title' => 'Payment in progress Order Status',
                'desc' => 'State for orders with payment in progress',
                'value' => self::ensure_order_status('MODULE_PAYMENT_CSOB_PROCESSING_ORDER_STATUS_ID', 'Payment processing'),
                'set_func' => 'tep_cfg_pull_down_order_statuses(',
                'use_func' => 'tep_get_order_status_name',
                'public' => true
            ],
            'MODULE_PAYMENT_CSOB_PROBLEM_ORDER_STATUS_ID' => [
                'title' => 'Card payment problem Order Status',
                'desc' => 'Include transaction information in this order status level',
                'value' => self::ensure_order_status('MODULE_PAYMENT_CSOB_PROBLEM_ORDER_STATUS_ID', 'Card payment problem'),
                'set_func' => 'tep_cfg_pull_down_order_statuses(',
                'use_func' => 'tep_get_order_status_name',
                'public' => true
            ],
            'MODULE_PAYMENT_CSOB_PRODUCTION' => [
                'title' => 'Use production API',
                'value' => 'False',
                'desc' => 'Put false here to use testing API servers.',
                'set_func' => "tep_cfg_select_option(['True', 'False'], "
            ]
        ];
    }

}
