<?php

namespace Bhittani\Path;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class StaticPathTest extends TestCase
{
    /** @test */
    function it_passes_through_to_the_underlying_shared_instance()
    {
        $this->assertEquals('foo/bar', StaticPath::sanitize('foo\bar'));
    }

    /** @test */
    function it_throws_a_bad_method_call_exception_if_the_method_does_not_exist()
    {
        try {
            StaticPath::method404();
        } catch (BadMethodCallException $e) {
            return $this->assertEquals(sprintf(
                'Call to undefined method %s::%s().',
                Path::class,
                'method404'
            ), $e->getMessage());
        }

        $this->fail(sprintf(
            'Expected a %s exception to be thrown.',
            BadMethodCallException::class
        ));
    }
}
