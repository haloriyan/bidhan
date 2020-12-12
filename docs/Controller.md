# Controller

Like another framework, controller is the place where you doing any process that needed before shown in view. Because many framework has controller system, it will not much text in here.

## Create new Controller

You have to create a new file manually into /app/Controllers/ directory. For name, we follow laravel controller namign convention, use PascalCase and ended with "Controller" word. Example : UserController and obviously with .php file extension.

Basic controller's script :
```php
<?php

namespace App\Controllers;

class UserController {
    //
}

```

## Call the other controller

If you need another controller inside your controller, you can call them with `use` keyword.

```php
<?php

namespace App\Controllers;

use App\Controllers\UserController as UserCtrl;

class PostController {
    public function publish() {
        /*
         * another publishing process here
         */

        $isUserCanPosting = UserCtrl::postingAbility($user->id);
        if (!$isUserCanPosting) {
            redirect('dashboard', [
                'message' => "Sorry you don't have ability to publishing post"
            ]);
        }
    }
}

```

You can use alias for your controller or not depends on your preference.