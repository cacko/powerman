<?php

namespace App\Entity;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @property-read int $trigger
 * @property-read int $ack
 * @property-read int $last_ack
 */
class StateEntity extends AbstractAppEntity
{
    public function __construct(
        protected int $trigger,
        protected int $ack,
        protected int $last_ack
    ) {

    }

}