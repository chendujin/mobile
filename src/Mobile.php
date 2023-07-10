<?php
namespace Chendujin\Mobile;
class Mobile
{
    private $fileHandle = null;
    private $fileSize = 0;
    private $version = 0;

    const CARD_TYPE = [
        1 => '移动',
        2 => '联通',
        3 => '电信',
        4 => '电信虚拟运营商',
        5 => '联通虚拟运营商',
        6 => '移动虚拟运营商',
        7 => '广电',
        8 => '广电虚拟运营商',
    ];

    public function __construct()
    {
        $filePath = __DIR__ . '/../data/phone.dat';
        $this->fileHandle = fopen($filePath, 'rb');
        $this->fileSize = filesize($filePath);
    }


    /**
     * 获取电话号码数据库版本
     *
     * @return integer
     */
    public function getDbVersion(): int
    {
        if ($this->version) {
            return $this->version;
        }
        fseek($this->fileHandle, 0);
        return $this->version = fread($this->fileHandle, 4);
    }

    /**
     * 查找单个手机号码归属地信息
     * @param string $mobile
     * @return array
     */
    public function search(string $mobile): array
    {
        $item = [];
        if (strlen($mobile) != 11) {
            return $item;
        }
        $telPrefix = substr($mobile, 0, 7);

        fseek($this->fileHandle, 4);
        $offset = fread($this->fileHandle, 4);
        $indexBegin = implode('', unpack('L', $offset));
        $total = ($this->fileSize - $indexBegin)/9;

        $position = $leftPos = 0;
        $rightPos = $total;

        while ($leftPos < $rightPos - 1) {
            $position = $leftPos + (($rightPos - $leftPos) >> 1);
            fseek($this->fileHandle, ($position*9) + $indexBegin);
            $idx = implode('', unpack('L', fread($this->fileHandle, 4)));
            // echo 'position = '.$position.' idx = '.$idx;
            if ($idx < $telPrefix) {
                $leftPos = $position;
            } elseif ($idx > $telPrefix) {
                $rightPos = $position;
            } else {
                // 找到数据
                fseek($this->fileHandle, ($position*9+4) + $indexBegin);
                $itemIdx = unpack('Lidx_pos/ctype', fread($this->fileHandle, 5));
                $itemPos = $itemIdx['idx_pos'];
                $type = $itemIdx['type'];
                fseek($this->fileHandle, $itemPos);
                $itemStr = '';
                while (($tmp = fread($this->fileHandle, 1)) != chr(0)) {
                    $itemStr .= $tmp;
                }
                $item = $this->mobileInfo($mobile, $itemStr, $type);
                break;
            }
        }
        return $item;
    }

    /**
     * 解析归属地信息
     * @param string $mobile
     * @param string $itemStr
     * @param int $type
     * @return array
     */
    private function mobileInfo(string $mobile, string $itemStr, int $type): array
    {
        $typeStr = self::CARD_TYPE[$type] ?? '';
        $itemArr = explode('|', $itemStr);
        return ['mobile' => $mobile, 'province' => $itemArr[0], 'city' => $itemArr[1], 'zip_code' => $itemArr[2], 'area_code' => $itemArr[3], 'operator_type' => $typeStr];
    }

    // 是否移动运营商
    public function isCMCC(string $mobile): bool
    {
        if (empty($mobile)) { return false;}
        $mobileArr = $this->search($mobile);
        if (!$mobileArr) { return false;}
        return $mobileArr['operator_type'] == self::CARD_TYPE[1];
    }

    // 是否联通运营商
    public function isCUCC(string $mobile): bool
    {
        if (empty($mobile)) { return false;}
        $mobileArr = $this->search($mobile);
        if (!$mobileArr) { return false;}
        return $mobileArr['operator_type'] == self::CARD_TYPE[2];
    }

    // 是否电信运营商
    public function isCTCC(string $mobile): bool
    {
        if (empty($mobile)) { return false;}
        $mobileArr = $this->search($mobile);
        if (!$mobileArr) { return false;}
        return $mobileArr['operator_type'] == self::CARD_TYPE[3];
    }

    // 是否电信虚拟运营商
    public function isCTCCV(string $mobile): bool
    {
        if (empty($mobile)) { return false;}
        $mobileArr = $this->search($mobile);
        if (!$mobileArr) { return false;}
        return $mobileArr['operator_type'] == self::CARD_TYPE[4];
    }

    // 是否联通虚拟运营商
    public function isCUCCV(string $mobile): bool
    {
        if (empty($mobile)) { return false;}
        $mobileArr = $this->search($mobile);
        if (!$mobileArr) { return false;}
        return $mobileArr['operator_type'] == self::CARD_TYPE[5];
    }

    // 是否移动虚拟运营商
    public function isCMCCV(string $mobile): bool
    {
        if (empty($mobile)) { return false;}
        $mobileArr = $this->search($mobile);
        if (!$mobileArr) { return false;}
        return $mobileArr['operator_type'] == self::CARD_TYPE[6];
    }

    // 是否广电运营商
    public function isCBCC(string $mobile): bool
    {
        if (empty($mobile)) { return false;}
        $mobileArr = $this->search($mobile);
        if (!$mobileArr) { return false;}
        return $mobileArr['operator_type'] == self::CARD_TYPE[7];
    }

    // 是否广电虚拟运营商
    public function isCBCCV(string $mobile): bool
    {
        if (empty($mobile)) { return false;}
        $mobileArr = $this->search($mobile);
        if (!$mobileArr) { return false;}
        return $mobileArr['operator_type'] == self::CARD_TYPE[8];
    }

    /**
     * 判断是否为手机号码
     * @param string $mobilePhone
     * @return boolean
     */
    public static function isMobilePhone(string $mobilePhone): bool
    {
        return (bool)preg_match('/^1[3456789]{1}[0-9]{9}$/', $mobilePhone);
    }

    /**
     * 判断是否为座机号码
     * @param string $telPhone
     * @return boolean
     */
    public static function isTelPhone(string $telPhone): bool
    {
        return (bool)preg_match('/^(0[0-9]{2,3}(\-)?)?\d{7,8}$/', $telPhone);
    }

    public function __destruct()
    {
        fclose($this->fileHandle);
    }
}
