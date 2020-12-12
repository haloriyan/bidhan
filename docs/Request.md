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