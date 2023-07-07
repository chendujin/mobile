## 手机号码归属地解析

* 使用 [https://github.com/xluohome/phonedata](https://github.com/xluohome/phonedata/blob/master/phone.dat) 提供的数据库

### 环境需求
1. php >= 7.0
2. Composer

### 安装

```shell script
composer require chendujin/mobile

```

### 配置

#### Laravel环境无需配置

#### Lumen

将下面代码放入 `bootstrap/app.php`

```shell script
$app->register(Chendujin\Mobile\ServiceProvider::class);
```

### 验证器使用

```php
<?php

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'phone' => 'required|is_cmcc|is_cucc|is_ctcc|is_ctccv|is_cuccv|is_cmccv|is_cbcc|is_cbccv'
    ]);
   
    if ($validator->fails()) {
        return new JsonResponse([
            'state' => 'error',
            'message' => $validator->errors()->first(),
        ]);
    }   

}
```

### 其他使用
```php
$mobile = new \Chendujin\Mobile\Mobile();

// 查询
$mobile->search('15240881243');

// 是否是移动运营商
$mobile->isCMCC('18807397135'); // true

// 是否是联通运营商
$mobile->isCUCC('15507645084'); // true

// 是否是电信运营商
$mobile->isCTCC('18923516448'); // true

// 是否是广电运营商
$mobile->isCBCC('19212071413'); // true

// 是否为手机号码
$mobile->isMobilePhone('18923516448'); // true

// 判断是否为座机号码
$mobile->isTelPhone('5957126'); // true
```

### 命令行工具

```bash
php bin/searchMobile 15240881243
```
返回：
```text
Array
(
    [mobile] => 15240881243
    [province] => 云南
    [city] => 昭通
    [zip_code] => 657000
    [area_code] => 0870
    [operator_type] => 移动
)
time: 0.0093469620 s
memory:664 B
```

### 单元测试

```shell script
./vendor/bin/phpunit
```