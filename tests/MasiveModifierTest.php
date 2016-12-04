<?php
use \WeblaborMX\FileModifier\MasiveModifier;
use \WeblaborMX\FileModifier\Helper;
use \WeblaborMX\FileModifier\FileModifier;
include_once 'tests/loader.php';

class MasiveModifierTest extends \PHPUnit_Framework_TestCase {

	private $folder1;
	private $folder2;

	public function setUp() {

		$this->folder1 = 'examples/folder1-b';
		$this->folder2 = 'examples/folder2-b';
       	Helper::folder("examples/folder1")->copyTo($this->folder1);
       	Helper::folder("examples/folder2")->copyTo($this->folder2);

    }

    /* Normal code testing */
    public function testFiles() {

    	$files = [$this->folder1.'\views\edit.php', $this->folder1.'\views\index.php'];
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\edit.php')->find('{name}')->count());
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\index.php')->find('{name}')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\edit.php')->find('Ejemplo')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\index.php')->find('Ejemplo')->count());

    	$found = MasiveModifier::files($files)->execute(function($FileModifier) {
    		$FileModifier->replace('{name}', 'Ejemplo');
    	});

    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\edit.php')->find('{name}')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\index.php')->find('{name}')->count());
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\edit.php')->find('Ejemplo')->count());
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\index.php')->find('Ejemplo')->count());

    }

    public function testDirectory() {

    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\edit.php')->find('{name}')->count());
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\index.php')->find('{name}')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\edit.php')->find('Ejemplo')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\index.php')->find('Ejemplo')->count());

    	$found = MasiveModifier::directory($this->folder1)->execute(function($FileModifier) {
    		$FileModifier->replace('{name}', 'Ejemplo');
    		$FileModifier->replace('{text}', 'Texto');
    	});

    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\edit.php')->find('{name}')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\index.php')->find('{name}')->count());
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\edit.php')->find('Ejemplo')->count());
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\index.php')->find('Ejemplo')->count());
    	$this->assertEquals(1, FileModifier::file($this->folder1.'\repository.php')->find('Texto')->count());

    }

    public function testDirectories() {

    	$directories = [$this->folder1, $this->folder2];
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\edit.php')->find('{name}')->count());
    	$this->assertEquals(1, FileModifier::file($this->folder2.'\request.php')->find('{name}')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\edit.php')->find('Ejemplo')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder2.'\request.php')->find('Ejemplo')->count());

    	$found = MasiveModifier::directories( $directories )->execute(function($FileModifier) {
    		$FileModifier->replace('{name}', 'Ejemplo');
    		$FileModifier->replace('{text}', 'Texto');
    	});

    	$this->assertEquals(0, FileModifier::file($this->folder1.'\views\edit.php')->find('{name}')->count());
    	$this->assertEquals(0, FileModifier::file($this->folder2.'\request.php')->find('{name}')->count());
    	$this->assertEquals(2, FileModifier::file($this->folder1.'\views\edit.php')->find('Ejemplo')->count());
    	$this->assertEquals(1, FileModifier::file($this->folder2.'\request.php')->find('Ejemplo')->count());

    }


	public function tearDown() {

		Helper::folder( $this->folder1 )->remove();
		Helper::folder( $this->folder2 )->remove();

    }

}
?> 