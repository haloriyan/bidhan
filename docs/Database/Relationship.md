# Relationship

We have 3 relationship type

- hasOne (one to one)
- hasMany (one to many)
- belongsTo (many to one)

if we don't understand the difference between these 3 type, please look at the Laravel documentation.


### hasOne

```php
// Model
class User extends Model {
    public function life() {
        return self::hasOne('App\Models\Life', 'user_id');
    }
}

// query in controller
$users = User::with('life')->get();
```

Result :

```json
[
    {
        id: 1,
        name: "Riyan",
        life: {
            id: 1,
            columnName: "value"
        }
    }
]
```

or if we need the n to many relationship, we could use `hasMany` instead.

### hasMany

```php
// Model
class User extends Model {
    public function pets() {
        return self::hasMany('App\Models\Life', 'user_id');
    }
}

// query in controller
$users = User::with('pets')->get();
```

we will get this result

```json
[
    {
        id: 1,
        name: "Riyan",
        pets: [
            {id: 1, name: "Cat"},
            {id: 2, name: "Dog"},
            {id: 3, name: "Bird"},
        ]
    }
]
```

### belongsTo

This relation type is used to get the author of data. From example above, first we getting the pets and then we grab the owner data.

```php
// Model
class Pet extends Model {
    public function owner() {
        return self::belongsTo('App\Models\User', 'user_id');
    }
}

// query in controller
$pets = Pet::with('owner')->get();
```

and here is the result

```json
[
    {
        id: 1,
        name: "Dog",
        owner: {
            id: 1,
            name: "Riyan"
        }
    },
    {
        id: 1,
        name: "Cat",
        owner: {
            id: 1,
            name: "Riyan"
        }
    }
]
```

## Multiple Relationship

In the name of code efficiency, we should run all the required query at once. For example we have `users` table, and we want to get `posts` and `comments` of **a user**.

|Table|=>|Table|
|------|--|----|
|users|hasMany|posts|
|users|hasMany|comments|

We should not to running 2 query that we getting all posts comments with `where user_id = n` query. Instead we could just run this in one line.

```php
// Model User.php
class User extends Model {
    public function posts() {
        return self::hasMany('App\Models\Post', 'user_id');
    }
    public function comments() {
        return self::hasMany('App\Models\Post', 'user_id');
    }
}

// UserController
public function userProfile($id) {
    $user = User::where('id', $id)->with(['posts','comments'])->first();
}
```

And here is the result

```json
{
    id: 1,
    name: "Riyan",
    posts: [
        {
            id: 1,
            user_id: 1,
            title: "Learning Bidhan for Beginner"
        },
        {
            id: 2,
            user_id: 1,
            title: "How to Become a Good Programmer"
        }
    ],
    comments: [
        {
            id: 1,
            user_id: 1,
            post_id: 1,
            body: "This is so helpful"
        },
        {
            id: 2,
            user_id: 1,
            post_id: 4,
            body: "Wow, nice insight for me"
        }
    ]
}
```

## Nested Relationship

Sometimes we need 3 tables at once in one query. For example we have `users`, `posts`, and `comments`.

|Table|=>|Table|=>|Table|
|----|-----|----|----|-----|
|users|hasMany|posts|hasMany|comments|

So first we define the relationship in User and Post model.

```php
// User.php
class User extends Model {
    public function posts() {
        return self::hasMany('App\Models\Post', 'user_id');
    }
}

// Post.php
class Post extends Model {
    public function comments() {
        return self::hasMany('App\Models\Comment', 'post_id');
    }
}
```

Then we call these two relations with `with()` and connected with dot `.`

```php
class UserController {
    public function index() {
        $users = User::with('posts.comments')->get();
    }
}
```

And we will get this result

```json
[
    {
        id: 1,
        name: "Riyan",
        posts: [
            {
                id: 1,
                title: "Learning Bidhan for Beginner",
                comments: [
                    {
                        id: 1,
                        user_id: 255,
                        body: "This article is suck!"
                    },
                    {
                        id: 2,
                        user_id: 318,
                        body: "Such a garbage"
                    }
                ]
            }
        ]
    }
]
```