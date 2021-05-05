<?php

namespace App\DataFixtures;

use App\Entity\Evenement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EvenementFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $evenement = new Evenement();
            $evenement->setTitle($faker->jobTitle)
                ->setDescription($faker->paragraph())
                ->setImage($faker->imageUrl($width = 640, $height = 480))
                ->setCreatedAt($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'));
            $manager->persist($evenement);
        }

        // $evenement->setTitle('mon 1 er titre');
        // $evenement->setDescription("mon text");
        // $evenement->setImage('mon image');
        
        $manager->flush();
    }
}
