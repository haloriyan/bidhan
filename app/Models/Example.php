<?php

namespace App\Models;

use App\Framework\Model;

class Example extends Model {
    // protected static $table = 'pkbs';

    public function relationName() {
        return self::hasMany('App\Models\Pkb', 'company_id');
    }
}