<?php
use \WeblaborMX\FileModifier\Helper;
use \WeblaborMX\FileModifier\FileModifier;
include_once 'tests/loader.php';

class HelperTest extends \PHPUnit_Framework_TestCase {

	// Strings

	public function testStartsWith() {

		$this->assertTrue( Helper::startsWith( 'hola-mundo.php', 'hola' ));
		$this->assertFalse( Helper::startsWith( 'adios-mundo-hola.php', 'hola' ));
		$this->assertFalse( Helper::startsWith( 'ahola-mundo.php', 'hola' ));

	}

	public function testEndsWith() {

		$this->assertTrue( Helper::endsWith( 'hola-mundo.php', '.php' ));
		$this->assertFalse( Helper::endsWith( 'file.js', '.php' ));
		
	}

	public function testHasString() {

		$this->assertTrue( Helper::hasString( 'hola-mundo.php', 'mundo' ));
		$this->assertTrue( Helper::hasString( 'adios-mundo-hola.php', 'hola' ));
		$this->assertTrue( Helper::hasString( 'ahola-mundo.php', 'hola' ));
		$this->assertFalse( Helper::hasString( 'ahola-mundo.php', 'adios' ));
		
	}

	// Folders

	public function testFolderCount() {
		// Count files
		$this->assertEquals( 4, Helper::folder( 'examples/folder1' )->count() );
		$this->assertEquals( 3, Helper::folder( 'examples/folder2' )->count() );
		
	}

	public function testFolderTotalSubfolders() {

		$this->assertEquals( 1, Helper::folder( 'examples/folder1' )->total_subfolders() );
		$this->assertEquals( 2, Helper::folder( 'examples/folder2' )->total_subfolders() );
		
	}

	public function testFolderExists() {

		$this->assertTrue( Helper::folder( 'examples/folder1' )->exists() );
		$this->assertTrue( Helper::folder( 'examples/folder2' )->exists() );
		$this->assertFalse( Helper::folder( 'examples/noexiste' )->exists() );
		
	}

	public function testFolderFiles() {

		$folder1 = ['controller.php', 'repository.php', 'views\edit.php', 'views\index.php'];
		$folder2 = ['request.php', 'sub1\request.php', 'sub2\request.php'];
		$this->assertTrue( Helper::folder( 'examples/folder1' )->files() == $folder1 );
		$this->assertTrue( Helper::folder( 'examples/folder2' )->files() == $folder2 );
		
	}

	public function testFolderCopyTo() {

		$this->assertTrue( FileModifier::file('examples/folder2/request.php')->exists() );
		$this->assertFalse( FileModifier::file('examples/folder2-1/request.php')->exists() );

		Helper::folder( 'examples/folder2' )->copyTo( 'examples/folder2-1' );
		
		$this->assertTrue( FileModifier::file('examples/folder2/request.php')->exists() );
		$this->assertTrue( FileModifier::file('examples/folder2-1/request.php')->exists() );
		
	}

	public function testFolderRemove() {

		$this->assertTrue( Helper::folder('examples/folder2-1')->exists() );

		Helper::folder( 'examples/folder2-1' )->remove();
		
		$this->assertFalse( Helper::folder('examples/folder2-1')->exists() );
		
	}

	public function testFolderCreate() {

		$this->assertFalse( Helper::folder('examples/folder2-1')->exists() );

		Helper::folder( 'examples/folder2-1' )->create();
		
		$this->assertTrue( Helper::folder('examples/folder2-1')->exists() );
		Helper::folder( 'examples/folder2-1' )->remove();

	}

	public function testFolderMove() {

		$this->assertFalse( Helper::folder('examples/folder2-1')->exists() );

		Helper::folder( 'examples/folder2' )->moveTo('examples/folder2-1');
		
		$this->assertTrue( Helper::folder('examples/folder2-1')->exists() );
		$this->assertFalse( Helper::folder('examples/folder2')->exists() );

		Helper::folder( 'examples/folder2-1' )->moveTo('examples/folder2');
			
	}

}
?> 