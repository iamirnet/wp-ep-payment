<?php

class PayIR
{
    function send($api, $amount, $factorNumber = null, $redirect,$currency = "IRR")
    {
        if ($currency == "IRT")
            $amount = round($amount*10);
        $res = $this->curl_post('https://pay.ir/pg/send', [
            'api'          => $api,
            'amount'       => $amount,
            'redirect'     => $redirect,
            'factorNumber' => $factorNumber,
        ]);
        if($res->status) {
            $go = "https://pay.ir/pg/$res->token";
            return array('status' => true, 'url' => $go);
        } else {
            return array("status" => false, "msg" => $res->errorMessage);
        }
    }

    function verify($api, $transId)
    {
        $res = $this->curl_post('https://pay.ir/pg/verify', [
            'api' 	=> $api,
            'token' => $transId,
        ]);
        if($res->status == 1){
            return array(
                'status' => true,
                "transId" => $res->transId,
            );
        } else {

            return array("status" => false, "msg" => $res->errorMessage);
        }
    }

    function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res);
    }
}
