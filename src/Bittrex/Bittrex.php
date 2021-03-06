<?php

namespace Bittrex;

use Bittrex\Auth;

class Bittrex
{
    private $balance;

    public function __construct($apikey,$apisecret)
    {
        $this->auth = new Auth($apikey,$apisecret);
    }

    //获取btc当前价格
    public function getBtcPrice()
    {
        $url = 'v2.0/pub/currencies/GetBTCPrice';
        $data = $this->auth->reqPublic($url);
        if($data['success']){
            return $data['result']['bpi']['USD']['rate_float'];
        }else{
            exit(json_encode($data));
        }
    }

    //获取币种情况
    public function getticker($type)
    {
        $url = 'v1.1/public/getticker?market='.$type;
        $data = $this->auth->reqPublic($url);
        if($data['success']){
            return $data['result'];
        }else{
            exit(json_encode($data['result']));
        }
    }

    //获取账户余额
    public function getBalance()
    {
        $url = 'account/getbalances';

        $balance = $this->auth->reqPrivate($url);

        if($balance['success']){
            $balance = $balance['result'];	
            $btcPrice = $this->getBtcPrice();
            foreach($balance as $k=>$v){
                if($v['Currency'] == 'BTC' || $v['Balance'] == 0){ //如果当前币种是btc获取当前币种余额为0,忽略不计
                    unset($balance[$k]);
                    continue;
                }
            
                $newbalance[$k]['Currency'] = $v['Currency'];
                $newbalance[$k]['balance'] = $v['Balance'];
                $ticker = $this->getticker('BTC-'.$v['Currency']);
                $newbalance[$k]['Last'] = $ticker['Last'];
                $newbalance[$k]['est.btc'] = $v['Balance'] * $ticker['Last'];
                $newbalance[$k]['est.usd'] = $newbalance[$k]['estbtc'] * $btcPrice;            
            }
            return $newbalance;
        }else{
            exit(json_encode($balance));
        }        
    }

}