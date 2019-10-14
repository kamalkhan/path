<?php

/*
 * This file is part of bhittani/path.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\Path;

use BadMethodCallException;

class StaticPath
{
    /**
     * Invoke a method on the shared instance.
     *
     * @param string  $name
     * @param mixed[] $arguments
     *
     * @throws BadMethodCallException if the method does not exist on the instance
     *
     * @return mixed
     */
    public static function __callStatic($name, array $arguments)
    {
        static $instance;

        $instance = $instance ?: new Path();

        if (method_exists($instance, $name)) {
            return $instance->{$name}(...$arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s().',
            get_class($instance),
            $name
        ));
    }
}
