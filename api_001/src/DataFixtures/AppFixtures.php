<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $book = new Book;
            $book->setTitle($faker->name());
            $book->setCoverText($faker->text());
            $manager->persist($book);
        }

        $manager->flush();
    }
}
