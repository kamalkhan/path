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

class Path
{
    /**
     * Normalize and join the paths.
     *
     * @param string $paths,...
     *
     * @return string
     */
    public function normalize(...$paths)
    {
        $path = array_shift($paths);

        if (!$this->isAbsolute($path)) {
            $path = $this->absolute($path);
        }

        return $this->join($path, ...$paths);
    }

    /**
     * Get the absolute path.
     *
     * @param null|string $path
     * @param bool        $force
     *
     * @return string
     */
    public function absolute($path = null, $force = false)
    {
        if (!$force && $this->isAbsolute($path)) {
            return $this->normalize($path);
        }

        return $this->normalize(getcwd(), $path);
    }

    /**
     * Determine whether a path is absolute.
     *
     * @param string $path
     *
     * @return bool
     */
    public function isAbsolute($path)
    {
        return
            // /..., \...
            $this->startsWithSlash($path)
            // xxx://..., xxx:\\...
            || $this->isUrl($path)
            // x:/, x:\
            || $this->isDisk($path);
    }

    /**
     * Determine whether a path is root.
     *
     * @param string $path
     *
     * @return bool
     */
    public function isRoot($path)
    {
        return $this->isAbsolute($path) && (
            // /, \
            (strlen($path) == 1 && $this->startsWithSlash($path))
            // x:/, x:\
            || (strlen($path) == 3 && $this->isDisk($path))
            // xxx://, xxx:\\
            || (!is_null($scheme = parse_url($path, PHP_URL_SCHEME))
                && strlen($scheme) != 1
                && (!($p = parse_url($path, PHP_URL_PATH))
                    || $p == '/'
                )
            )
        );
    }

    /**
     * Join paths.
     *
     * @param string $paths,...
     *
     * @return string
     */
    public function join(...$paths)
    {
        $paths = $this->sanitize($paths);

        $path = array_shift($paths);

        return array_reduce($paths, function ($prefix, $suffix) {
            return rtrim(rtrim($prefix, '/').'/'.ltrim($suffix, '/'), '/');
        }, rtrim($path, '/').($this->isRoot($path) ? '/' : ''));
    }

    /**
     * Sanitize paths by converting back slashes to forward slashes.
     *
     * @param string|array $paths
     *
     * @return string|array
     */
    public function sanitize($paths)
    {
        if (!is_array($paths)) {
            return str_replace('\\', '/', $paths);
        }

        return array_map([$this, 'sanitize'], $paths);
    }

    /**
     * Determine whether a path is a disk drive path.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function isDisk($path)
    {
        $path = $this->sanitize($path);

        return strlen($path) > 2
            && ctype_alpha($path[0])
            && $path[1] == ':'
            && $path[2] == '/';
    }

    /**
     * Determine whether a path looks like a URL.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function isUrl($path)
    {
        return !is_null(parse_url($path, PHP_URL_SCHEME));
    }

    /**
     * Determine whether a path starts with a slash.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function startsWithSlash($path)
    {
        return !empty($path) && $this->sanitize($path[0]) == '/';
    }
}
