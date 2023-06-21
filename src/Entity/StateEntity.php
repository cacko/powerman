<?php

namespace App\Entity;

/**
 * @property-read int $trigger
 * @property-read int $ack
 */
class StateEntity extends AbstractAppEntity
{
    public function __construct(
        protected int $trigger,
        protected int $ack
    ) {

    }
}