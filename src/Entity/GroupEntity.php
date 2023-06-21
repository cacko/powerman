<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @property-read string $id
 * @property-read string $label
 * @property-read string $suspendAt
 * @property-read string $resumeAt
 * @property-read int $sleepInterval
 * @property-read string[] $hostnames
 */
class GroupEntity extends AbstractAppEntity
{
    const TZ = 'Europe/London';

    public function __construct(
        protected string $id,
        protected string $label,
        protected string $suspendAt,
        protected string $resumeAt,
        protected int    $sleepInterval,
        protected array  $hostnames
    )
    {

    }

    public function getDefaultTrigger(): ?DateTime
    {
        $tz = new DateTimeZone(static::TZ);
        $now = new DateTime('now', $tz);
        $resumeAt = new DateTime("today {$this->resumeAt}", $tz);
        $suspendAt = new DateTime("today {$this->suspendAt}", $tz);
        if ($now > $suspendAt) {
            return new DateTime("tomorrow {$this->resumeAt}", $tz);
        }
        if ($resumeAt > $now) {
            return $resumeAt;
        }
        return null;
    }

    public function getNextTrigger(int $trigger): ?DateTime
    {
        $tz = new DateTimeZone(static::TZ);
        $now = new DateTime('now', $tz);
        $resumeAt = $trigger ? new DateTime("@{$trigger}", $tz) : new DateTime("today {$this->resumeAt}", $tz);
        $suspendAt = new DateTime("today {$this->suspendAt}", $tz);
        if ($now > $suspendAt) {
            return new DateTime("tomorrow {$this->resumeAt}", $tz);
        }
        if ($resumeAt > $now) {
            return $resumeAt;
        }
        return null;
    }

}