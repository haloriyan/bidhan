# Relationship

We have 3 relationship type

- hasOne
- hasMany
- belongsTo

if you don't understand the difference between these 3 type, please go to Laravel documentation.


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