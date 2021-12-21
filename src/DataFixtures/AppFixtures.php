<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 10; $i++) {
            $animal = new Animal();
            $animal->setName('Animal n°'.$i);
            $animal->setAge(2);
            $animal->setType("Berger");
            $animal->setRace("Chien");
            $animal->setDescription("Lorem Ipsum is simply dummy text of the printing and typesetting industry.");
            $animal->setGender("Mâle");
            $animal->setSterilised(false);
            $animal->setReserved(false);
            $animal->setDateArrived(new \Datetime());
            $manager->persist($animal);
        }

        $manager->flush();
    }
}
