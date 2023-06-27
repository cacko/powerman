<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;

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


    public function getDefaultTrigger(): int
    {
        try {
            $tz = new DateTimeZone(static::TZ);
            $now = new DateTime('now', $tz);
            $resumeAt = new DateTime("today {$this->resumeAt}", $tz);
            $suspendAt = new DateTime("today {$this->suspendAt}", $tz);
            if ($now > $suspendAt) {
                return (new DateTime("tomorrow {$this->resumeAt}", $tz))->getTimestamp();
            }
            if ($resumeAt > $now) {
                return $resumeAt->getTimestamp();
            }
            return 0;
        } catch (\Exception $e) {
            dump($e);
        }
        return 0;

    }

    public function getNextTrigger(int $trigger): int
    {
        try {
            $tz = new DateTimeZone(static::TZ);
            $now = new DateTime('now', $tz);
            if ($trigger < 0) {
                $suspendAt = new DateTime(sprintf("@%d", abs($trigger)), $tz);
                $resumeAt = new DateTime("tomorrow {$this->resumeAt}", $tz);
                return $now > $suspendAt ? $resumeAt->getTimestamp() : $trigger;
            }
            if ($trigger > 0) {
                $resumeAt = new DateTime("@{$trigger}", $tz);
                return $now > $resumeAt ? 0 : $trigger;
            }

            $resumeAt = new DateTime("today {$this->resumeAt}", $tz);
            $suspendAt = new DateTime("today {$this->suspendAt}", $tz);

            if ($now > $resumeAt && $now < $suspendAt) {
                return $suspendAt->getTimestamp();
            }
            return 0;
        } catch (\Exception $e) {
            dump($e);
        }
        return 0;
    }

}