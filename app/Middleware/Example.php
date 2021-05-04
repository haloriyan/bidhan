<?php

namespace App\Middleware;

use App\Framework\Auth;

class Example {
    public function handle() {
        if (!Auth::guard('ExampleGuardian')->check()) {
            redirect('admin/login', [
                'error' => "Anda harus login terlebih dahulu"
            ]);
        }
    }
}