<?php

namespace Bhittani\Path;

use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    var $path;

    function setUp()
    {
        $this->path = new Path;
    }

    /** @test */
    function sanitize()
    {
        $this->assertEquals('foo/bar', $this->path->sanitize('foo/bar'));
        $this->assertEquals('foo/bar', $this->path->sanitize('foo\bar'));
        $this->assertEquals('/foo/bar', $this->path->sanitize('\foo/bar'));
        $this->assertEquals('c:/foo/bar', $this->path->sanitize('c:\foo/bar'));
        $this->assertEquals([
            'foo/bar/',
            'fizz/buzz/',
        ], $this->path->sanitize([
            'foo\bar/',
            'fizz\buzz\\',
        ]));
    }

    /** @test */
    function join()
    {
        $this->assertEquals('foo', $this->path->join('foo', '/'));
        $this->assertEquals('/foo', $this->path->join('/', 'foo'));
        $this->assertEquals('foo/bar/baz', $this->path->join('foo', 'bar', 'baz'));
        $this->assertEquals('/foo/bar/baz', $this->path->join('/foo', 'bar', 'baz'));
        $this->assertEquals('/foo/bar/baz', $this->path->join('/foo', 'bar', 'baz/'));
        $this->assertEquals('/foo/bar/baz', $this->path->join('/foo', '/bar', 'baz/'));
        $this->assertEquals('/foo/bar/baz', $this->path->join('/foo', '/bar/', 'baz/'));
        $this->assertEquals('http://example.com/foo', $this->path->join('http://example.com', 'foo'));
    }

    /** @test */
    function isAbsolute()
    {
        $this->assertFalse($this->path->isAbsolute('foo'));
        $this->assertFalse($this->path->isAbsolute('foo/bar'));

        $this->assertTrue($this->path->isAbsolute('/'));
        $this->assertTrue($this->path->isAbsolute('/foo'));
        $this->assertTrue($this->path->isAbsolute('c:/'));
        $this->assertTrue($this->path->isAbsolute('d://'));
        $this->assertTrue($this->path->isAbsolute('e:\\'));
        $this->assertTrue($this->path->isAbsolute('c:/foo'));
        $this->assertTrue($this->path->isAbsolute('d://foo'));
        $this->assertTrue($this->path->isAbsolute('e:\\foo'));
        $this->assertTrue($this->path->isAbsolute('http://'));
        $this->assertTrue($this->path->isAbsolute('http://example.com'));
        $this->assertTrue($this->path->isAbsolute('http://example.com/path'));
    }

    /** @test */
    function isRoot()
    {
        $this->assertFalse($this->path->isRoot('foo'));
        $this->assertFalse($this->path->isRoot('/foo'));
        $this->assertFalse($this->path->isRoot('foo/bar'));
        $this->assertFalse($this->path->isRoot('/foo/bar'));
        $this->assertFalse($this->path->isRoot('c:/p'));
        $this->assertFalse($this->path->isRoot('d://p'));
        $this->assertFalse($this->path->isRoot('e:\\p'));
        $this->assertFalse($this->path->isRoot('http://example.com/path'));
        $this->assertFalse($this->path->isRoot('http://domain.example.com/path'));

        $this->assertTrue($this->path->isRoot('/'));
        $this->assertTrue($this->path->isRoot('c:/'));
        $this->assertTrue($this->path->isRoot('d://'));
        $this->assertTrue($this->path->isRoot('e:\\'));
        $this->assertTrue($this->path->isRoot('http://'));
        $this->assertTrue($this->path->isRoot('http://example.com'));
        $this->assertTrue($this->path->isRoot('http://example.com/'));
        $this->assertTrue($this->path->isRoot('http://domain.example.com'));
    }

    /** @test */
    function absolute()
    {
        $path = dirname(__DIR__);

        $this->assertEquals($path, $this->path->absolute());
        $this->assertEquals('/foo.php', $this->path->absolute('/foo.php/'));
        $this->assertEquals($path.'/foo.php', $this->path->absolute('/foo.php/', true));
    }

    /** @test */
    function normalize()
    {
        $path = dirname(__DIR__);

        $this->assertEquals($path, $this->path->normalize());
        $this->assertEquals('/foo/bar', $this->path->normalize('/', 'foo', 'bar', '/'));
        $this->assertEquals('/foo/bar', $this->path->normalize('/foo', 'bar/'));
        $this->assertEquals($path.'/foo/bar/baz', $this->path->normalize('foo', 'bar', 'baz'));
        $this->assertEquals('/foo.php', $this->path->normalize('/foo.php/'));
        $this->assertEquals('/', $this->path->normalize('/'));
        $this->assertEquals('c:/', $this->path->normalize('c:/'));
        $this->assertEquals('c:/foo', $this->path->normalize('c:', 'foo/'));
    }
}
