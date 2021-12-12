# Database

Bidhan has an ORM (Object-relational Mapper) like Eloquent (Laravel) that will make your code more beauty when interact with database. Every time you play with database you must have a Model for each tables.

## What is Model?

Model in this framework has not same meaning as Codeigniter model, instead it just configuration of your query like defining table name and relationship.

## Generating Model

To create model for each table, just run this command

```
php canyou make:model User
```

that command will created a model file named `User.php` in `/app/Models/`.

## Defining table name

Bidhan will recognize your table name as your model's name with snake_case naming convention. It also could auto-renamed your model into plural form.

For example, if you generated model named `Company`, Bidhan will define your table name as `companies`. Or if containing 2 words, it would be `client_companies` if the model's name is `ClientCompany`.

Or you could also overriding table name as your choice. Like if your table name is `clientcompany` (which is not recomended for clean code), you could define this table name inside your model file (`app\Models\ClientCompany.php`)

```php
class ClientCompany extends Model {
    protected $table = "clientcompany";
}
```

## Hiding specific column

Some kind of column might not to visible to end-user such as password or auth token. To prevent data breach, define what column that cannot retrieved in controller and view by fill it in `$hidden` property.

```php
class User extends Model {
    protected $hidden = [
        'password','authentication_token'
    ];
}
```

When you calling this model to get user data, you will not receive column that has defined in `$hidden` property.