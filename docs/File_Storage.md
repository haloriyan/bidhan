# File Storage

You don't need to think about how files going to stored into your website. Because this framework handled that stuff. Before we go, please add Storage helper into your Controller.

```php
use App\Framework\Storage;
```

Then, let's start with choose the "disk" thing.

## disk($name `string`)

First of all, we need to define what disk will used. Disk mean folders are located in `/storage/`. If it doesn't exists, it will created automatically. For example, we write "avatars", a folder to store avatar image of user profile when they registering in your site.

```php
public function register() {
    Storage::disk('avatars')
}
```

## store($subFolder `string`, $_FILES['fileName']) (depracated)

> This method has been depracated. Please use file() on Request instead.

After define the disk that will be used, now we can store any file into it using `store()` method.

```php
public function register() {
    $avatar = $_FILES['avatar'];
    Storage::disk('avatars')->store('/', $avatar);
}
```

First argument is sub-folder where we will store the avatar image. If it doesn't need subfolder, just fill it with '/' and files will stored right in /storage/avatars/. And the second argument, is the file which stored in $_FILES. Please remember in this framework version, file handler not covered by [Request](Request.md) yet. So you stil need $_FILES variable PHP built-in.

## delete($filePath `string`)

If the user changed their avatar, maybe you need to delete the old file due to storage usage. Use `delete()` method to delete any file in your disk.

```php
public function changeAvatar() {
    $oldAvatarFileName = 'aang.png';
    
    $deleteOldAvatar = Storage::disk('avatars')->delete($oldAvatarFilename);
}
```

## Moving file

Nor you don't want to delete file and doing soft delete instead, you can use the `move()` method to your trash directory.

```php
public function changeAvatar() {
    $filename = "aang.png";
    $softDelete = Storage::disk('avatars')->move($filename, "../trash/".$filename);
}
```

For make it clear, here the directory structure

```
- storage/
--- avatars/
------ aang.png
--- trash/
```