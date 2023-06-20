<?php

namespace App\Entity;

class StateEntity extends AbstractAppEntity
{
    public function __construct(
        protected int $trigger,
        protected int $ack
    ) {

    }
}