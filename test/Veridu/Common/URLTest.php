<?php

namespace VeriduTest\Common;

use Veridu\Common\URL;

class URLTest extends \PHPUnit_Framework_TestCase
{

    public function testBaseURLWithTrailingSlashEmptyRequestURI()
    {
        $url = URL::build('http://example.com/');
        $this->assertSame('http://example.com/', $url);
    }

    public function testBaseURLWithTrailingSlashStringRequestURI()
    {
        $url = URL::build('http://example.com/', '/path/to/me');
        $this->assertSame('http://example.com/path/to/me', $url);
    }

    public function testQueryStringWithLeadingQuestionMark()
    {
        $url = URL::build('http://example.com', null, '?key=value');
        $this->assertSame('http://example.com/?key=value', $url);
    }

    public function testArrayRequestURIEmptyQueryString()
    {
        $url = URL::build('http://example.com', array('path', 'to', 'me'));
        $this->assertSame('http://example.com/path/to/me', $url);
    }

    public function testArrayRequestURIWithSlashesStringQueryString()
    {
        $url = URL::build('http://example.com', array('/path/', '/to/me'), 'key=value');
        $this->assertSame('http://example.com/path/to/me?key=value', $url);
    }

    public function testArrayRequestURIWithoutSlashesStringQueryString()
    {
        $url = URL::build('http://example.com', array('path', 'to', 'me'), 'key=value');
        $this->assertSame('http://example.com/path/to/me?key=value', $url);
    }

    public function testArrayRequestURIArrayQueryString()
    {
        $url = URL::build('http://example.com', array('path', 'to', 'me'), array('key'=> 'value'));
        $this->assertSame('http://example.com/path/to/me?key=value', $url);
    }

    public function testStringRequestURIEmptyQueryString()
    {
        $url = URL::build('http://example.com', '/path/to/me/');
        $this->assertSame('http://example.com/path/to/me/', $url);
    }

    public function testStringRequestURIStringQueryString()
    {
        $url = URL::build('http://example.com', '/path/to/me/', 'key=value');
        $this->assertSame('http://example.com/path/to/me/?key=value', $url);
    }

    public function testStringRequestURIArrayQueryString()
    {
        $url = URL::build('http://example.com', '/path/to/me/', array('key' => 'value'));
        $this->assertSame('http://example.com/path/to/me/?key=value', $url);
    }

    public function testEmptyRequestURIEmptyQueryString()
    {
        $url = URL::build('http://example.com');
        $this->assertSame('http://example.com/', $url);
    }

    public function testEmptyRequestURIStringQueryString()
    {
        $url = URL::build('http://example.com', null, 'key=value');
        $this->assertSame('http://example.com/?key=value', $url);
    }

    public function testEmptyRequestURIArrayQueryString()
    {
        $url = URL::build('http://example.com', null, array('key' => 'value'));
        $this->assertSame('http://example.com/?key=value', $url);
    }
}
