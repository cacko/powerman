<?php

namespace App\Service;

use App\Entity\GroupEntity;
use App\Entity\UserEntity;
use App\Entity\WorkstationEntity;
use App\Security\User;
use Cmixin\BusinessDay;
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
        protected array           $resources,
        protected string          $states,
        protected LoggerInterface $logger
    )
    {
        BusinessDay::enable('Carbon\Carbon', 'gb-engwales');
        $data = $this->loadResources($this->resources);
        $this->workstations = new Collection([]);
        $this->groups = new Collection(array_map(
            function ($key, $values) {
                $group = new GroupEntity($key, ...$values);
                $this->workstations->push(...array_map(
                    fn($id) => new WorkstationEntity(
                        $id,
                        $group,
                        $this->states . DIRECTORY_SEPARATOR . "{$id}.yaml"
                    ),
                    $values['hostnames']
                ));
                return $group;
            },
            array_keys($data['group']),
            $data['group']
        ));
        $this->users = new Collection(array_map(
            function ($key, array $values) {
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

    protected function loadResources(array $resources)
    {
        return array_reduce($resources, function ($data, $res) {
            $resData = [];
            if (is_dir($res)) {
                $resData = $this->loadResources(glob(sprintf("%s/{,*/,*/*/,*/*/*/}*.yaml", $res), GLOB_BRACE));
            } elseif (is_file($res)) {
                $resData = Yaml::parseFile($res);
            }
            return array_merge_recursive($data, $resData);
        }, []);
    }

    public function getWorkstations(?User $user): array
    {
        if (!$user) {
            return [];
        }
        /** @var UserEntity|null $ws_user */
        $entity = $this->users->firstWhere('email', $user->getEmail());
        return $entity ? $entity['workstations'] : [];
    }

    /**
     * @param string $hostname
     * @return WorkstationEntity
     */
    public function getWorkstation(string $hostname): WorkstationEntity
    {
        /** @var WorkstationEntity $entity */
        $entity = $this->workstations->firstWhere('hostname', $hostname);
        assert($entity);
        return $entity;
    }
}