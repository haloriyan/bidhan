# Pagination

Bidhan has default paginator that will help us to limit the datas to be shown. It is part of ORM Database. For the link view, we could use Bootsrap, Materialize CSS, Bulma, or Foundation CSS framework.

## Basic Usage

Let say we have a `users` table. We want to display the datas 10 by 10 per page. After configured model that has explained in [Introduction](./Introduction.md), let's start writing the query.

```php
<?php

use App\Models\User;

class AdminController {
    public function user() {
        $users = User::paginate(10);
        return view('user', ['users' => $users]);
    }
}
```

## Display the data

In the `views/user.php` file, we need to use `foreach` to display 10 elements of users data.

```php
<?php foreach ($users->get() as $user) : ?>
    <li><?= $user->name ?></li>
<?php endforeach; ?>
```

One important thing for us to remember, we have to add `get()` method after writing `$users` object due to the query did not run yet. Our query will executed as `get()` method called.

## Show pagination link

Every pagination has it links to navigate between pages. In order to show the pagination link, we just have to call `links()` method in our view.

```php
<?php foreach ($users->get() as $user) : ?>
    // The user data display code goes here
<?php endforeach; ?>

// Showing pagination link
<?= $users->links() ?>
```

## Using different CSS Framework

By default, it used Bootstrap as it's CSS class. But if you need to use different CSS Framework such as Bulma or Materialize, just change it by passing as `links()` argument.

```php
<?= $users->links('bulma') ?>
// or
<?= $users->links('materialize') ?>
```

Bidhan has supported Bootstrap, Bulma, Materialize, and Foundation for pagination link.