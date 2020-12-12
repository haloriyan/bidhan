# Migration

Not like other framework, in here migration mean table definition. Only for defining table and it's content, without tracking changes. Define your database in `/migration.json`

## Setup table

Add new table by adding new object item inside `table` object

```
"table": {
    "users": [],
    "posts": []
}
```

#### Adding column

Based on example, each table's objects has value with array type and will used for defining it's column. In order to define column in table, you have to following this format

```
{ColumnName} {DataType}({Length}) {NullOrNotNull}
```

So it will look like this

```
"users": [
    "id int(11) not null",
    "name varchar(255) not null",
    "is_active varchar(11) null",
    "created_at timestamp not null
]
```

## Add Attribute

At least you will need two table attributes, PRIMARY and FOREIGN.

#### PRIMARY KEY

```
"attribute": {
    "PRIMARY": [
        "{TableName}.{Column}",
        "{AnotherTable}.{Column}"
    ]
}
```

#### FOREIGN KEY

```
"attribute": {
    "FOREIGN": [
        "{TableName}.{Column}=>{TableReferenceTo}.{ColumnReferenceTo}"
    ]
}
```

For example we have 2 tables, `users` and `posts` where `posts` containing `user_id` as reference to `id` on `users`. So we need to write the foreign key like this

```
"attribute": {
    "FOREIGN": [
        "posts.user_id=>users.id"
    ]
}
```

## Migrate!

When your migration is ready to migrate, run http://localhost:{PORT}/migrate.php in your browser and wait until page has processed all of your request.