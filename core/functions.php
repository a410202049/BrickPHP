<?php
/**
 * 公共函数
 * User: mh
 * Date: 2016/9/1
 * Time: 19:58
 */


/**
 * 返回完整url路径
 * @param string $url 变量
 * @return string|null
 */
if (!function_exists('base_url')) {
    function base_url($url){
        $url_str = '/';
        if(!empty($url)){
           $url_str = $_SERVER['HTTP_HOST'].'/'.$url;
        }
        return $url_str;
    }
}

/**
 * 改进后的变量输出
 * @param mixed $var 变量
 * @param bool $echo 是否返回输出
 * @return string|null
 */
if (!function_exists('dumper')) {
	function dumper($arr){
		echo "<pre>";
		print_r( $arr);
		echo "</pre>";
	}
}

/**
 * session操作快捷函数
 * @param string $name session名称
 * @param string $value session值
 */
if (!function_exists('session')) {
    function session($name, $value = '') {
        if ('' === $value) {
            if (is_null($name)) {
                // 清除所有session
                $_SESSION = array();
            } else {
                // 读取session
                return isset($_SESSION[$name]) ? unserialize(base64_decode($_SESSION[$name])) : null;
            }
        } elseif (is_null($value)) {
            // 清除某个session
            unset($_SESSION[$name]);
        } else {
            // 设置session
            $_SESSION[$name] = base64_encode(serialize($value));
        }
        return null;
    }
}

if (!function_exists('set_flashdata')) {
    function set_flashdata($data, $value = NULL){
        session($data, $value);
    }
}

if (!function_exists('get_flashdata')) {
    function get_flashdata($key){
        $value = session($key);
        session($key,null);
        return $value;
    }
}

/**
 * 兼容gzip解压
 * @param string $data
 * @param string $filename
 * @param string $error
 * @param int $maxlength
 * @return boolean
 */
if (!function_exists('gzdecode')) {
    function gzdecode($data, &$filename = '', &$error = '', $maxlength = null) {
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
            $error = "Not in GZIP format.";
            return null;  // Not GZIP format (See RFC 1952)
        }
        $method = ord(substr($data, 2, 1));  // Compression method
        $flags = ord(substr($data, 3, 1));  // Flags
        if ($flags & 31 != $flags) {
            $error = "Reserved bits not allowed.";
            return null;
        }
        // NOTE: $mtime may be negative (PHP integer limitations)
        $mtime = unpack("V", substr($data, 4, 4));
        $mtime = $mtime[1];
        $xfl = substr($data, 8, 1);
        $os = substr($data, 8, 1);
        $headerlen = 10;
        $extralen = 0;
        $extra = "";
        if ($flags & 4) {
            // 2-byte length prefixed EXTRA data in header
            if ($len - $headerlen - 2 < 8) {
                return false;  // invalid
            }
            $extralen = unpack("v", substr($data, 8, 2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                return false;  // invalid
            }
            $extra = substr($data, 10, $extralen);
            $headerlen += 2 + $extralen;
        }
        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string
            if ($len - $headerlen - 1 < 8) {
                return false; // invalid
            }
            $filenamelen = strpos(substr($data, $headerlen), chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                return false; // invalid
            }
            $filename = substr($data, $headerlen, $filenamelen);
            $headerlen += $filenamelen + 1;
        }
        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data in header
            if ($len - $headerlen - 1 < 8) {
                return false;    // invalid
            }
            $commentlen = strpos(substr($data, $headerlen), chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                return false;    // Invalid header format
            }
            $comment = substr($data, $headerlen, $commentlen);
            $headerlen += $commentlen + 1;
        }
        $headercrc = "";
        if ($flags & 2) {
            // 2-bytes (lowest order) of CRC32 on header present
            if ($len - $headerlen - 2 < 8) {
                return false;    // invalid
            }
            $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data, $headerlen, 2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                $error = "Header checksum failed.";
                return false;    // Bad header CRC
            }
            $headerlen += 2;
        }
        // GZIP FOOTER
        $datacrc = unpack("V", substr($data, -8, 4));
        $datacrc = sprintf('%u', $datacrc[1] & 0xFFFFFFFF);
        $isize = unpack("V", substr($data, -4));
        $isize = $isize[1];
        // decompression:
        $bodylen = $len - $headerlen - 8;
        if ($bodylen < 1) {
            // IMPLEMENTATION BUG!
            return null;
        }
        $body = substr($data, $headerlen, $bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8:
                    // Currently the only supported compression method:
                    $data = gzinflate($body, $maxlength);
                    break;
                default:
                    $error = "Unknown compression method.";
                    return false;
            }
        }  // zero-byte body content is allowed
        // Verifiy CRC32
        $crc = sprintf("%u", crc32($data));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($data);
        if (!$lenOK || !$crcOK) {
            $error = ($lenOK ? '' : 'Length check FAILED. ') . ($crcOK ? '' : 'Checksum FAILED.');
            return false;
        }
        return $data;
    }
}