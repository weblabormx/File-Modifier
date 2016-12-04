File-Modifier
======

Main Function of File-Modifier library, this class will help you to modify files
```php
use WeblaborMX\FileModifier\FileModifier;
```

## Basics
To edit a file you will need to call FileModifier and the direction of the file to edit.
```php
FileModifier::file('file.php');
```

### Execute
We need the function `execute()` to execute the modifications.
```php
->execute();        // It will modify the file
->execute(false);   // It will return the posible modifications but without doing it.
```

### First
If we want only the first result we will use first
```php
->first();        // It will modify the file
->first(false);   // It will return the posible modifications but without doing it.
```

### Exists
Used to know if a file exists, will return a boolean
```php
FileModifier::file($this->file)->exists();
```

### Create
Used to create a new file
```php
FileModifier::file($this->file)->create('Hola Mundo');
```

### Count
If we want the quantity of results gotten, if we do only one action will return the number of changes made, if we execute more actions will return the quantity of actions made successfully
```php
// This will return the number of lines that have 'user' string
FileModifier::file($this->file)->find('user')->count();
```

### Do actions in certain lines
If you want to make actions based in the number of lines we should use in the next way. False is used only in one variable functions.
```php
$array = array(
    'lines' => array(
        'starts'    => 3,
        'finish'    => 8
    ), 
);
// This will return the first gotten result
// One variable function
FileModifier::file($this->file)->find('user', false, $array)->first(); 
// Two variable function
FileModifier::file($this->file)->replace('user', 'usuario', $array)->first(); 
```

### Make actions in certain position
If you want to get for example the second result or modify the third result this will help you.
```php
$array = array(
    'pos'   => 2 // Only the second position
);
// One variable function
FileModifier::file($this->file)->find('user', false, $array)->first(); 
// Two variable function
FileModifier::file($this->file)->replace('user', 'usuario', $array)->first(); 
```

### Make actions in certain position and in certain lines
You can combine both features!
```php
$array = array(
    'lines' => array(
        'starts'    => 3,
        'finish'    => 8
    ), 
    'pos'   => 2 // Only the second position
);
// One variable function
FileModifier::file($this->file)->find('user', false, $array)->first(); 
// Two variable function
FileModifier::file($this->file)->replace('user', 'usuario', $array)->first(); 
```
To send the array in `getIfLines`, `getFunctionInit` and in `getFunctionLines` function you will need to pass it in the second attribute of the function.
```php
$array = array(
    'pos'   => 2
);
$res = FileModifier::file($this->file)->getIfLines("if(true)", $array); 
```

## Reading the file

### Single search
To search the word `user` we need the next code.
```php
$return = FileModifier::file('file.php')->find('user')->execute();
```
This example will return multiple objects, that are the results of the search
```php
foreach($return as $object) {
    $object->value; // The value of the line
    $object->line; // The number of line
} 
```

### Multiple search
`Find` function accepts an array as parameter
```php
$search = array("user","1");
$return = FileModifier::file('file.php')->find($search)->execute();
```
Returns an array with the number of gotten results.
```php
$return = array(
    'user' => array(
        ...
    ),
    '1' => array(
        ...
    )
);
```

### Find at the beginning
To search the word `user` but that appears just at the beginning
```php
FileModifier::file($this->file)->findAtBeginning($search)->first();
```

### Get line
To know information about the line in a file
```php
FileModifier::file('file.php')->getLine($line)->first();
```

### A File exists
To check if a file exists you should execute the next code. Returns a boolean.
```php
$exists = FileModifier::file('file.php')->exists();
```

### Count number of lines
To get the total lines or number of the end line. Returns a number.
```php
FileModifier::file('file.php')->count();
```

### Get the line of a search
if you want to know the line where `user` appears you need to use:
```php
FileModifier::file('file.php')->find('user')->first()->line;
```
It will give the first word found. Returns a number.

