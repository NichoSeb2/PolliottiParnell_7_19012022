<?php

namespace App\DataFixtures;

use App\Entity\Society;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SocietyFixtures extends Fixture {
    public const SOCIETY_ZERO_REFERENCE = "society-0-reference";

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void {
        $society = new Society();
        $society
            ->setName("Society Zero")
            ->setPassword($this->passwordHasher->hashPassword(
                $society, 
                "azerty"
            ))
        ;
        $this->addReference(self::SOCIETY_ZERO_REFERENCE, $society);
        $manager->persist($society);
        $manager->flush();
    }
}
