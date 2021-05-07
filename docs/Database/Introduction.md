# Database

Bidhan has an ORM (Object-relational Mapper) like Eloquent (Laravel) that will make your code more beauty when interact with database. Every time you play with database you must have a Model for each tables.

### What is Model?

Model in this framework has not same meaning as Codeigniter model, instead it just configuration of your query like defining table name and relationship.

### Generating Model

To create model for each table, just run this command

```
php canyou make:model User
```

that command will created a model named `User` in `/app/Models/`.

#### Define table name

After your model has been generated, perhaps you need to define your table name manually due to Bidhan system not as smart as Laravel yet.

```php
class User extends Model {
    protected $table = "users";
}
```

but if your table has only one word, it's okay to not define table name, Bidhan will understand your table name automatically based on your model name.

For example if you generate `Product`, the table name is `products`. Or `Company`, the table name will be `companies`