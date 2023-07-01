<?php

namespace App\Entity;

use ArrayAccess;
use InvalidArgumentException;

abstract class AbstractAppEntity implements ArrayAccess
{

    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new InvalidArgumentException();
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new InvalidArgumentException();
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    protected static function cleanKeys(array $vars): array
    {
        return array_reduce(array_keys($vars), function($res, $k) use($vars) {
            $res[trim($k, "\0*")] = $vars[$k];
            return $res;
        }, []);
    }

}