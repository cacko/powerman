<?php

namespace App\Entity;

/**
 * @property-read string $username
 * @property-read string $email
 * @property-read WorkstationEntity[] $workstations
 */
class UserEntity extends AbstractAppEntity
{
    public function __construct(
        protected string $username,
        protected string $email,
        protected array $workstations
    ) {

    }
}