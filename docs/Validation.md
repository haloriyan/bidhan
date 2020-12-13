# Validation

Validation helper is part of Request helper. So you only need to insert Request helper into your controller

```php
use App\Framework\Request;
```

and then run `validate()` method in first of your controller's method

|Method|Arguments|
|------|---------|
|validate|[$input_name => $rules] `array`|

```php
public function register(Request $request) {
    $validateData = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6|max:18'
    ]);
}
```

## Available Rules

|Rules|Value|Functionality|
|-----|-----|-------------|
|required||Input is not empty|
|email||Input is valid email address|
|url||Input is valid URL address|
|min|`integer`|Input is not less than value|
|max|`integer`|Input is not more than value|

You can add multiple rules separated by `|`

## Showing Errors Message

All errors message were stored in `$errors` variable. You can take it from view or controller.

```php
<?php foreach ($errors as $error) : ?>
    <li><?= $error ?></li>
<?php endforeach ?>
```

## Customize Error Messages

Errors message template stored in `/config/validation_message.php`. You can customize the message with your preference or local app's language. Variable usage has written in that file, please don't delete the comments in case you forgot how to insert variable in message.