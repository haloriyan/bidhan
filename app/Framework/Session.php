<?php
namespace App\Framework;

class Session {
    public function get($name, $index = NULL) {
        @session_start();
        if ($index != NULL) {
            return (array) $_SESSION[$name]->$index;
        }
        // return (array) $_SESSION[$name];
        return $_SESSION[$name];
    }
    public function set($name, $value) {
        @session_start();
        return $_SESSION[$name] = $value;
    }
    public function unset($name) {
        @session_start();
        unset($_SESSION[$name]);
        session_destroy();
    }
}