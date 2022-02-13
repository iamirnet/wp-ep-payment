<?php

class Saman
{
    public function send($MerchantCode, $Amount, $callbackURL, $currency = "IRR", $InvoiceID = null, $Payload = null)
    {
        if ($currency == "IRT")
            $Amount = round($Amount*10);
        return array('status' => true, 'MID' => $MerchantCode, 'Amount' => $Amount, 'ResNum' => $InvoiceID, 'RedirectURL' => $callbackURL);
    }

    public function verify($MerchantCode, $RefNum, $State)
    {
        if (isset($State) && $State == "OK") {

            $soapclient = new soapclient('https://verify.sep.ir/Payments/ReferencePayment.asmx?WSDL');
            $res = $soapclient->VerifyTransaction($RefNum, $MerchantCode);

            if ($res <= 0) {
                // Transaction Failed
                return array("status" => false, "msg" => $res);
            } else {
                // Transaction Successful
                return array(
                    'status' => true,
                    "transId" => $RefNum,
                );
            }
        } else {
            // Transaction Failed
            return array("status" => false, "msg" => "Failed");

        }
    }
}
