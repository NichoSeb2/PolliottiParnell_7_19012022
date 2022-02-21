<?php

namespace App\DataFixtures;

use App\Entity\Society;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SocietyFixtures extends Fixture {
    public const SOCIETY_DEMO_REFERENCE = "society-demo-reference";

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void {
        $society = new Society();
        $society
            ->setName("Demo Society")
            ->setPassword($this->passwordHasher->hashPassword(
                $society, 
                "Password"
            ))
        ;
        $this->addReference(self::SOCIETY_DEMO_REFERENCE, $society);

        $manager->persist($society);
        $manager->flush();
    }
}
