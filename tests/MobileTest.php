<?php
namespace Chendujin\Mobile\Tests;

use Chendujin\Mobile\Mobile;
use PHPUnit\Framework\TestCase;

class MobileTest extends TestCase
{
    public function testIsValid()
    {
        $mobile = new Mobile();
        $this->assertTrue($mobile->isCMCC('18807397135')); // 移动
        $this->assertTrue($mobile->isCUCC('15507645084')); // 联通
        $this->assertTrue($mobile->isCTCC('18923516448')); // 电信
        $this->assertTrue($mobile->isCBCC('19212071413')); // 广电
    }

    public function testGetInfo()
    {
        $mobile = new Mobile();
        $this->assertEquals(
            [
                'mobile' => '18621281566',
                'province' => '上海',
                'city' => '上海',
                'zip_code' => '200000',
                'area_code' => '021',
                'operator_type' => '联通',
            ],
            $mobile->search('18621281566')
        );
    }
}
