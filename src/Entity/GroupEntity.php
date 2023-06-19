<?php

namespace App\Entity;
//label: Always On
//    suspendAt: 00:00
//    resumeAt: 00:00
//    sleepInterval: 120
class GroupEntity extends AbstractAppEntity
{
    public function __construct(
        protected string $id,
        protected string $label,
        protected string $suspendAt,
        protected string $resumeAt,
        protected int $sleepInterval
    ) {

    }

}