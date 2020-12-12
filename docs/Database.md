# Database

Here you will learn any stuff that related to database system. This framework's core is using mysqli for execute all database query and tested in php7.2 & php7.3 with latest mysql-server version that running in Ubuntu machine.

First of all, you need to include DB helper by writing use keyword in controller.

```php
<?php

namespace App\Controllers;

// write this outside the class
use App\Framework\DB;

class UserController {
    //
}
```

## Query Builder

If you familiar with standard SQL Query (I mean the position of it keywords), you will get used to it.

### Starting Methods

Ok then we start with starting keyword, SELECT, DELETE, and UPDATE. But before you write that, you have to write which table that you want to use.

```php
$query = DB::table('users')->create()
$query = DB::table('users')->select()
$query = DB::table('users')->update()
$query = DB::table('users')->delete()
```

|Method|Argument|Example|
|-----|------|-------|
|create|array|`create(['name' => "Riyan"])`|
|select|multiple|`select('name', 'email')`|
|update|array|`update(['name' => "Riyan"])`|
|delete|null|`delete()`|

### where()

single condition

```php
->where('name', '=', 'Riyan')
```

multiple conditions

```php
->where([
    ['email', '=', 'riyan@thewebsite.com'],
    ['password', '=', 'supersecretPassword']
])
```

### another method that will be useful
|Method|Arguments|Functionality|Example
|----|-----|-----|---|
|query()|$query `string`|Run native SQL Query|`DB::query("SELECT * FROM users");`|
|toSql()|`null`|Return SQL Query that was built|`DB::table('users')->select()->where('id', '=', 1)->toSql();`|
|orderBy()|$column `string`, $sortingMode `string`, `optional`|Ordering data by column as ascending or descending|`DB::query("SELECT * FROM users");`|
|orWhere()|$filter `string`|Just like where(), but with OR keyword|`select()->where('name', '=', 'Riyan')->orWhere('name', '=', 'Satria');`|
|whereBetween()|$column `string`, $value `array`|where() with between keyword|`select()->whereBetween('age', [18, 25]);`|

### Execute query or retrieve data

If your query is using `create()`, `update()`, or `delete()` you need to end your query with `->execute()` method, that will run your query in mysqli_query(). But if query using `select()`, end your query with `->get()` or `->first()` instead.

## Relationship

```php
$foreign = [
    $idOnRelatedTable => $foreignKeyInCurrentTable
];

DB::table()->select()
->with('tableName', $foreign)
```

By default, this relationship was designed for one to many and many to many. And if you need * to one relationship, it will little tricky.

```php
public function getPosts() {
    $posts = DB::table('posts')
    ->select()
    ->with('users', [
        'id' => 'user_id'
    ])
    ->get();

    // usage example
    foreach ($posts as $post) {
        echo "<h2>Title : ".$post->title."</h2>";
        echo "<div>Posted by : ".$post->users[0]->name."</div>";
    }
}
```

due to `users` collection returned as single array value, you can call it first data directly.

## Pagination

You can limit data depends how much you need, by calling paginate() method in query builder.

```php
$datas = DB::table('posts')
->select()
->paginate(10); // without ->get() or ->first()

return view('user/posts', [
    'posts' => $datas
]);
```

Show datas on view
```php
<?php foreach ($posts->get() as $post) : ?>
    <div class="post-item">
        <h3><?= $post->title ?></h3>
    </div>
<?php endforeach ?>

<!-- Show navigation page -->
<?= $posts->links() ?>
```