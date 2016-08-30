<?php
// Indirect Use => Use by another method in the class. Not by user
class iPay88 {

    public $host = 'https://www.mobile88.com/epayment/entry.asp';
    var $array, $merchantKey;

    function __construct() {
        $this->array = array();
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
     *  Set MerchantKey from iPay88
     *  Keep it secret
     */
    public function setMerchantKey($data) {
        $this->merchantKey = $data;
        return $this;
    }

    /*
     *  Set Merchant Code from iPay88
     */
    public function setMerchantCode($data) {
        $this->array['MerchantCode'] = $data;
        return $this;
    }

    /*
     *  SetPaymentId (Optional)
     *  Credit Card(MYR): 2
     *  Maybank2u       : 6
     *  Alliance Online : 8
     *  AmOnline        : 10
     *  RHB Online      : 14
     *  Hong Leong Bank : 15
     *  FPX             : 16
     *  CIMB Clicks     : 20
     *  WebCash         : 22
     *  Celcom AirCash  : 100
     *  Bank Rakyat     : 102
     *  Affin Online    : 103
     *  PayPal (MYR)    : 48
     */
    public function setPaymentId($data) {
        $this->array['PaymentId'] = $data;
        return $this;
    }

    /*
     * Any string from system
     */
    public function setRefNo($data) {
        $this->array['RefNo'] = substr($data, 0, 19);
        return $this;
    }

    /* 
     * Set amount to be paid
     */
    public function setAmount($data) {
        $this->array['Amount'] = number_format($data, 2);
        return $this;
    }

    /* 
     * Set MYR only
     */
    public function setCurrency($data) {
        $this->array['Currency'] = $data;
        return $this;
    }

    /*
     * Set Product Descrition
     */
    public function setProdDesc($data) {
        $this->array['ProdDesc'] = substr($data, 0, 99);
        return $this;
    }

    /* 
     * Set Customer Name
     */
    public function setUserName($data) {
        $this->array['UserName'] = substr($data, 0, 99);
        return $this;
    }

    /*
     * Set Customer Email
     */
    public function setUserEmail($data) {
        $this->array['UserEmail'] = substr($data, 0, 99);
        return $this;
    }

    /*
     * Set User Mobile Phone Number
     */
    public function setUserContact($data) {
        $this->array['UserContact'] = substr($data, 0, 19);
        return $this;
    }

    /*
     * Set Remark (Optional)
     */
    public function setRemark($data) {
        $this->array['Remark'] = substr($data, 0, 99);
        return $this;
    }

    /*
     * Set Lang. Use UTF-8
     */
    public function setLang($data) {
        $this->array['Lang'] = substr($data, 0, 19);
        return $this;
    }

    /*
     * Set Return URL when user complete the process
     */
    public function setResponseURL($data) {
        $this->array['ResponseURL'] = substr($data, 0, 199);
        return $this;
    }

    /* 
     * Set Callback URL for server to server update
     */
    public function setBackendURL($data) {
        $this->array['BackendURL'] = substr($data, 0, 199);
        return $this;
    }

    /*
     *  Generate Signature
     */

    public function prepare() {
        $amount = preg_replace("/[^0-9]/", "", $this->array['Amount']);
        $this->array['Signature'] = $this->iPay88_signature($this->merchantKey . $this->array['MerchantCode'] . $this->array['PaymentId'] . $this->array['RefNo'] . $amount . $this->array['Currency']);
        return $this;
    }

    /* 
     *  Generate HTML Form
     */
    public function process() {
        $output = '<form method="post" action="' . $this->host . '" name="ePayment">';
        foreach ($this->array as $key => $value) {
            $output.= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }
        $output.= '<input type="submit" name="Submit" value="Proceed"></form>';
        return $output;
    }

    /*
     *  Not Allowed by iPay88
     *  Will Result to Permission Not Allow Error
     * 
     */
    public function curlAction() {
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $this->host);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($this->array));
        $return = curl_exec($process);
        curl_close($process);
        echo $return;
    }

}
