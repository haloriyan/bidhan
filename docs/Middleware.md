# Middleware

Middleware is process that mediates between user request and response that will send to user. Mostly used for checking has user logged in, or checking user role restriction.

## Create new middleware

Create new file inside of `/app/Middleware/` with middleware name as filename. For example we create `User` middleware, and obviously the filename should be `User.php`. Here is base code of middleware

```php
<?php

namespace App\Middleware;

class User {
    public function handle() {
        // Middleware process goes here
    }
}
```

### Checking user if has logged in

```php
<?php

namespace App\Middleware;

// We need to insert Auth helper
use App\Framework\Auth;

class User {
    public function handle() {
        // if user has not logged in, then redirect to route 'login'
        if (!Auth::check()) {
            redirect('login', [
                'message' => "We are sorry but you have to login first before accessing this page"
            ]);
        }
    }
}
```

## Activate the middleware

For now, we can activating middleware only from Controller via constructor.

```php
// Insert Middleware helper
use App\Framework\Middleware;

class UserController {
    public function __construct() {
        Middleware::use('User');
    }
}
```

### What if I need some methods which doesn't need middleware?

You can add second argumen in `use()` for exclude some methods which doesn't need middleware

```php
class UserController {
    public function __construct() {
        Middleware::use('User', ['withoutMiddleware']);
    }
    public function withoutMiddleware() {
        echo "I'M FREE!";
    }
}
```