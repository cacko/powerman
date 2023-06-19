<?php

namespace App\Entity;

class WorkstationEntity extends AbstractAppEntity
{
    public function __construct(
        protected string $hostname,
        protected GroupEntity $group,
        protected string $location
    ) {

    }
}