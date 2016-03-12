<?php
use \WeblaborMX\FileModifier\FileModifier;
include_once 'src/FileModifier.php';

class FileModifierTest extends \PHPUnit_Framework_TestCase {

	private $file;

	public function setUp() {

		$this->file = 'examples/usersb.txt';
       	copy("examples/users.txt", $this->file);

    }

    /* Normal code testing */
	public function testFindArray() {

		$search = array("user","1");
		$found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertEquals(count($found), 2); 			// 2 elements searched
	    $this->assertEquals(count($found['user']), 4); 	// 4 users founded
	    $this->assertEquals(count($found['1']), 2); 	// 2 users with 1 founded

	}

	public function testFind() {

		$found = FileModifier::file($this->file)->find('user')->execute();
	    $this->assertEquals( count($found['user']), 4 );		// 4 elements with 'user' founded
	
	}

	public function testReplace() {
		$search = 'user';
		$replacement = 'usuario';

		// See without changing
		$found = FileModifier::file($this->file)->replace($search, $replacement)->execute(false);
	    $this->assertEquals( count($found), 4 );			// 4 changes to do
	    $found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertEquals( count($found[$search]), 4 );	// 4 elements should still there
	    $found = FileModifier::file($this->file)->find($replacement)->execute();
	    $this->assertEquals( count($found), 0 );			// None elements with usuario

	    // Changing
	    $found = FileModifier::file($this->file)->replace($search, $replacement)->execute();
	    $this->assertEquals( count($found), 4 );				// 4 changes to do
	    $found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertEquals( count($found), 0 );				// None elements with user 
	    $found = FileModifier::file($this->file)->find($replacement)->execute();
	    $this->assertEquals( count($found[$replacement]), 4 );		// 4 elements with usuario

	}

	public function testReplaceLineWhere() {
		$search = 'rios';
		$replacement = 'nuevo, river';

		// See without changing
		$found = FileModifier::file($this->file)->replaceLineWhere($search, $replacement)->execute(false);
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 elements should still there
	    $found = FileModifier::file($this->file)->find($replacement)->execute();
	    $this->assertEquals( count($found), 0 );		// None elements with replacement

	    // Changing
	    $found = FileModifier::file($this->file)->replaceLineWhere($search, $replacement)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertEquals( count($found), 0 );		// None elements with search
	    $found = FileModifier::file($this->file)->find($replacement)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 elements with replacement

	}

	public function testAddBeforeLine() {
		$search = 'user4';
		$addition = 'user3.1, hola';

		// See without changing
		$found = FileModifier::file($this->file)->addBeforeLine($search, $addition)->execute(false);
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertEquals( count($found), 0 );		// None elements with replacement

	    // Changing
	    $found = FileModifier::file($this->file)->addBeforeLine($search, $addition)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 elements with replacement

	}

	public function testAddBeforeLineByLine() {
		$addition = 'asd3.1, hola';

		$res = FileModifier::file($this->file)->getFunctionLines("hibye"); // Get number of lines of the function
	    $oldline = $res['finish'];
	    $search = $oldline; // Number of line

		$found = FileModifier::file($this->file)->addBeforeLineByLine($search, $addition)->execute();
	    
	    $res = FileModifier::file($this->file)->getFunctionLines("hibye");
	    $newline = $res['finish'];

	    $this->assertEquals( $oldline+1, $newline ); // A new line inside the function added

	}

	public function testAddAfterLine() {
		$search = 'user4';
		$addition = 'user4.1, hola';

		// See without changing
		$found = FileModifier::file($this->file)->addAfterLine($search, $addition)->execute(false);
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertEquals( count($found), 0 );		// None elements with replacement

	    // Changing
	    $found = FileModifier::file($this->file)->addAfterLine($search, $addition)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 elements with replacement

	}

	public function testAddAtTheEnd() {
		$addition = 'copyright';

	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertEquals( count($found), 0 );		// None elements with replacement
	    FileModifier::file($this->file)->addAtTheEnd($addition)->execute();
	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 elements with replacement

	}

	public function testChangeLine() {
		$line = 8;
		$change = '// new comment';

		$found = FileModifier::file($this->file)->find($change)->execute();
	    $this->assertEquals( count($found), 0 );

	    $found = FileModifier::file($this->file)->changeLine($line, $change)->execute();

	    $found = FileModifier::file($this->file)->find($change)->execute();
	    $this->assertEquals( count($found), 1 );

	}

	public function testMultiple() {

		$res = FileModifier::file($this->file)
			->replace("user","usuario1")	// 4 users
			->addBeforeLine("florencio","beforeflorencio, nada") // 2 florencio
			->addAtTheEnd("Copyright") // one end
			->execute(false);

	    $this->assertEquals( count($res), 7 );

	}

	public function testCount() {

		$res = FileModifier::file($this->file)->count();
	    $this->assertEquals( $res, 28 );

	}

	public function testGetLineWhere() {

		$res = FileModifier::file($this->file)->getLineWhere('rios');
	    $this->assertEquals( $res, 5 );

	    $res = FileModifier::file($this->file)->getLineWhere('user');
	    $this->assertEquals( $res, 1 );

	}

	/* Programming code testing */

	public function testGetFunctionInit() {

		$res = FileModifier::file($this->file)->getFunctionInit("hi");
	    $this->assertEquals( $res, 14 );
	    $res = FileModifier::file($this->file)->getFunctionInit("hibye");
	    $this->assertEquals( $res, 22 );

	}

	public function testGetFunctionLines() {

		$res = FileModifier::file($this->file)->getFunctionLines("hi");
	    $this->assertEquals( $res['starts'], 14 );
	    $this->assertEquals( $res['finish'], 21 );
	    $res = FileModifier::file($this->file)->getFunctionLines("hibye");
	    $this->assertEquals( $res['starts'], 24 );
	    $this->assertEquals( $res['finish'], 28 );

	}

	public function testGetIfLines() {

		$res = FileModifier::file($this->file)->getIfLines("if(true)");
	    $this->assertEquals( $res['starts'], 10 ); // it says the first founded
	    $this->assertEquals( $res['finish'], 13 );
	    $res = FileModifier::file($this->file)->getIfLines("if(true)", false, 2); // Search the second one
	    $this->assertEquals( $res['starts'], 18 );
	    $this->assertEquals( $res['finish'], 20 );

	    $lines = FileModifier::file($this->file)->getFunctionLines("hi");

	    $res = FileModifier::file($this->file)->getIfLines("if(true)", $lines); // Search inside hi function
	    $this->assertEquals( $res['starts'], 18 );
	    $this->assertEquals( $res['finish'], 20 );

	}

	public function testFileExists() {
		$this->assertTrue( FileModifier::file($this->file)->exists() );
		$this->assertTrue( FileModifier::file('composer.json')->exists() );
		$this->assertFalse( FileModifier::file('holamundo.php')->exists() );
	}

	public function tearDown() {

		unlink( $this->file );

    }

}
?> 