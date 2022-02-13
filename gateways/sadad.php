<?php

class Sadad
{
    public function send($data, $Amount, $OrderId, $ReturnUrl, $currency = "IRR", $Payload = null)
    {
        //Prepare data
        session_start();
        if ($currency == "IRT")
            $Amount = round($Amount * 10);
        $LocalDateTime = date("m/d/Y g:i:s a");
        $SignData = $this->encrypt_pkcs7("$data->TerminalId;$OrderId;$Amount", "$data->key");
        $data = array('TerminalId' => $data->TerminalId,
            'MerchantId' => $data->MerchantId,
            'Amount' => $Amount,
            'SignData' => $SignData,
            'ReturnUrl' => $ReturnUrl,
            'LocalDateTime' => $LocalDateTime,
            'OrderId' => $OrderId);
        $str_data = json_encode($data);
        $res = $this->CallAPI('https://sadad.shaparak.ir/vpg/api/v0/Request/PaymentRequest', $str_data);
        $arrres = json_decode($res);
        if ($arrres->ResCode == "0") {
            $Token = $arrres->Token;
            return array('status' => true, "url" => "https://sadad.shaparak.ir/VPG/Purchase?Token=$Token");
        } else {
            return array('status' => false, "msg" => 'امکان اتصال وجود ندارد ، لطفاً دوباره تلاش کنید.');
        }
    }

    function encrypt_pkcs7($str, $key)
    {
        $key = base64_decode($key);
        $ciphertext = OpenSSL_encrypt($str, "DES-EDE3", $key, OPENSSL_RAW_DATA);
        return base64_encode($ciphertext);
    }


    function CallAPI($url, $data = false)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function verify($key, $Token, $ResCode)
    {
        if ($ResCode == 0) {
            $verifyData = array('Token' => $Token, 'SignData' => $this->encrypt_pkcs7($Token, $key));
            $str_data = json_encode($verifyData);
            $res = $this->CallAPI('https://sadad.shaparak.ir/vpg/api/v0/Advice/Verify', $str_data);
            $arrres = json_decode($res);
        }
        if ($arrres->ResCode != -1 && $arrres->ResCode == 0) {
            return array('status' => true, 'id' => $arrres->OrderId, 'transId' => $arrres->SystemTraceNo, 'SrcNum' => $arrres->RetrivalRefNo);
        } else
            return array('status' => false, "تراکنش نا موفق بود در صورت کسر مبلغ از حساب شما حداکثر پس از 72 ساعت مبلغ به حسابتان برمی گردد.");

    }
}
