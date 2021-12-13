<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 6; $i++) {
            $product = new Product();
            $product->setName('Product' . $i)
                ->setDescription('Product description' . $i)
                ->setPrice((mt_rand(10, 100)))
                ->setVatRate($this->getReference(VatRateFixtures::getReferenceKey($i % 4)));
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VatRateFixtures::class
        ];
    }
}
