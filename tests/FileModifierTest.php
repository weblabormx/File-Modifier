<?php
use \WeblaborMX\FileModifier\FileModifier;
include_once 'tests/loader.php';

class FileModifierTest extends \PHPUnit_Framework_TestCase {

	private $file;

	public function setUp() {

		$this->file = 'examples/usersb.txt';
       	copy("examples/users.txt", $this->file);

    }

    /* Normal code testing *//*
    public function testFile() {

    	$found = FileModifier::file('noexisteesto.php')->exists();
	    $this->assertFalse( $found );

	    $found = FileModifier::file($this->file)->exists();
	    $this->assertTrue( $found );

    }

    public function testFind() {
/*
		$found = FileModifier::file($this->file)->find('user')->count();
	    $this->assertEquals( 4, $found );		// 4 elements with 'user' founded

	    $found = FileModifier::file($this->file)->find('user')->first();
		$this->assertEquals( 1, $found->line );
		$this->assertEquals( 'user1,pass1', trim($found->value) );

	    $found = FileModifier::file($this->file)->find('noexisteesto')->execute();
	    $this->assertFalse( $found );


	}
/*
	public function testFindArray() {

		$search = array("user","1");
		$found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertEquals(count($found), 2); 			// 2 elements searched
	    $this->assertEquals(count($found['user']), 4); 	// 4 users founded
	    $this->assertEquals(count($found['1']), 2); 	// 2 users with 1 founded

	}

	public function testFindByLine() {

		$found = FileModifier::file($this->file)->getLine(8)->first();
	    $this->assertEquals( '// Comentary', trim($found->value) );

		$found = FileModifier::file($this->file)->getLine(832)->first();
	    $this->assertFalse( $found );
	    $found = FileModifier::file($this->file)->getLine(832)->execute();
	    $this->assertFalse( $found );

	}
	
	public function testCount() {

		$found = FileModifier::file($this->file)->count();
		$this->assertEquals( 29, $found );

		$found = FileModifier::file($this->file)->find('user')->count();
	    $this->assertEquals( 4, $found );		// 4 elements with 'user' founded
		
		$found = FileModifier::file($this->file)->find('floren')->count();
	    $this->assertEquals( 3, $found );
		
	    $found = FileModifier::file($this->file)->find('noexisteesto')->count();
	    $this->assertEquals( 0, $found );

	    $search = array("user","1");
		$found = FileModifier::file($this->file)->find($search)->count();
	    $this->assertEquals($found, 2);

	    $search = array("user","noexisteesto");
		$found = FileModifier::file($this->file)->find($search)->count();
	    $this->assertEquals($found, 1);  // in one option was successfull

	}

	public function testReplace() {
		$search = 'user';
		$replacement = 'usuario';

		// See without changing
		$found = FileModifier::file($this->file)->replace($search, $replacement)->execute(false);
	    $this->assertEquals( count($found), 4 );			// 4 changes to do
	    $found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertEquals( count($found), 4 );	// 4 elements should still there
	    $found = FileModifier::file($this->file)->find($replacement)->execute();
	    $this->assertFalse( $found );			// None elements with usuario

	    // Changing
	    $found = FileModifier::file($this->file)->replace($search, $replacement)->execute();
	    $this->assertEquals( count($found), 4 );				// 4 changes to do
	    $found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertFalse( $found );				// None elements with user 
	    $found = FileModifier::file($this->file)->find($replacement)->execute();
	    $this->assertEquals( count($found), 4 );		// 4 elements with usuario

	    $found = FileModifier::file($this->file)->replace('noexiste', $replacement)->execute();
	    $this->assertFalse( $found );
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
	    $this->assertFalse( $found );		// None elements with replacement

	    // Changing
	    $found = FileModifier::file($this->file)->replaceLineWhere($search, $replacement)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($search)->execute();
	    $this->assertFalse( $found );		// None elements with search
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
	    $this->assertFalse( $found );		// None elements with replacement

	    $found = FileModifier::file($this->file)->find($search)->first();
	    $linesearch = $found->line; 

	    // Changing
	    $found = FileModifier::file($this->file)->addBeforeLine($search, $addition)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($addition)->count();
	    $this->assertEquals( $found, 1 );		// 1 elements with replacement
	   	$found = FileModifier::file($this->file)->find($addition)->first();
	    $this->assertEquals( $linesearch, $found->line );

	    $found = FileModifier::file($this->file)->find($search)->first();
	    $this->assertEquals( $linesearch+1, $found->line );
	}

	public function testAddAfterLine() {
		$search = 'user4';
		$addition = 'user4.1, hola';

		// See without changing
		$found = FileModifier::file($this->file)->addAfterLine($search, $addition)->execute(false);
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertFalse( $found );		// None elements with replacement

	    $found = FileModifier::file($this->file)->find($search)->first();
	    $linesearch = $found->line;

	    // Changing
	    $found = FileModifier::file($this->file)->addAfterLine($search, $addition)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 changes to do
	    $found = FileModifier::file($this->file)->find($addition)->count();
	    $this->assertEquals( $found, 1 );		// 1 elements with replacement
	    $found = FileModifier::file($this->file)->find($addition)->first();
	    $this->assertEquals( $linesearch+1, $found->line );

	    $found = FileModifier::file($this->file)->find($search)->first();
	    $this->assertEquals( $linesearch, $found->line );

	}

	public function testGetFunctionLines() {

		$res = FileModifier::file($this->file)->getFunctionLines("hi");
	    $this->assertEquals( $res['starts'], 14 );
	    $this->assertEquals( $res['finish'], 21 );
	    $res = FileModifier::file($this->file)->getFunctionLines("hibye");
	    $this->assertEquals( $res['starts'], 24 );
	    $this->assertEquals( $res['finish'], 28 );

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

	public function testAddAfterLineByLine() {
		$addition = 'asd3.1, hola';

		$res = FileModifier::file($this->file)->getFunctionLines("hibye"); // Get number of lines of the function
	    $oldline = $res['finish'];
	    $search = $oldline; // Number of line

		$found = FileModifier::file($this->file)->addAfterLineByLine($search, $addition)->execute();
	    
	    $res = FileModifier::file($this->file)->find($addition)->first();
	    $newline = $res->line;

	    $this->assertEquals( $oldline+1, $newline ); // A new line inside the function added

	}

	public function testChangeLine() {
		$line = 8;
		$change = '// new comment';

		$found = FileModifier::file($this->file)->find($change)->execute();
	    $this->assertFalse( $found );

	    $found = FileModifier::file($this->file)->changeLine($line, $change)->execute();

	    $found = FileModifier::file($this->file)->find($change)->count();
	    $this->assertEquals( 1, $found );
	    $found = FileModifier::file($this->file)->find($change)->first();
	    $this->assertEquals( 8, $found->line );

	}

	public function testRemoveLine() {
		$line = 8;
		$change = '// old comment';

		$found = FileModifier::file($this->file)->getLine($line)->first();
	    $oldvalue = trim($found->value);

	    $found = FileModifier::file($this->file)->removeLine($line)->execute();
		$found = FileModifier::file($this->file)->getLine($line)->first();
	    $newvalue = trim($found->value);

	    $this->assertFalse( $newvalue==$oldvalue );

	}

	public function testRemoveLineWhere() {
		$search = 'user';

		$lines = FileModifier::file($this->file)->count();

	    $found = FileModifier::file($this->file)->removeLineWhere($search)->execute();

		$newlines = FileModifier::file($this->file)->count();

	    $this->assertEquals( 29, $lines );
	    $this->assertEquals( 25, $newlines );

	}

	// Example of return
	/*
		array(
			0 => array(
				'action'	=> 'replace',
				'lineNum'	=> 1,
				'lineOld'	=> 'user1,pass1',
				'lineNew'	=> 'usuario11,pass1',
				'search'	=> 'user',
				'val2'		=> 'usuario1'
			)
		);
	*/ 
/*
	public function testMultiple() {

		$res = FileModifier::file($this->file)
			->replace("user","usuario1")	// 4 users
			->addBeforeLine("florencio","beforeflorencio, nada") // 2 florencio
			->execute(false);

	    $this->assertEquals( count($res), 6 );

	}

	// Programming code testing 

	public function testGetFunctionInit() {

		$res = FileModifier::file($this->file)->getFunctionInit("hi");
	    $this->assertEquals( $res, 14 );
	    $res = FileModifier::file($this->file)->getFunctionInit("hibye");
	    $this->assertEquals( $res, 22 );

	}
*/
	public function testGetIfLines() {

		$res = FileModifier::file($this->file)->getIfLines("if(true)");
	    $this->assertEquals( $res['starts'], 10 ); // it says the first founded
	    $this->assertEquals( $res['finish'], 13 );
	    /*$res = FileModifier::file($this->file)->getIfLines("if(true)", false, 2); // Search the second one
	    $this->assertEquals( $res['starts'], 18 );
	    $this->assertEquals( $res['finish'], 20 );

	    $lines = FileModifier::file($this->file)->getFunctionLines("hi");

	    $res = FileModifier::file($this->file)->getIfLines("if(true)", $lines); // Search inside hi function
	    $this->assertEquals( $res['starts'], 18 );
	    $this->assertEquals( $res['finish'], 20 );*/

	}
/*
	function testLinesAndPos() {
	
	    $array = array(
	    	'lines'	=> array(
	    		'starts'	=> 4,
	    		'finish'	=> 8
	    	)
	    );
	    $found = FileModifier::file($this->file)->find('user', false, $array)->count();
	    $this->assertEquals( 1, $found );

	    $array = array(
	    	'lines'	=> array(
	    		'starts'	=> 3,
	    		'finish'	=> 8
	    	), 
	    );
	    $found = FileModifier::file($this->file)->find('user', false, $array)->count();
	    $this->assertEquals( 2, $found );

	    $array = array(
	    	'lines'	=> array(
	    		'starts'	=> 3,
	    		'finish'	=> 8
	    	), 
	    	'pos'	=> 2
	    );
	    $found = FileModifier::file($this->file)->find('user', false, $array)->first();
	    $this->assertEquals( 'user4,pass4', trim($found->value) );
	}
	/*
	public function testAddAtTheEnd() {
		$addition = 'copyright';

	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertEquals( count($found), 0 );		// None elements with replacement
	    FileModifier::file($this->file)->addAtTheEnd($addition)->execute();
	    $found = FileModifier::file($this->file)->find($addition)->execute();
	    $this->assertEquals( count($found), 1 );		// 1 elements with replacement

	}*/

	public function tearDown() {

		unlink( $this->file );

    }

}
?> 