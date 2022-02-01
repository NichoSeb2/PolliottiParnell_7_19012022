<?php

namespace App\DataFixtures;

use App\Entity\Society;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SocietyFixtures extends Fixture {
    public const SOCIETY_ONE_REFERENCE = "society-1-reference";
    public const SOCIETY_TWO_REFERENCE = "society-2-reference";

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void {
        $society = new Society();
        $society
            ->setName("Society One")
            ->setPassword($this->passwordHasher->hashPassword(
                $society, 
                "azerty"
            ))
        ;
        $this->addReference(self::SOCIETY_ONE_REFERENCE, $society);

        $manager->persist($society);

        $society = new Society();
        $society
            ->setName("Society Two")
            ->setPassword($this->passwordHasher->hashPassword(
                $society, 
                "azerty"
            ))
        ;
        $this->addReference(self::SOCIETY_TWO_REFERENCE, $society);

        $manager->persist($society);

        $manager->flush();
    }
}
