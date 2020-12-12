<?php
namespace App\Framework;

class Request {
    private $data = [];
    private static $_instance = null;

    public function __get($varName){
        if (!array_key_exists($varName,$this->data)) {
            //this attribute is not defined!
        }
            else return $this->data[$varName];
    }
    public function __set($varName,$value){
        $this->data[$varName] = $value;
    }
    public function __construct() {
        $inputs = file_get_contents('php://input');
        
        // Handle post params
        if($inputs !== NULL) {
            if (@$_SERVER['CONTENT_TYPE'] == "application/json") {
                $datas = json_decode($inputs, true);
                foreach ($datas as $key => $value) {
                    $this->{$key} = $value;
                }
            }else {
                $httpMethod = $_SERVER['REQUEST_METHOD'];

                if ($httpMethod == "POST") {
                    $postData = $_POST;
                    if (count($postData) != 0) {
                        foreach ($postData as $key => $value) {
                            $this->{$key} = $value;
                        }
                    }
                }else {
                    $datas = explode("&", $inputs);
                    foreach($datas as $key => $value) {
                        $a = explode("=", $value);
                        $this->{$a[0]} = @$a[1];
                    }
                }
            }
        }

        // Handle get params
        foreach($_GET as $key => $value) {
            if ($key != "lokasiItuUntukDefaultParam") {
                $this->{$key} = $value;
            }
        }
    }
}