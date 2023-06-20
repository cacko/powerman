<?php

namespace App\Entity;

use Symfony\Component\Yaml\Yaml;

class WorkstationEntity extends AbstractAppEntity
{

    protected ?StateEntity $state = null;

    public function __construct(
        protected string $hostname,
        protected GroupEntity $group,
        protected string $location
    ) {

    }

    protected function loadState()
    {
        if(!file_exists($this->location)) {
            $default = $this->group->getDefaultTrigger()?->getTimestamp() ?: 0;
            $this->writeState([
                'trigger' => $default,
                'ack' => 0
            ]);
        }
        $data = Yaml::parseFile($this->location);
        return new StateEntity(...$data);
    }

    protected function writeState(array $config)
    {
        $yaml = Yaml::dump($config);
        file_put_contents($this->location, $yaml);
    }


    public function offsetGet(mixed $offset): mixed
    {
        return match($offset) {
            'state' => $this->loadState(),
            default => parent::offsetGet($offset),
        };
    }

}