<?php

class iPay88Callback {

    var $array, $merchantKey, $host;

    public function __construct($merchantKey) {
        $this->array = array(
            'MerchantCode' => $_POST['MerchantCode'],
            'PaymentId' => $_POST['PaymentId'],
            'RefNo' => $_POST['RefNo'],
            'Amount' => $_POST['Amount'],
            'Currency' => $_POST['Currency'],
            'Remark' => $_POST['Remark'],
            'TransId' => $_POST['TransId'],
            'AuthCode' => $_POST['AuthCode'],
            'Status' => $_POST['Status'],
            'ErrDesc' => $_POST['ErrDesc'],
            'Signature' => $_POST['Signature'],
            'CCName' => $_POST['CCName'],
            'S_bankname' => $_POST['S_bankname'],
            'S_country' => $_POST['S_country'],
            'TokenId' => $_POST['TokenId']
        );
        $this->merchantKey = $merchantKey;
    }

    /*
     * Generate iPay88 Signature for Verification
     * Indirect Use
     * 
     */

    function iPay88_signature($source) {
        return base64_encode(hex2bin(sha1($source)));
    }

    function hex2bin($hexSource) {
        for ($i = 0; $i < strlen($hexSource); $i = $i + 2) {
            $bin .= chr(hexdec(substr($hexSource, $i, 2)));
        }
        return $bin;
    }

    /*
     * By default Signature verification is enough
     * But, if want extra verification, make extra call
     * 
     */
    public function verifySignature() {
        $amount = preg_replace("/[^0-9]/", "", $this->array['Amount']);
        $string = $this->iPay88_signature($this->merchantKey . $this->array['MerchantCode'] . $this->array['PaymentId'] . $this->array['RefNo'] . $amount . $this->array['Currency'] . $this->array['Status']);
        if ($string == $this->array['Signature']) {
            echo 'RECEIVEOK';
            return $this;
        } else {
            exit('Signature Not Match');
        }
    }
    
    public function requeryStatus(){
        $host = 'https://www.mobile88.com/epayment/enquiry.asp';
        //$host.= '?MerchantCode='.$this->array['MerchantCode'].'&RefNo='.$this->array['RefNo']. '&Amount='.$this->array['Amount'];
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $host);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($this->array));
        $return = curl_exec($process);
        curl_close($process);
        if ($return == '00'){
            return $this;
        }else {
            exit($return);
        }
    }
    
    /*
     *  Not used in callback
     */
    public function getData() {
        return $this->array;
    }

}
