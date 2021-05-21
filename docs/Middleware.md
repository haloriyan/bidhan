# Middleware

Middleware is software in the middle of request and process. This is gonna help you to check any incoming request before it can be processed. As an example, you may check the user who accessing route `/admin/dashboard` has logged in or not.

First, we generate the middleware for `Admin`

```
php canyou make:middleware Admin
```

Then you have file `/app/Middleware/Admin.php`. Open it up and you will see this code

```php
<?php

namespace App\Middleware;

use App\Framework\Auth;

class Admin {
    public function handle() {
        // 
    }
}
```

We will work in the `handle()` method.

## Checking if user has not logged in

Open your `Admin` middleware and put this code inside `handle()` method

```php
if ($myData = Auth::guard('admin')->check()) {
    return redirect(route('admin.loginPage'), [
        'errors' => "Sorry, but you have to logged in first before accessing the page"
    ]);
}
```

Of course you must have the `admin.loginPage` route in `/config/routes.php`.

## Implement to routes configuration

As route documentation said, we have two ways to implement a middleware to route.

- Via group

```php
Route::group(['prefix' => "admin", 'middleware' => "Admin"], function () {
    // Restricted route
    Route::get('dashboard', "AdminController@dashboard");
});
```

- At each route

```php
Route::group(['prefix' => "admin"], function () {
    Route::get('login', "AdminController@loginPage");
    Route::post('login', "AdminController@login");

    // Restricted route
    Route::get('dashboard', "AdminController@dashboard")->middleware("Admin");
});
```