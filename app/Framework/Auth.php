<?php

namespace App\Framework;

class Auth {
    private static $_instance = null;
    protected static $guardianName = 'user';

    public static function guard($name) {
        self::$guardianName = $name;
        $guardians = self::config();
        $guard = @$guardians[$name];
        if (!$guard) {
            die("Guardian not found");
        }

        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function config() {
        $cfg = require '../config/auth.php';
        return $cfg['guards'];
    }
    public static function attempt($credentials) {
        $guardians = self::config();
        $guard = $guardians[self::$guardianName];
        $authData = Session::get('auth_data');
        $criteria = [];
        $criteriaKey = [];

        if ($credentials['password']) {
            $credentials['password'] = md5($credentials['password']);
        }

        foreach ($credentials as $key => $value) {
            array_push($criteria, [$key, '=', $value]);
            array_push($criteriaKey, $key);
        }

        $attemptingLogin = DB::table($guard['table'])->select(implode($criteriaKey, ","))->where($criteria)->toSql();
        if ($attemptingLogin) {
            $authData['guard'][self::$guardianName] = $criteria;
            Session::set('auth_data', $authData);
        }
        
        return $attemptingLogin;
    }
    public function user() {
        $authData = Session::get('auth_data')['guard'][self::$guardianName];
        $guardians = self::config();
        $guard = $guardians[self::$guardianName];

        $data = DB::table($guard['table'])->select()->where($authData)->first();
        if ($data == NULL) {
            self::logout();
        }
        return $data;
    }
    public static function logout() {
        $guard = Session::get('auth_data')['guard'];
        unset($guard[self::$guardianName]);
        Session::set('auth_data', $guard);
        return $guard;
    }
    public static function check() {
        $authData = Session::get('auth_data')['guard'][self::$guardianName];
        return $authData != "" ? true : false;
    }
}