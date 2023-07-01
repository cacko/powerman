<?php

namespace App\Entity;

use Symfony\Component\Yaml\Yaml;

/**
 * @property-read string $hostname
 * @property-read GroupEntity $group
 * @property-read ?StateEntity $state
 */
class WorkstationEntity extends AbstractAppEntity
{

    protected ?StateEntity $state = null;

    public function __construct(
        protected string      $hostname,
        protected GroupEntity $group,
        protected string      $location
    )
    {

    }

    protected function loadState(): StateEntity
    {
        $default = $this->group->getDefaultTrigger();
        $config = [
            'trigger' => $default,
            'ack' => 0,
            'last_ack' => 0,
        ];

        if (!file_exists($this->location)) {
            $this->state = $this->writeState($config);
            return $this->state;
        }
        $data = Yaml::parseFile($this->location);
        $state = new StateEntity(...[...$config, ...$data]);
        $trigger = $state->trigger;
        $nextTrigger = $this->group->getNextTrigger($trigger);
        if ($trigger != $nextTrigger) {
            $this->state = $this->writeState([
                'trigger' => $nextTrigger,
                'ack' => 0,
                'last_ack' => $state->last_ack
            ]);
        } else {
            $this->state = $state;
        }
        return $this->state;
    }

    public function writeState(array $config): StateEntity
    {
        $config = static::cleanKeys($config);
        $yaml = Yaml::dump($config);
        file_put_contents($this->location, $yaml);
        return new StateEntity(...$config);
    }


    public function offsetGet(mixed $offset): mixed
    {
        return match ($offset) {
            'state' => $this->state ?? $this->loadState(),
            default => parent::offsetGet($offset),
        };
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }


}