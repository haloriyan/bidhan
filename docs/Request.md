# Request

Every request that you run had included Request helper and you just need to take it on parameter in controller

```php
class UserController {
    public function register(Request $request) {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        // another register process here
    }
}
```

### Where the request properties came from?

Of course it came from input name in your form.

```html
<form action="<?= route('register')" method="post">
    <div>Your name :</div>
    <input type="text" name="name">
    <div>Email :</div>
    <input type="email" name="email">
    <div>Password :</div>
    <input type="password" name="password">
</form>
```

And, you can also do that in GET method.

## Handling File from Request

You can handle file and put into your `storage/` directory via Request. But at first you need to retrieve the request and store in a variable

```php
public function store(Request $request) {
    $photo = $request->file('photo');
}
```

and here APIs that you can use with your `$photo` variable.

|Method|Argument|
|------|--------|
|store|$path `string`, $filename `string` `array` `optional`|
|getFileName|`null`|
|getFileSize|$unit (KB, MB, or GB)|
|getFileExtension|`null`|

###  Code example for uploading a file

```php
public function store(Request $request) {
    $photo = $request->file('photo');
    $photoFileName = time() . "_" . $photo->getFileName();
    
    // Upload process
    $photo->store('user_photo', $photoFileName);
    // If you didn't fill second argument, then it will use it's default file name automatically
}
```

## What if I want to upload multiple file?

You need to add square bracket in name of input file in your form.

```html
<input type="file" name="photos[]">
<input type="file" name="photos[]">
<input type="file" name="photos[]">
```

and when you call 3 get method you will get return array with the order of it's index will same as your form input order. For example

```php
public function store(Request $request) {
    $photos = $request->file('photos');
    echo $photos->getFileName(); // ["FirstFile.jpg","SecondFile.jpg","ThirdFile.jpg"]
    echo $photos->getFileSize(); // [55082,8920,32500]
    echo $photos->getFileExtension(); // ["jpg","jpg","jpg"]

    $photos->store('uploaded_photos', ["FotoPertama.jpg","FotoKedua.jpg","FotoKetiga.jpg"]);
    // If you didn't fill second argument, then it will use it's default file name automatically
}
```