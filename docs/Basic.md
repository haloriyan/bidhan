# Core function

Core function mean any function that you can help you in any place, either controller or view.

### view($location `string`, $variableToPass `array`)

Returning your request with view file which located in /views/. This function also can be used in mailing system for attach the HTML view as body's email.


```php
// controller
public function dashboardPage() {
    return view('dashboard', [
        'user' => $user;
    ]);
}

// inside /views/dashboard.php
<h1>Hello, <?= $user->name ?>!</h1>

```

### route($path `string`)
Get full URL from your defined route in `config/routes.php`

```php
// /config/routes.php
return [
    'user/login' => "POST:UserController@login"
];

// /views/login.php
<form action="<?= route('user/login') ?>">
```

for complete information please check Router.md file

### redirect($routePath `string`, $parameters `array`)

Redirect user into another route and if it needed, you can also pass GET parameters that will be appear once.

```php
public function login() {
    if ($loggingIn) {
        redirect('user/login', [
            'errorMessage' => "Wrong email and/or password"
        ]);
    }
}
```

then you can showing it in view
```html
<li><?= $errorMessage ?></li>
```

### insert()

You don't have to write many codes multiple time. Create it into any folder (like "partials/") and insert into your view as it need. And yes, this could passing variable too.

```php
<!-- /views/partials/Header.php -->
<header>
    <h1><?= $site_title ?></h1>
</header>

<!-- /views/dashboard.php -->
<body>

<?php
insert('./partials/Header', [
    'site_title' => "Awesome Website"
]);
?>

</body>
```
