<?php

class csob_hook_admin_orders_action {

    public function execute() {


        $oID = \Ease\WebPage::getRequestValue('oID');
        $payApi = new \PureOSC\Payment(new \PureOSC\Order($oID));

        switch (\Ease\WebPage::getRequestValue('tabaction')) {
            case 'doCapture':

                $payload = [ 'merchantId' => '',"payId" => $payApi->payId, 'dttm' => ''];

                print_r($payload);

                $closed = $payApi->getApi()->customRequest('payment/close', $payload, [], [], 'PUT');

                print_r($closed);
                break;
            case 'getTransactionDetails':
                echo new \Ease\TWB4\Alert('info', \PureOSC\Payment::paymentStatusMeaning($payApi->requestStatus()));
                break;

            case 'refundTransaction':
                try {
                    $refund = $payApi->paymentRefund($payApi->payId);
                    echo new \Ease\TWB4\Alert('success', 'Refunded');
                } catch (\OndraKoupil\Csob\Exception $exc) {
                    echo new \Ease\TWB4\Alert('warning', $exc->getMessage());

//                    echo $exc->getTraceAsString();
                }
                break;
            default:
                break;
        }
    }

}
