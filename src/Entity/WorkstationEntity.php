<?php

namespace App\Entity;

use Symfony\Component\Yaml\Yaml;

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

    protected function loadState()
    {
        if (!file_exists($this->location)) {
            $default = $this->group->getDefaultTrigger()?->getTimestamp() ?: 0;
            $config = [
                'trigger' => $default,
                'ack' => 0
            ];
            return $this->writeState($config);
        }
        $config = Yaml::parseFile($this->location);

        $state = new StateEntity(...$config);
        $trigger = $state['trigger'];
        $nextTrigger = $this->group->getNextTrigger($trigger)?->getTimestamp() ?: 0;
        if ($trigger != $nextTrigger) {
            return $this->writeState([
                'trigger' => $nextTrigger,
                'ack' => 0
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

}