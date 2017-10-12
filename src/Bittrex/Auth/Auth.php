<?php

namespace Bittrex\Auth;

class Auth
{
    private $uri = 'https://bittrex.com/api/';
    public function __construct($apikey,$apisecret)
    {
        $this->apikey = $apikey;
        $this->apisecret = $apisecret;
    }

    public function sign($uri)
    {
        return hash_hmac('sha512',$uri,$this->apisecret);
    }

    //无需秘钥访问
    public function reqPublic($url)
    {
        $uri = $this->uri.$url;
        $curlobj = curl_init(); 
        curl_setopt($curlobj,CURLOPT_URL,$uri);  
        curl_setopt($curlobj,CURLOPT_RETURNTRANSFER,1);  
        date_default_timezone_set('PRC'); 
        curl_setopt($curlobj,CURLOPT_SSL_VERIFYPEER,0);            
        $output = curl_exec($curlobj);  
        curl_close($curlobj);  
        return json_decode($output,true); 
    }

    //带秘钥访问
    function reqPrivate($url)
    {	    
        $nonce = time();
        $uri = $this->uri.'v1.1/'.$url.'?apikey='.$this->apikey.'&nonce='.$nonce;
        $curlobj = curl_init(); 
        curl_setopt($curlobj,CURLOPT_URL,$uri); 
        $sign = $this->sign($uri);
        curl_setopt($curlobj, CURLOPT_HTTPHEADER, array('Expect:','apisign:'.$sign));
        curl_setopt($curlobj,CURLOPT_RETURNTRANSFER,1);  
        date_default_timezone_set('PRC'); 
        curl_setopt($curlobj,CURLOPT_SSL_VERIFYPEER,0); 
        $output = curl_exec($curlobj);  
        curl_close($curlobj);  
        return json_decode($output,true);
    }

    //获取账户余额
    public function getBalance()
    {
        $url = 'v2.0/pub/currencies/GetBTCPrice';
        $data = getPublicUrl($url);
        if($data['success']){
            return $data['result']['bpi']['USD']['rate_float'];
        }
    }
}

?>