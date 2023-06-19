<?php

namespace App\Service;

use App\Entity\GroupEntity;
use App\Entity\UserEntity;
use App\Entity\WorkstationEntity;
use App\Security\User;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class WorkstationsService
{

    /** @var Collection<GroupEntity> */
    protected Collection $groups;

    /** @var Collection<WorkstationEntity> */
    protected Collection $workstations;

    /** @var Collection<UserEntity> */
    protected Collection $users;

    public function __construct(
        protected string $resources,
        protected LoggerInterface $logger
    ) {
        $data = Yaml::parseFile("{$this->resources}/workstations.yaml");
        $this->groups = new Collection(array_map(
            fn($key, $values) => new GroupEntity($key, ...$values),
            array_keys($data['group']),
            $data['group']
        ));
        $this->logger->info($this->groups);
        $this->workstations = new Collection(array_map(
            function($key, array $values) {
                $config = ['hostname' => $key];
                $config['group'] = $this->groups->firstWhere('id', $values['group']);
                if (array_key_exists('includes', $values)) {
                    $config['includes']  = $values['includes'];
                }
                return new WorkstationEntity(...$config);
            },
            array_keys($data['workstation']),
            $data['workstation']
        ));
        $this->users = new Collection(array_map(
            function($key, array $values) {
                $config = ['username' => $key];
                $config['email'] = "{$key}@travelfusion.com";
                $config['workstations'] = array_map(
                    fn($id) => $this->workstations->firstWhere('hostname', $id),
                    $values
                );
                return new UserEntity(...$config);
            },
            array_keys($data['user']),
            $data['user']
        ));
    }

    public function getWorkstations(?User $user): array {
        if (!$user) {
            return [];
        }
        /** @var UserEntity|null $ws_user */
        $entity = $this->users->firstWhere('email', $user->getEmail());
        return $entity ? $entity['workstations'] : [];
    }
}