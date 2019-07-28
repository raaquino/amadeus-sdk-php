<?php

use Amadeus\Amadeus;

class AmadeusTest extends PHPUnit_Framework_TestCase {

	public function testIsOk()  
	{  
		$amadeus = new Amadeus;  
		$this->assertTrue($amadeus->isOk());  
	}

}