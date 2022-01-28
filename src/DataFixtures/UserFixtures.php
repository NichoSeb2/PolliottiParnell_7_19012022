<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\SocietyFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface {
    public function getDependencies() {
        return [
            SocietyFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void {
        for ($i = 1; $i <= 5; $i++) { 
            $user = new User();
            $user
                ->setFirstName("FirstName ". $i)
                ->setLastName("LastName ". $i)
                ->setEmail("firstname.lastname@gmail.com")
                ->setSociety($this->getReference(SocietyFixtures::SOCIETY_ZERO_REFERENCE))
            ;
            $manager->persist($user);
            $manager->flush();
        }
    }
}
