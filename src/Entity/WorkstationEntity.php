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
            return $this->writeState($config);
        }
        $data = Yaml::parseFile($this->location);
        $state = new StateEntity(...[...$config, ...$data]);
        $trigger = $state->trigger;
        $nextTrigger = $this->group->getNextTrigger($trigger);
        if ($trigger != $nextTrigger) {
            return $this->writeState([
                'trigger' => $nextTrigger,
                'ack' => 0,
                'last_ack' => $state->last_ack
            ]);
        }
        return $state;
    }

    protected function writeState(array $config): StateEntity
    {
        $yaml = Yaml::dump($config);
        file_put_contents($this->location, $yaml);
        return new StateEntity(...$config);
    }


    public function offsetGet(mixed $offset): mixed
    {
        return match ($offset) {
            'state' => $this->loadState(),
            default => parent::offsetGet($offset),
        };
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }


}