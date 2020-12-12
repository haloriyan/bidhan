<?php

namespace App\Framework;

class Curl {
    public static $ch;
    private static $_instance = null;

    public function __construct() {
        self::$ch = curl_init();
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public static function get($url) {
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        $res = curl_exec(self::$ch);
        return $res;
    }
    public static function post($url, $bodyFields) {
        if (self::$ch == null) {
            self::$ch = curl_init();
        }
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLOPT_POST, 1);
        curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $bodyFields);
        $res = curl_exec(self::$ch);
        curl_close(self::$ch);
        return substr($res, 0, -1);
    }
}