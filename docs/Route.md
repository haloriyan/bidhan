# Route

## Basic routing

The most simple way to route is give the URI on first argument, and a closure for the action that will run

```php
use App\Framework\Route;

Route::get('/home', function () {
    echo "Hello bidhan";
});
```

Of course you also able call your controller instead of  a closure

```php
Route::get('/home', "UserController@home");
```

## HTTP Methods

Currently bidhan only retrieve 2 http methods, `GET` and `POST`. Use `post` method instead if you need POST method to your route.

```php
Route::get('/login', "UserController@login");
Route::post('/loginAction', "UserController@loginAction");
```

## Parameters

#### Required Parameters

When you need some parameters, just put the name between curly braces in your URI route

```php
Route::get('product/{id}', "ProductController@detail");
// or
Route::get('product/{id}', function ($productID) {
    echo "The product ID is " . $productID;
});
```

And if you need more than one parameter, just add more to URI and retrieve to your action. Remember the position of your parameter name in URI and action parameter, it has to be exactly same.

```php
Route::get('user/{userID}/product/{productID}', function ($userID, $productID) {
    echo "The product ID of user with ID " . $userID . " is " . $productID;
});
```

#### Optional Parameters

Just put question mark after parameter name in URI then set it's default value in action parameter

```php
Route::get('user/{id?}', function ($userID = 123) {
    echo "by default, userID is = " . $userID;
});
```

## Naming route

Tired to remember all URI's path? Give your route a name and just call it's name!

```php
Route::get('/profile', "UserController@profile")->name('profile');

// redirect to profile route
redirect(route('profile'));
```

> Every route cannot has a same name. Make sure it is unique when write the route name

#### Get the full URL

```html 
<a href="<?= route('profile') ?>">Go to profile</a>
```

## Groups

The `group` method help you manage all route with same prefix to locate in same place.

```php
Route::group(['prefix' => 'admin'], function () {
    Route::get('dashboard', "AdminController@dashboard")->name('admin.dashboard');
    Route::get('report', "AdminController@report")->name('admin.report');
});

/*
 * http://localhost:{PORT}/admin/dashboard
 * http://localhost:{PORT}/admin/report
 */
```

## Middleware

Middleware give you an ability to check every incoming request. For example, you may check if user has been logged in or not.

You have two options to use your middleware in router, defining on the `group` method or define in every route.

```php
Route::group(['prefix' => 'admin', 'middleware' => "Admin"], function () {
    Route::get('dashboard', "AdminController@dashboard")->name('admin.dashboard');
    Route::get('report', "AdminController@report")->name('admin.report');
});

// or like this

Route::get('profile', "UserController@profile")->name('profile')->middleware("Admin");
Route::get('write', "UserController@write")->name('write')->middleware("Admin");
```

Open `/docs/Middleware.md` to get more information about Middleware