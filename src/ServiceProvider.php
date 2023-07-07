<?php
namespace Chendujin\Mobile;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $validator = $this->app['validator'];

        // 判断是否为手机号码
        $validator->extend('is_mobile_phone', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isMobilePhone($value);
        });

        // 判断是否为座机号码
        $validator->extend('is_tel_phone', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isTelPhone($value);
        });

        // 判断是否为移动运营商
        $validator->extend('is_cmcc', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isCMCC($value);
        });

        // 判断是否为联通运营商
        $validator->extend('is_cucc', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isCUCC($value);
        });

        // 判断是否为电信运营商
        $validator->extend('is_ctcc', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isCTCC($value);
        });

        // 判断是否为电信虚拟运营商
        $validator->extend('is_ctccv', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isCTCCV($value);
        });

        // 判断是否为联通虚拟运营商
        $validator->extend('is_cuccv', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isCUCCV($value);
        });

        // 判断是否为移动虚拟运营商
        $validator->extend('is_cmccv', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isCMCCV($value);
        });

        // 判断是否为广电运营商
        $validator->extend('is_cbcc', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isCBCC($value);
        });

        // 判断是否为广电虚拟运营商
        $validator->extend('is_cbccv', function ($attribute, $value, $paramters, $validator) {
            return  (new Mobile())->isCBCCV($value);
        });
    }
}
