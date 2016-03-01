File-Modifier
======

PHP Package for modifing single files. With this package you would have the posibility to modify a lot of files, talking about text files (.txt, .php, .json, .js, etc)

### Features

- Replace text by searching
- Detects programming code like functions and if

## Installation

- With composer run `composer require weblabormx/file-modifier` 

## Documentation

### Basics
First to use the package you will need to add the namespace:
```php
use \WeblaborMX\FileModifier\FileModifier;
use \WeblaborMX\FileModifier\Helper;
```
To edit a file you will need to call FileModifier and the direction of the file to edit.
```php
FileModifier::file('file.php');
```

We need the function `execute()` to execute the modifications.
```php
->execute();        // It will modify the file
->execute(false);   // It will return the posible modifications but without doing it.
```
### Reading the file
#### Search in a file
To search the word `user` we need the next code.
```php
$return = FileModifier::file('file.php')->find('user')->execute();
```

`Find` function accepts an array as parameter
```php
$search = array("user","1");
$return = FileModifier::file('file.php')->find($search)->execute();
```
##### Returns
Returns an array with the number of gotten results, each result with the number of line and the actual content of the line.
#### A File exists
To check if a file exists you should execute the next code. Returns a boolean.
```php
$exists = FileModifier::file('file.php')->exists();
```
#### Count number of lines
To get the total lines or number of the end line. Returns a number.
```php
FileModifier::file('file.php')->count();
```
#### Get the line of a search
if you want to know the line where `user` appears you need to use:
```php
FileModifier::file('file.php')->getLineWhere('user');
```
It will give the first word found. Returns a number.
#### Get the line where a function begins
if you are modifying code files this could help you.You add the name of the function and the line where it starts will be given. If a function have comments before it, it will give you the line before the comment.
- Example of `function` value: `name()`
```php
FileModifier::file('file.php')->getFunctionInit($function");
```
#### Get the lines of a function
This will give you the first and last line of a function.
```php
FileModifier::file('file.php')->getFunctionLines($function");
```
Returns an array with `starts` and `finish` values.
#### Get the lines of an if
This will give you the first and last line of an if.
```php
FileModifier::file('file.php')->getIfLines("if(true)");
```
Returns an array with `starts` and `finish` values.
### Modifying the file
#### Replace a word
To search the word `user` and replace it for `guest`, it will change all the results.
```php
FileModifier::file('file.php')->replace('user', 'guest')->execute();
```
#### Replace a line
Used to change a full line.
- `$search`  What you are looking in the line.
- `$replacement` What you want to put in the line
```php
FileModifier::file('file.php')->replaceLineWhere($search, $replacement)->execute();
```
#### Add a line before a keyword
Add a line before the line of keyword searched
- `$search`  What you are looking in the line.
- `$replacement` What you want to put in the line before.
```php
FileModifier::file('file.php')->addBeforeLine($search, $replacement)->execute();
```
#### Add a line before a line
The same that `addBeforeLine`but instead searching a word you search the number of line
- `$search`  The line
- `$replacement` What you want to put in the line before.
```php
FileModifier::file('file.php')->addBeforeLineByLine($search, $replacement)->execute();
```
#### Add a line after  a keyword
The same that `addBeforeLine`but instead adding the line before is added after.
- `$search`  What you are looking in the line.
- `$replacement` What you want to put in the line after
```php
FileModifier::file('file.php')->addAfterLine($search, $replacement)->execute();
```
#### Add at the end of the file
Add a line at the last line of the file
- `$addition` Information of the line
```php
FileModifier::file('file.php')->addAtTheEnd($addition)->execute();
```
#### Modify a line
Change the information of a line
- `$line` Number of line
- `$change` New information
```php
FileModifier::file('file.php')->changeLine($line, $change)->execute();
```
#### Do multiple actions at the same time
If you want to execute a lot of functions at the same time you can do it.
```php
FileModifier::file('file.php')
	->replace("user","usuario1")
	->addBeforeLine("florencio","beforeflorencio, nada") 
	->addAtTheEnd("Copyright") 
	->execute();
```
It returns an array with all the actions made.
## Helper Class
The Helper Class has functions that helps with the programming.
```php
// To search if begins with a select string
Helper::startsWith( 'hola-mundo.php', 'hola' )  // true
// If ends with some string
Helper::endsWith( 'hola-mundo.php', '.php' )    // true
// If has string
Helper::hasString( 'hola-mundo.php', 'mundo' )  // true
```