<?php

namespace VeriduTest\Common;

use Veridu\Common\Compat;

class CompatTest extends \PHPUnit_Framework_TestCase {

	public function testEmptyBuildQuery() {
		$query = Compat::buildQuery(array());
		$this->assertSame('', $query);
	}

	public function testSpacedBuildQuery() {
		$query = Compat::buildQuery(array(
			'a' => ' b '
		));
		$this->assertSame('a=+b+', $query);
	}

	public function testSimpleBuildQuery() {
		$query = Compat::buildQuery(array(
			'a' => 'b',
			'c' => 'd'
		));
		$this->assertSame('a=b&c=d', $query);
	}

	public function testIndexedBuildQuery() {
		$query = Compat::buildQuery(array(
			'a', 'b', 'c'
		));
		$this->assertSame('0=a&1=b&2=c', $query);
	}

	public function testNumericArrayBuildQuery() {
		$query = Compat::buildQuery(array(
			'a' => array('b', 'c'),
			'd' => 'e'
		));
		$this->assertSame('a%5B0%5D=b&a%5B1%5D=c&d=e', $query);
	}

	public function testIndexedArrayBuildQuery() {
		$query = Compat::buildQuery(array(
			'a' => array(
				'b' => 'c',
				'd' => 'e'
			),
			'f' => 'g'
		));
		$this->assertSame('a%5Bb%5D=c&a%5Bd%5D=e&f=g', $query);
	}

}
