<?php
class Mellat {
    public function send($data, $Amount, $InvoiceID=null, $callbackURL, $currency = "IRR",$Payload=null) {
        if ($currency == "IRT")
            $Amount = round($Amount*10);
        $InvoiceID = date("His") . '10000'. $InvoiceID;
        $localDate = date('Ymd');
        $localTime= date('Gis');
        $parameters = array(
            'terminalId' 		=> $data->terminalId,
            'userName' 			=> $data->userName,
            'userPassword' 		=> $data->userPassword,
            'orderId' 			=> $InvoiceID,
            'amount' 			=> $Amount,
            'localDate' 		=> $localDate,
            'localTime' 		=> $localTime,
            'additionalData' 	=> '',
            'callBackUrl' 		=> $callbackURL,
            'payerId' 			=> 0);
        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $namespace = 'http://interfaces.core.sw.bps.com/';
        $result = $client->call('bpPayRequest', $parameters, $namespace);
        $resultStr  = $result;
        $res = explode (',',$resultStr);
        $ResCode = $res[0];
        $res[1];
        if ($ResCode == "0") {
            return array('status' => true, "url" => "https://bpm.shaparak.ir/pgwchannel/startpay.mellat","RefId" => $res[1]);
        } else   {
            return array('status' => false, "msg" => 'امکان اتصال وجود ندارد ، لطفاً دوباره تلاش کنید.');
        }
    }

    public function verify($data, $orderId, $verifySaleOrderId,  $verifySaleReferenceId) {

        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $namespace='http://interfaces.core.sw.bps.com/';
        $parameters = array(
            'terminalId' 		=> $data->terminalId,
            'userName' 			=> $data->userName,
            'userPassword' 		=> $data->userPassword,
            'orderId' => $orderId,
            'saleOrderId' => $verifySaleOrderId,
            'saleReferenceId' => $verifySaleReferenceId);
        $err = $client->getError();
        if ($err) {
            echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            die();
        }
        $result = $client->call('bpVerifyRequest', $parameters, $namespace);
        $VerifyAnswer = $result;

        if($VerifyAnswer == '0'){
            // Call the SOAP method
            $result = $client->call('bpSettleRequest', $parameters, $namespace);
            $SetlleAnswer = $result;
            if ($SetlleAnswer == '0'){
                $Pay_Status = 'OK';
            }
        }
        if ($VerifyAnswer <> '0' AND $VerifyAnswer != '' ){
            $result = $client->call('bpInquiryRequest', $parameters, $namespace);
            $InquiryAnswer = $result ;
            if ($InquiryAnswer == '0'){
                // Call the SOAP method
                $result = $client->call('bpSettleRequest', $parameters, $namespace);
                $SetlleAnswer = $result;
            }else{
                // Call the SOAP method
                $result = $client->call('bpReversalRequest', $parameters, $namespace);
            }
        }
        if ($Pay_Status == 'OK') {
            return array(
                'status' => true,
                "transId" => $verifySaleReferenceId,
            );
        } else {

            return array("status" => false, "msg" => $result);
        }

    }
}
