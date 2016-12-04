Helper Class
======

The Helper Class has functions that helps with the programming.

### Strings
```php
// To search if begins with a select string
Helper::startsWith( 'hola-mundo.php', 'hola' );  // true
// If ends with some string
Helper::endsWith( 'hola-mundo.php', '.php' );    // true
// If has string
Helper::hasString( 'hola-mundo.php', 'mundo' );  // true
```

### Directories
```php
// To move a folder from ubication
Helper::moveFolder( $from, $to ); 
// To copy a folder from ubication
Helper::copyFolder( $from, $to ); 
// To create a folder
Helper::newFolder( $name ); 
// To remove a folder
Helper::removeFolder( $name ); 
```