<?php

namespace Tests\Iterators;

use Iterator;

class JsonDataProviderIterator implements Iterator
{
    protected $position = 0;
    protected $array;

    public function __construct($test, $namespace)
    {
        $path = preg_replace('/.php$/', '.data', $test) . '/' . $namespace;
        $dir = scandir($path);
        $files = array_filter(
            $dir ? $dir : array(),
            function ($file) {
                return preg_match('/.json$/', $file);
            }
            );
        $files = array_map(function ($file) use ($path) {
            return $path . '/' . $file;
        }, $files);
        sort($files);
        $this->array = $files;
    }

    public function current()
    {
        $file = $this->array[$this->position];
        $content = file_get_contents($file);

        return array(json_decode($content, true));
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->array[$this->position]);
    }
}
