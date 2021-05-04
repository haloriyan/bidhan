<?php

namespace App\Framework;

use App\Framework\DB;

class Model extends DB {
    public static function getTableName($fromClass) {
        global $tabel;

        $classPath = explode("\\", $fromClass);
        $className = $classPath[count($classPath) - 1];

        if (!property_exists($fromClass, 'table')) {
            $toSetTableName = $className;
            $lastCharacter = substr($toSetTableName, -1);
            if ($lastCharacter == "s") {
                $toSetTableName .= "es";
            } else if ($lastCharacter == "y") {
                $toSetTableName = substr_replace($toSetTableName, "ies", -1);
            } else {
                $toSetTableName .= "s";
            }
        }else {
            $toSetTableName = $fromClass::$table;
        }
        $tabel = strtolower($toSetTableName);
    }
    public function hasMany($foreignClass, $foreignKey) {
        $methodCaller = debug_backtrace()[1]['function'];
        return [
            "methodCaller" => $methodCaller,
            "foreignClass" => $foreignClass,
            "foreignKey" => $foreignKey,
            "relationshipType" => "hasMany"
        ];
    }
    public function hasOne($foreignClass, $foreignKey) {
        $methodCaller = debug_backtrace()[1]['function'];
        return [
            "methodCaller" => $methodCaller,
            "foreignClass" => $foreignClass,
            "foreignKey" => $foreignKey,
            "relationshipType" => "hasOne"
        ];
    }
    public function belongsTo($foreignClass, $foreignKey) {
        $methodCaller = debug_backtrace()[1]['function'];
        return [
            "methodCaller" => $methodCaller,
            "foreignClass" => $foreignClass,
            "foreignKey" => $foreignKey,
            "relationshipType" => "belongsTo"
        ];
    }
}