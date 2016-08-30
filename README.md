# iPay88 PHP Basic API

Generate HTML Form to send to iPay88 API 

# How to use
## Sending user to iPay88 Payment Page
1. Use **require_once** or **include_once** for iPay88.php file. 
###Example: **require_once 'iPay88.php';**

2. Create new object
###Example: **$obj = new iPay88;**

3. Send Parameter & Print Output
###Example: 
        echo $obj->setMerchantKey('')
        ->setMerchantCode('')
        ->setPaymentId('')
        ->setRefNo('A00')
        ->setAmount('1.00')
        ->setCurrency('MYR')
        ->setProdDesc('Untung la Sangat')
        ->setUserName('Wan Zul')
        ->setUserEmail('wanzulkarnain69@gmail.com')
        ->setUserContact('0145356443')
        ->setRemark('')
        ->setLang('UTF-8')
        ->setResponseURL('http://woo.wanzul-hosting.com/igniter/ssa.php')
        ->setBackendURL('http://woo.wanzul-hosting.com/igniter/ssa.php')
        ->prepare()
        ->process();

## After user completed the process on iPay88
1. Use **require_once** or **include_once** for iPay88.php file. 
###Example: **require_once 'iPay88Callback.php';**

2. Create new object and pass your merchant key
###Example: **$obj = new iPay88Callback('ABC');**

3. For ResponseURL, call method as example below:
###Example: **$obj->verifySignature()->getData();**

4. For BackendURL, call method as example below:
###Example: **$obj->verifySignature()->requeryStatus()->getData();**

# Donate

Support me by giving donation: http://www.wanzul.net/donate

# Hosting

Get cheap and affordable web hosting, starting from RM39.99/year

[www.wanzul-hosting.com](https://www.wanzul-hosting.com)
