<?php

/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
 */

chdir('../../../../');
require 'includes/application_top.php';

$cartPayId = \Ease\WebPage::getRequestValue('payId');
$cardDttm = \Ease\WebPage::getRequestValue('dttm');

$merchantData = json_decode(base64_decode(\Ease\WebPage::getRequestValue('merchantData')), 'true');
$paymentStatus = \Ease\WebPage::getRequestValue('paymentStatus', 'int');
$resultCode = \Ease\WebPage::getRequestValue('resultCode', 'int');

$GLOBALS['order_id'] = $merchantData['orderId'];
$GLOBALS['order'] = Guarantor::ensure_global('PureOSC\Order', array_key_exists('order_id', $GLOBALS) ? $GLOBALS['order_id'] : null);

$payment_modules = new \payment($merchantData['payment']);
$payment_modules->update_status();

if (( is_array($payment_modules->modules) && (count($payment_modules->modules) > 1) && !is_object(${$_SESSION['payment']}) ) || (is_object(${$_SESSION['payment']}) && (${$_SESSION['payment']}->enabled == false))) {
    tep_redirect(tep_href_link('checkout_payment.php', 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
}

if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
}
