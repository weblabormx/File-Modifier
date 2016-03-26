Analyze Files
======
This class helps you to make changes in an specified file. You can use functions from `FileModifier` function.
## Execute
### Basic example
```php
 $results = AnalyzeFiles::directory($this->directory)->execute(function($file) {
    $file->fileIs('sub1/sub1.php');
    $file->action(function($action) {
        $action->whereNoSearch('Carlos');
        $action->whereSearch('Jorge');
        $action->replace('Jorge', 'Carlos');
    });
    $file->action(function($action) {
        $action->whereSearch('Example1');
        $action->replace('Hi', 'Bye');
    });
});
```
### Returned array
```php
$result = array(
    'sub1/sub1.php' => array( // File name
        0 => array( // Number of action
            'result'        => true,
            'requirements'  => array(
                0 => array(
                    'function'    => 'NoSearch',
                    'value'       => 'Carlos',
                    'result'      => true
                ), 1 => array(
                    'function'    => 'Search',
                    'value'       => 'Jorge',
                    'result'      => true
                )
            ),
            'results' => array(
                0 => array(
                    'action'    => 'replace',
                    'lineNum'   => 1,
                    'lineOld'   => 'user1,pass1',
                    'lineNew'   => 'usuario11,pass1',
                    'search'    => 'user',
                    'val2'      => 'usuario1'
                )
            )
        )
    )
);
```
## Rules
This class will help you to know if some rules are being successfull or not.
### Basic example
```php
$results = AnalyzeFiles::rules()->directory($this->directory)->create(function($rules) {
    $rules->add(function($data) {
        $data->fileIs('example1.php');
        $data->validation(function($validation) {
            $count = 1;
            $validation->whereSearch('Carlos', $count); 
            $validation->whereSearch('Example1'); // Without $count only search that the search exists minimum once
        });
        
    });
    $rules->add(function($data) {
        $data->fileIs('example2.php');
        $data->validation(function($validation) {
            $count = 2;
            $validation->whereSearch('Carlos',$count);
        });
    });
});
```
### Directory
We can use some different ways to select the directory (or directories)
```php
AnalyzeFiles::rules()->directory('examples/folder') // Just one directory
```
And to select two or more directories
```php  
$directories = array(   
    'examples/folder',
    'examples/folder2'
);
AnalyzeFiles::rules()->directory($directories);
```
To specify the subfolder when should search
```php
$results = AnalyzeFiles::rules()->directory($this->directory)->create(function($rules) {
    $rules->add(function($data) {
        $data->fileEndsWith('.php');
        $data->filesInside(array('sub1','sub2')); // HERE!
        $data->validation(function($validation) {
            $validation->whereSearch('Carlos'); 
        });
    });
});
```
### About the files
We can use different functions to define the files
```php
$data->fileIs('example1.php');  // To select just one file
$data->fileEndsWith('.php');    // To search in all php files
$data->files(array('example1.php','example2.php')); // Select the names
```
### About validation 
`$count` is opcional, by default it will return true if appears at least once.
```php
// Check if exists 'Carlos' in the file, return a boolean
$validation->whereSearch('Carlos', $count); 
// Only when doesn't exist 'Carlos'
$validation->whereNoSearch('Carlos', $count);
```
### Return example
```php
$results = array(
    'example1.php' => array(
        'result'    => true,
        'requirements'  => array(
            0 => array(
                'function'    => 'NoSearch',
                'value'       => 'Carlos',
                'result'      => true
            ), 1 => array(
                'function'    => 'Search',
                'value'       => 'Jorge',
                'result'      => true
            )
        )
    )
);
```