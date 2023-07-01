<?php

namespace App\Entity;

use ArrayIterator;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Traversable;

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

    public function getIterator(): Traversable
    {
        return new ArrayIterator();
    }

    public function getDefaultTrigger(): int
    {
        try {
            $tz = new DateTimeZone(static::TZ);
            $now = new DateTime('now', $tz);
            $resumeAt = new DateTime("today {$this->resumeAt}", $tz);
            $suspendAt = new DateTime("today {$this->suspendAt}", $tz);
            if ($now > $suspendAt) {
                return Carbon::nextBusinessDay()
                    ->setTimeFromTimeString($this->resumeAt)
                    ->setTimezone($tz)->getTimestamp();
            }
            if ($resumeAt > $now && $now < $suspendAt) {
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
                $resumeAt = Carbon::nextBusinessDay()
                    ->setTimeFromTimeString($this->resumeAt)->setTimezone($tz);
                return $now > $suspendAt ? $resumeAt->getTimestamp() : $trigger;
            }
            if ($trigger > 0) {
                $resumeAt = new DateTime("@{$trigger}", $tz);
                return $now < $resumeAt ? $trigger : 0;
            }

            $resumeAt = new DateTime("today {$this->resumeAt}", $tz);
            $suspendAt = new DateTime("today {$this->suspendAt}", $tz);

            if ($now > $resumeAt && $now < $suspendAt) {
                return 0;
            }
            return $suspendAt->getTimestamp();

        } catch (\Exception $e) {
            dump($e);
        }
        return 0;
    }

}