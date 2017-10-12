# bittrex
[![Total Downloads](https://img.shields.io/packagist/dt/vincenth520/bittrex.svg)](https://packagist.org/packages/vincenth520/bittrex)
[![License](https://img.shields.io/badge/bittrex-v1.1-brightgreen.svg)](https://bittrex.com/Home/Api)
[![License](https://poser.pugx.org/vincenth520/bittrex/license.svg)](https://packagist.org/packages/vincenth520/bittrex)
## 安装
- 通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者运行下面的命令。SDK 包已经放到这里 [vincenth520/bittrex](https://packagist.org/packages/vincenth520/bittrex) 。

```
composer require vincenth520/bittrex
```

- 直接下载安装，SDK 没有依赖其他第三方库，但需要参照 composer的autoloader，增加一个自己的autoloader程序。

## 运行环境
![php-5.3](https://img.shields.io/badge/php-5.3-yellowgreen.svg)

## 使用方法

### 获取账户余额
 
```php
use Bittrex\Bittrex;

$apikey='';
$apisecret='';

$Bittrex = new Bittrex($apikey,$apisecret);

$balance = $Bittrex->getBalance();

var_dump($balance);
```

**打印**
```
array(1) {
  [3]=>
  array(5) {
    ["Currency"]=>
    string(3) "SPR"
    ["balance"]=>
    float(46.64854959)
    ["Last"]=>
    float(0.000145)
    ["estbtc"]=>
    float(0.00676403969055)
    ["estusd"]=>
    float(33.289356617836)
  }
}
```


## 代码许可
The MIT License (MIT).详情见 [License文件](https://github.com/vincenth520/bittrex/blob/master/LICENSE).
