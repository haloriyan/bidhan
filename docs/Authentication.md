# Authentication

Bidhan provide a helper to help you logging in your user in easy way. Before we start, we need to

### Set the guardians

Guardian means user role of your app to differentiate the access of routes. Usually it's like `user` and `admin`. Open `/config/auth.php` to add new guardian.

```php
'guards' => [
    'user' => [
        'table' => "users"
    ],
    
    // add new guardian
    'admin' => [
        'table' => "admins"
    ],
]
```

When you call `admin` guardian, `Auth` will work with `admins` table.

### Authenticating user login

```php
use App\Framework\Auth;
use App\Framework\Request;

class AdminController {
    public function login(Request $req) {
        $loggingIn = Auth::guard('admin')->attempt([
            'email' => $req->email,
            'password' => $req->password,
        ]);

        if (!$loggingIn) {
            return redirect(route('admin.loginPage'), [
                'errorMesage' => "Email or password doesn't match"
            ]);
        }

        return redirect(route('admin.dashboard'));
    }
}
```

your `password` value has automatically converted to md5 encrypted string. So you have to encrypt the password to md5 in registration process

### Retrieve logged in user data

```php
class AdminController {
    public function dashboard() {
        $me = Auth::guard('admin')->user();
    }
}
```

### Check if user has logged in or not

```
<?php

use App\Framework\Auth;

?>

<nav>
    <?php if (Auth::guard('user)->check()) : ?>
        Hi, <?= Auth::guard('user')->user()->name ?>
    <?php endif ?>
</nav>
```

### Logging out user

```php
class AdminController {
    public function logout() {
        $loggingOut = Auth::guard('admin')->logout();
        return redirect(route('admin.loginPage'));
    }
}
```