### Get the line where a function begins
if you are modifying code files this could help you.You add the name of the function and the line where it starts will be given. If a function have comments before it, it will give you the line before the comment.
- Example of `function` value: `name()`
```php
FileModifier::file('file.php')->getFunctionInit($function");
```

### Get the lines of a function
This will give you the first and last line of a function.
```php
FileModifier::file('file.php')->getFunctionLines($function");
```
Returns an array with `starts` and `finish` values.

### Get the lines of an if
This will give you the first and last line of an if.
```php
FileModifier::file('file.php')->getIfLines("if(true)");
```
Returns an array with `starts` and `finish` values.

## Modifying the file

### Replace a word
To search the word `user` and replace it for `guest`, it will change all the results.
```php
FileModifier::file('file.php')->replace('user', 'guest')->execute();
```
returns an array with objects about changes made or `false` if the word was not founded

### Replace a line
Used to change a full line.
- `$search`  What you are looking in the line.
- `$replacement` What you want to put in the line
```php
FileModifier::file('file.php')->replaceLineWhere($search, $replacement)->execute();
```

### Add a line before a keyword
Add a line before the line of keyword searched
- `$search`  What you are looking in the line.
- `$replacement` What you want to put in the line before.
```php
FileModifier::file('file.php')->addBeforeLine($search, $replacement)->execute();
```

### Add a line after  a keyword
The same that `addBeforeLine`but instead adding the line before is added after.
- `$search`  What you are looking in the line.
- `$replacement` What you want to put in the line after
```php
FileModifier::file('file.php')->addAfterLine($search, $replacement)->execute();
```

### Add a line before a line
The same that `addBeforeLine`but instead searching a word you search the number of line
- `$search`  The line
- `$replacement` What you want to put in the line before.
```php
FileModifier::file('file.php')->addBeforeLineByLine($search, $replacement)->execute();
```

### Add a line after a line
The same that `addBeforeLineByLine`but instead searching a word you search the number of line
- `$search`  The line
- `$replacement` What you want to put in the line before.
```php
FileModifier::file('file.php')->addAfterLineByLine($search, $replacement)->execute();
```

### Add at the end of the file
Add a line at the last line of the file
- `$addition` Information of the line
```php
FileModifier::file('file.php')->addAtTheEnd($addition)->execute();
```

### Modify a line
Change the information of a line
- `$line` Number of line
- `$change` New information
```php
FileModifier::file('file.php')->changeLine($line, $change)->execute();
```

### Remove a line
Remove a line, needs `$line` (Number of line)
```php
FileModifier::file('file.php')->removeLine($line)->execute();
```

### Remove a line where
Remove a line where there is a keyword, needs `$search` 
```php
FileModifier::file('file.php')->removeLineWhere($search)->execute();
```

### Remove lines where keywords
Remove some lines where between `$start` and `$finish` keywords.
```php
FileModifier::file('file.php')->removeLinesWhere($start, $finish)->execute();
```

### Remove lines between lines
Remove the selected lines between two lines: `$start` and `$finish`. If $start is 4 and $finish  is 10 will delete line 4, 5, 6, 7, 8, 9 and 10.
```php
FileModifier::file('file.php')->removeLinesBetweenLines($start, $finish)->execute();
```

### Remove function
Remove the lines where exists a functiom
```php
FileModifier::file('file.php')->removeFunction($function)->execute();
```

### Do multiple actions at the same time
If you want to execute a lot of functions at the same time you can do it.
```php
FileModifier::file('file.php')
    ->replace("user","usuario1")
    ->addBeforeLine("florencio","beforeflorencio, nada") 
    ->addAtTheEnd("Copyright") 
    ->execute();
```
It returns an array with all the actions made.

### Return example
```php
array(
    0 => array(
        'action'    => 'replace',
        'lineNum'   => 1,
        'lineOld'   => 'user1,pass1',
        'lineNew'   => 'usuario11,pass1',
        'search'    => 'user',
        'val2'      => 'usuario1'
    )
);
```