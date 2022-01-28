<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
        for ($i = 1; $i <= 15; $i++) { 
            $product = new Product();
            $product
                ->setName("Product ". $i)
                ->setDescription("An awesome product")
                ->setColor(array_rand(["Red", "Blue", "Pink", "Gold", "Silver", "White", "Black"]))
                ->setPrice(rand(99, 999) + (rand(0, 99)) / 100)
            ;
            $manager->persist($product);
            $manager->flush();
        }
    }
}
