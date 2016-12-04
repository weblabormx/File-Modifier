Masive-Modifier
======

This class helps you to make changes in an specified folder or in a list of files. You can use functions from `FileModifier` class.
```php
use WeblaborMX\FileModifier\MasiveModifier;
```

## Constructor
This will teach you how to initialize Masive Modifier.

### Files
To add a list of files you will need to call `MasiveModifier` and the array of the files to edit.
```php
$files = ['msg1.txt', 'msg2.txt'];
MasiveModifier::files($files);
```

### Directory
To add a single directory you should use the next code:
```php
MasiveModifier::directory('folder_name');
```
Or if you want a list of directories:
```php
$files = ['dir1', 'dir2'];
MasiveModifier::directories($files');
```

## How to use it
```php
MasiveModifier::directory($directory)->execute(function($fileModifier) {
    $fileModifier->replace('Hi', 'GoodBye');
});
```
Inside of the function you will call any function in `FileModifier`. It is not necessary to use the execute function, it  will be launched automatically.