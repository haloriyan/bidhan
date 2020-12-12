# Mailing System

This framework have included default library that will help you to send emails. And for core mailer, PHPMailer will help you well.

## Calling Helper

Before you can use the mailing system, you have to call the helper via use keyword in your controller.

```php
namespace App\Controllers;

// add this outside the class
use App\Framework\Mailer;

class UserController {
    // controller's processes
}
```

## Setting up environment

Open /.env and change any configs about mailing system with MAIL_ prefix as your email configuration.

```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=somebody@gmail.com
MAIL_PASSWORD=supersecretpassword
MAIL_ENCRYPTION=ssl
```

## Define recipient

Use to() method for define your recipient information

```php
public function register() {
    // register process

    Mailer::to($email, $name)
}
```

## Set whom sent the email

You can write alias name for email's sender as you need, it can be your application name or even your name.

```php
Mailer::to($email, $name)
->from('no-reply@gmail.com', 'Awesome Mailing System')
```

## Write Subject

Some email need a subject, some email doesn't, and it will going to spam folder. Don't you want your email seen by your recipient instead going to spam? Then write the subject

```php
Mailer::to($email, $name)
->from()
->subject("WELCOME TO THE APP!")
```

## The Body

No one email was sent with empty body, even spam email has body. You can write body email with html (and inline css for styling), and call it by view() in send().

```php
Mailer::to($email, $name)
->from()
->subject("WELCOME TO THE APP!")
->send(
    view('email/successRegister', [
        'name' => $name
    ])
);
```

and here the /views/email/successRegister.php

```html
<p>Hello, <?= $name ?></p>

<p style="background: red;">Welcome to the app and thank's for joining with us</p>
```
