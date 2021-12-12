# CRUD

The core of application obviously CRUD, stands for `Create`, `Read`, `Update`, and `Delete`. And before we get started, make sure you have read `/docs/Database/Introduction.md` to understand about database and model.

And also make sure you have included your model inside controller.

```php
use App\Models\Product;
```

### Create

```php

class ProductController {
    public function store(Request $request)  {
        $createProduct = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'available_stock' => $request->available_stock,
        ]);
    }
}
```

You don't have to define `$fillable` property in `Product` model. But you need to remember what field is required to be filled or you will get mysql error on console.

### Read

Basically we have two methods to reading data, `first()` and `get()`.  The `first` method will return single collection, and `get` method will return array collection.


```php
class ProductController {
    public function getProducts() {
        return Product::get();
    }
    public function getProductById($productID) {
        return Product::where('id', $productID)->first();
    }
}
```
Result of `get()` :

```json
[
    {
        id: 1,
        name: "iPhone 4s",
        price: 199
    },
    {
        id: 1,
        name: "Playstation 5",
        price: 399
    },
]
```

Result of `first()` :

```json
{
    id: 1,
    name: "iPhone 4s",
    price: 199
}
```

### Update

```php
public function updateProduct($productID) {
    $updating = Product::where('id', $productID)->update([
        'name' => "New name"
    ]);
}
```

`update` method will returning boolean result of it's query

### Delete

```php
public function deleteProduct($productID) {
    $deleting = Product::where('id', $productID)->delete();
}
```

same as `update`, `delete` method will return boolean result