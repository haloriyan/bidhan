<?php

namespace App\Framework;

use App\Framework\DB;

class Model extends DB {
    public function revertTableName($name) {
        $names = explode("_", $name);
        foreach ($names as $i => $n) {
            $names[$i] = ucwords($n);

            if ($i == count($names) - 1) {
                if (substr($n, -2) == "es" && substr($n, -3) != "ies") {
                    $names[$i] = substr_replace($names[$i], "", -2);
                } else if (substr($n, -3) == "ies") {
                    $names[$i] = substr_replace($names[$i], "y", -3);
                } else if (substr($n, -1) == "s") {
                    $names[$i] = substr_replace($names[$i], "", -1);
                }
            }
        }
        return implode("", $names);
    }
    public static function getTableName($fromClass) {
        $classPath = explode("\\", $fromClass);
        $className = $classPath[count($classPath) - 1];

        if (!property_exists($fromClass, 'table')) {
            $toSetTableName = $className;
            
            $names = preg_split('/(?=[A-Z])/', $toSetTableName);
            array_splice($names, 0, 1);

            foreach ($names as $i => $name) {
                $names[$i] = strtolower($name);
                if ($i == count($names) - 1) {
                    $lastCharacter = substr($name, -1);
                    if ($lastCharacter == "s") {
                        $names[$i] .= "es";
                    } else if ($lastCharacter == "y") {
                        $names[$i] = substr_replace($names[$i], "ies", -1);
                    } else {
                        $names[$i] .= "s";
                    }
                }
            }
            $toSetTableName = implode($names, "_");
        }else {
            $toSetTableName = $fromClass::$table;
        }
        return $toSetTableName;
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