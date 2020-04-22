Helper Class
======

The Helper Class has functions that helps with the programming.
```php
use WeblaborMX\FileModifier\Helper;
```

## Strings
```php
// To search if begins with a select string
Helper::startsWith( 'hola-mundo.php', 'hola' );  // true
// If ends with some string
Helper::endsWith( 'hola-mundo.php', '.php' );    // true
// If has string
Helper::hasString( 'hola-mundo.php', 'mundo' );  // true
```

## Folder
Will help you to know information about a folder.

### Attributes
```php
$folder = Helper::folder($directory); 
$folder->count();             // Total files in folder (Int    )
$folder->total_subfolders();  // Total subfolders (Int)
$folder->exists();            // Check if exists (Boolean)
$folder->files();             // List of files inside (Array)
$folder->directories();       // List of directories inside (Array)
```

### Functions
```php
// To move (or rename) a folder from ubication
Helper::folder( $directory )->moveTo( $to ); 
// To copy a folder from ubication
Helper::folder( $directory )->copyTo( $to ); 
// To create a folder
Helper::folder( $directory )->create(); 
// To remove a folder
Helper::folder( $directory )->remove(); 
```
