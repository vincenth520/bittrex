<?php 

//获取余额
$url = 'account/getbalances';

do{
	$balance = getPrivateUrl($url);
}while(!$balance['success']);

$balance = $balance['result'];	
$btcPrice = getBtcPrice();
foreach($balance as $k=>$v){
	if($v['Currency'] == 'BTC' || $v['Balance'] == 0){
		unset($balance[$k]);
		continue;
	}

	$newbalance[$k]['Currency'] = $v['Currency'];
	$newbalance[$k]['balance'] = $v['Balance'];
	$ticker = getticker('BTC-'.$v['Currency']);
	$newbalance[$k]['Last'] = $ticker['Last'];
	$newbalance[$k]['estbtc'] = $v['Balance'] * $ticker['Last'];
	$newbalance[$k]['estusd'] = $newbalance[$k]['estbtc'] * $btcPrice;

}
var_dump($newbalance);


function getticker($type)
{
	$url = 'v1.1/public/getticker?market='.$type;
	$data = getPublicUrl($url);
	if($data['success']){
		return $data['result'];
	}
}

function getBtcPrice()
{
	$url = 'v2.0/pub/currencies/GetBTCPrice';
	$data = getPublicUrl($url);
	if($data['success']){
		return $data['result']['bpi']['USD']['rate_float'];
	}
	//return getticker('USDT-BTC');
}



//获取挂单
$url = 'market/getopenorders';
$order = getPrivateUrl($url);
var_dump($order);

function getPrivateUrl($url)
{	
	$apikey='d2e2cf1faa824ac686f1d2715928f161';
	$apisecret='5a15b360495a4fe393048bcfac2eba0a';

	$nonce = time();
	$uri = 'https://bittrex.com/api/v1.1/'.$url.'?apikey='.$apikey.'&nonce='.$nonce;
	$curlobj = curl_init();  
	//设置访问网页的URL  
	curl_setopt($curlobj,CURLOPT_URL,$uri);  
	//执行之后不直接打印出来  
	$sign=hash_hmac('sha512',$uri,$apisecret);

	curl_setopt($curlobj, CURLOPT_HTTPHEADER, array('Expect:','apisign:'.$sign));
	curl_setopt($curlobj,CURLOPT_RETURNTRANSFER,1);  
	  
	//设置HTTPS支持  
	//使用cookies的时候必须先设置时区  
	date_default_timezone_set('PRC');  
	//终止从服务器端验证  
	curl_setopt($curlobj,CURLOPT_SSL_VERIFYPEER,0);  
	  
	$output = curl_exec($curlobj);  
	//return $output;
	return json_decode($output,true); 
	curl_close($curlobj);  
}

function getPublicUrl($url)
{	
	$uri = 'https://bittrex.com/api/'.$url;
	$curlobj = curl_init();  
	//设置访问网页的URL  
	curl_setopt($curlobj,CURLOPT_URL,$uri);  
	curl_setopt($curlobj,CURLOPT_RETURNTRANSFER,1);  
	  
	//设置HTTPS支持  
	//使用cookies的时候必须先设置时区  
	date_default_timezone_set('PRC');  
	//终止从服务器端验证  
	curl_setopt($curlobj,CURLOPT_SSL_VERIFYPEER,0);  
	  
	$output = curl_exec($curlobj);  
	//return $output;
	return json_decode($output,true); 
	curl_close($curlobj);  
}


?>