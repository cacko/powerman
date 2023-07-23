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

    protected function getActiveTrack() {

    }

    public function getDefaultTrigger(): int
    {
        try {
            $tz = new DateTimeZone(static::TZ);
            $now = new DateTime('now', $tz);
            $resumeAt = new DateTime("today {$this->resumeAt}", $tz);
            $suspendAt = new DateTime("today {$this->suspendAt}", $tz);
            if ($now > $suspendAt) {
                return $this->getNextResumeAt()->getTimestamp();
            }
            if ($now < $resumeAt) {
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

            if ($now < $suspendAt) {
                return 0;
            }

            return $this->getNextResumeAt()->getTimestamp();

        } catch (\Exception $e) {
            dump($e);
        }
        return 0;
    }

    protected function getTimezone(): DateTimeZone
    {
        return new DateTimeZone(static::TZ);
    }

    protected function getNow(): DateTime
    {
        $tz = $this->getTimezone();
        return new DateTime('now', $tz);
    }

    protected function getNextResumeAt(): DateTime|\Carbon\CarbonImmutable|Carbon|\Carbon\CarbonInterface
    {
        $now = $this->getNow();
        $tz = $this->getTimezone();
        $resumeAt = new DateTime("today {$this->resumeAt}", $tz);
        if ($now > $resumeAt) {
            $resumeAt = Carbon::nextBusinessDay()
                ->setTimeFromTimeString($this->resumeAt)->setTimezone($tz);
        }
        return $resumeAt;
    }

    public function getNextSuspend(int $trigger): int
    {
        $tz = new DateTimeZone(static::TZ);
        $now = new DateTime('now', $tz);
        $suspendAt = new DateTime("today {$this->suspendAt}", $tz);
        if ($now > $suspendAt) {
            $suspendAt = new DateTime("tomorrow {$this->suspendAt}", $tz);
        }
        if ($trigger < 0 && abs($trigger) > time()) {
            $suspendAt = new DateTime(sprintf("@%d", abs($trigger)), $tz);
        }
        return $suspendAt->getTimestamp();
    }

    public function getNextResume(?DateTime $dt, ?DateTime $tm): int
    {
        if (!$dt) {
            return $this->getNextResumeAt()->getTimestamp();
        }
        $time = explode(':', $tm?->format('H:i') ?? $this->resumeAt);
        return $dt->setTime(...array_map('intval', $time))->getTimestamp();
    }

}