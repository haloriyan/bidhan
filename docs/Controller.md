# Controller

Controller is the place where you do any necesary process before the datas shown to view. Please open your google if you still did not understand about controller.

### Generating controller

Every you need new controller, just generate it by running `canyou` command

> php canyou make:controller UserController

then your new controller will appear in `/app/Controllers/`

### Collaborate with other controller

The bidhan system has been implemented with autoloading all your controllers. You just need to add `use` keyword to call the controller you need.

```php
use App\Controllers\BookController;

class UserController {
    public function myBooks() {
        $myBooks = BookController::mine();
    }
}
```