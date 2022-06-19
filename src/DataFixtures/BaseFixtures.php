<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixtures extends Fixture
{
    protected ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadData($manager);
    }

    abstract function loadData(ObjectManager $manager);

    protected function create(string $className, callable $factory, int $num = 0)
    {
        $entity = new $className;
        $factory($entity);

        $this->manager->persist($entity);

        $this->addReference("$className|$num", $entity);
        return $entity;
    }


    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i =0; $i <$count; $i++){
            $entity = $this->create($className, $factory, $i);
        }
        $this->manager->flush();
    }

    private array $referencesIndex;

    protected function getRandomReference(string $className): object
    {
        if (! isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $reference) {
                if (str_starts_with($key, $className . '|')) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$className])) {
            throw new \Exception('Не найдены ссылки на класс: ' . $className);
        }

        $num = mt_rand(0, count($this->referencesIndex[$className]) - 1);

        return $this->getReference($this->referencesIndex[$className][$num]);
    }

    
}
