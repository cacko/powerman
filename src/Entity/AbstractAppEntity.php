<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;

abstract class AbstractAppEntity extends EntityRepository implements \ArrayAccess
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
        throw new \InvalidArgumentException();
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \InvalidArgumentException();
    }

}