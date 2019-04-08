<?php

namespace app\utils;

/**
 * IP location query class
 * Class IpLocationUtils
 *
 * @author: lirong
 */
class IpLocationUtils
{
    /**
     * QQWry. Dat file pointer
     *
     * @var bool|resource
     * @author: lirong
     */
    private $fp;

    /**
     * The offset address of the first IP record
     *
     * @var int
     */
    private $firstIp;

    /**
     * The offset address of the last IP record
     *
     * @var int
     */
    private $lastIp;

    /**
     * The total number of IP records (excluding version information records)
     *
     * @var int
     */
    private $totalIp;

    /**
     * Constructor, open the QQWry. Dat file and initialize the information in the class
     * IpLocationUtils constructor.
     *
     * @param string $filename
     */
    public function __construct($filename = 'UTFWry.dat')
    {
        $this->fp = 0;
        if (($this->fp = fopen(__DIR__ . '/resource/' . $filename, 'rb')) !== false) {
            $this->firstIp = $this->getLong();
            $this->lastIp = $this->getLong();
            $this->totalIp = ($this->lastIp - $this->firstIp) / 7;
        }
    }

    /**
     * Returns the number of long integers read
     *
     * @access private
     * @return int
     */
    private function getLong(): int
    {
        //将读取的little-endian编码的4个字节转化为长整型数
        $result = unpack('Vlong', fread($this->fp, 4));
        return $result['long'];
    }

    /**
     * 根据所给 IP 地址或域名返回所在地区信息
     *
     * @access public
     * @param string $ip
     * @return array
     */
    public function getLocation($ip = ''): array
    {
        if (!$this->fp) {
            return null;
        }            // 如果数据文件没有被正确打开，则直接返回空
        if (empty($ip)) {
            $ip = $this->getClientIp();
        }
        $location['ip'] = gethostbyname($ip);   // 将输入的域名转化为IP地址
        $ip = $this->packIp($location['ip']);   // 将输入的IP地址转化为可比较的IP地址
        // 不合法的IP地址会被转化为255.255.255.255
        // 对分搜索
        $l = 0;                         // 搜索的下边界
        $u = $this->totalIp;            // 搜索的上边界
        $findIp = $this->lastIp;        // 如果没有找到就返回最后一条IP记录（QQWry.Dat的版本信息）
        while ($l <= $u) {              // 当上边界小于下边界时，查找失败
            $i = floor(($l + $u) / 2);  // 计算近似中间记录
            fseek($this->fp, $this->firstIp + $i * 7);
            $beginIp = strrev(fread($this->fp, 4));     // 获取中间记录的开始IP地址
            // strrev函数在这里的作用是将little-endian的压缩IP地址转化为big-endian的格式
            // 以便用于比较，后面相同。
            if ($ip < $beginIp) {       // 用户的IP小于中间记录的开始IP地址时
                $u = $i - 1;            // 将搜索的上边界修改为中间记录减一
            } else {
                fseek($this->fp, $this->getLongThree());
                $endip = strrev(fread($this->fp, 4));   // 获取中间记录的结束IP地址
                if ($ip > $endip) {     // 用户的IP大于中间记录的结束IP地址时
                    $l = $i + 1;        // 将搜索的下边界修改为中间记录加一
                } else {                  // 用户的IP在中间记录的IP范围内时
                    $findIp = $this->firstIp + $i * 7;
                    break;              // 则表示找到结果，退出循环
                }
            }
        }

        //获取查找到的IP地理位置信息
        fseek($this->fp, $findIp);
        $location['beginip'] = long2ip($this->getLong());   // 用户IP所在范围的开始地址
        $offset = $this->getLongThree();
        fseek($this->fp, $offset);
        $location['endip'] = long2ip($this->getLong());     // 用户IP所在范围的结束地址
        $byte = fread($this->fp, 1);    // 标志字节
        switch (ord($byte)) {
            case 1:                     // 标志字节为1，表示国家和区域信息都被同时重定向
                $countryOffset = $this->getLongThree();         // 重定向地址
                fseek($this->fp, $countryOffset);
                $byte = fread($this->fp, 1);    // 标志字节
                $i1 = ord($byte);
                if ($i1 === 2) {             // 标志字节为2，表示国家信息又被重定向
                    fseek($this->fp, $this->getLongThree());
                    $location['country'] = $this->getString();
                    fseek($this->fp, $countryOffset + 4);
                    $location['area'] = $this->getArea();
                } else {            // 否则，表示国家信息没有被重定向
                    $location['country'] = $this->getString($byte);
                    $location['area'] = $this->getArea();
                }
                break;
            case 2:                     // 标志字节为2，表示国家信息被重定向
                fseek($this->fp, $this->getLongThree());
                $location['country'] = $this->getString();
                fseek($this->fp, $offset + 8);
                $location['area'] = $this->getArea();
                break;
            default:                    // 否则，表示国家信息没有被重定向
                $location['country'] = $this->getString($byte);
                $location['area'] = $this->getArea();
                break;
        }
        if ($location['country'] === ' CZ88.NET') {  // CZ88.NET表示没有有效信息
            $location['country'] = '未知';
        }
        if ($location['area'] === ' CZ88.NET') {
            $location['area'] = '';
        }
        return $location;
    }

    /**
     * getClientIp
     *
     * @return mixed
     * @author: lirong
     */
    public function getClientIp()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 'unknown';
        }
        return $ip;
    }

    /**
     * Returns the IP address that can be compared after compression
     *
     * @access private
     * @param string $ip
     * @return string
     */
    private function packIp($ip): string
    {
        // 将IP地址转化为长整型数，如果在PHP5中，IP地址错误，则返回False，
        // 这时intval将Flase转化为整数-1，之后压缩成big-endian编码的字符串
        return pack('N', (int)ip2long($ip));
    }

    /**
     * Returns the 3 bytes of the long integer read
     *
     * @access private
     * @return int
     */
    private function getLongThree(): int
    {
        //将读取的little-endian编码的3个字节转化为长整型数
        $result = unpack('Vlong', fread($this->fp, 3) . chr(0));
        return $result['long'];
    }

    /**
     * Return the read string
     *
     * @access private
     * @param string $data
     * @return string
     */
    private function getString($data = ''): string
    {
        $char = fread($this->fp, 1);
        while (ord($char) > 0) {        // 字符串按照C格式保存，以\0结束
            $data .= $char;             // 将读取的字符连接到给定字符串之后
            $char = fread($this->fp, 1);
        }
        return $data;
    }

    /**
     * Return area information
     *
     * @return string
     * @author: lirong
     */
    private function getArea(): string
    {
        $byte = fread($this->fp, 1);    // 标志字节
        switch (ord($byte)) {
            case 0:                     // 没有区域信息
                $area = '';
                break;
            case 1:
            case 2:                     // 标志字节为1或2，表示区域信息被重定向
                fseek($this->fp, $this->getLongThree());
                $area = $this->getString();
                break;
            default:                    // 否则，表示区域信息没有被重定向
                $area = $this->getString($byte);
                break;
        }
        return $area;
    }

    /**
     * A destructor that automatically closes an open file after the page has finished executing.
     */
    public function __destruct()
    {
        if ($this->fp) {
            fclose($this->fp);
        }
        $this->fp = 0;
    }
}
