<?php

/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
 */

class hook_shop_siteWide_csob {

    public $version = '0.0.1';

    /**
     * Order handle
     * @var \PureOSC\Order
     */
    public $order = null;

    /**
     * 
     * @var array
     */
    private $paymentData;

    public function listen_autosync() {
        echo '';
    }

    /**
     * 
     * @param PureOSC\Order $order
     */
    public function listen_constructOrder(\order $order) {
        $orderId = $order->get_id();
        if ($orderId) {
//            $payment = new PureOSC\Payment($order);
//            $status = $payment->requestStatus();
        }
    }

    public function listen_databaseOrderBuild($param) {
        //Order was loaded from data base, so we can check card payment status
        $this->order = $param['order'];
//          if($this->order->getPaymentState() == 'unknown'){
//        $this->order->getPaymentStatus();
//          }
    }

}
