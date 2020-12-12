# Authentication

You don't have to write and manage session manually for users authentication, just use Auth helper to do that. You 

## Set the guardians

Before using Auth, make sure you had insert new guardian in `config/auth.php` with following this format.

```php
'guards' => [
    '{GuardianName}' => [
        'table' => "{TableName}"
    ],
]
```

The default guardian is `user` and already written in configuration as default.

## Authenticate user login

```php
// Add this to outside of controller's class
use App\Framework\Auth;

public function login(Request $request) {
    $loggingIn = Auth::guard('user')->attempt([
        'email' => $request->email,
        'password' => $request->password
    ]);
}
```