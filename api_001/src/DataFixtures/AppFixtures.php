<?php

namespace App\DataFixtures;

use App\Entity\DragonTreasure;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 25; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $user->setUsername($faker->userName());

            $treasure = new DragonTreasure($faker->lexify('Treasure ?????'));
            $treasure->setTextDescription($faker->paragraph(5));
            $treasure->setValue($faker->numberBetween(0, 1000000));
            $treasure->setCoolFactor($faker->numberBetween(1, 10));
            $treasure->setIsPublished($faker->boolean());
            $treasure->setOwner($user);

            $manager->persist($user);
            $manager->persist($treasure);
        }

        $manager->flush();
    }
}
