<?php
class ZarinPal {
    public $base_url = "https://www.zarinpal.com/pg/services/WebGate/wsdl";
    function send($MerchantID, $Amount, $Description, $CallbackURL, $currency = "IRT", $Email=null, $Mobile=null) {
        if ($currency == "IRR")
            $Amount = round($Amount/10);
        $client = new SoapClient($this->base_url, ['encoding' => 'UTF-8']);
        $result = $client->PaymentRequest(
            [
                'MerchantID' => $MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'Mobile' => $Mobile,
                'CallbackURL' => $CallbackURL,
            ]
        );
        if ($result->Status == 100) {
            return array('status' => true, 'url' => 'https://www.zarinpal.com/pg/StartPay/' . $result->Authority);
        } else {
            return array("status" => false, "msg" => $result->Status);
        }
    }

    function verify($MerchantID, $Authority, $Amount,$currency = "IRT") {
        if ($currency == "IRR")
            $Amount = round($Amount/10);
        $client = new SoapClient($this->base_url, ['encoding' => 'UTF-8']);
        $result = $client->PaymentVerification(
            [
                'MerchantID' => $MerchantID,
                'Authority' => $Authority,
                'Amount' => $Amount,
            ]
        );
        if ($result->Status == 100) {
            return array(
                'status' => true,
                "transId" => $result->RefID,
            );
        } else {
            return array("status" => false, "msg" => $result->Status);
        }
    }
}
