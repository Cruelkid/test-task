<?php

namespace App\DataFixtures;

use App\Entity\VatRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VatRateFixtures extends Fixture implements DependentFixtureInterface
{
    public const VR_0 = 'first';
    public const VR_1 = 'second';
    public const VR_2 = 'third';
    public const VR_3 = 'fourth';

    public static function getReferenceKey($key): string {
        return sprintf('VR_%s', $key);
    }

    public function load(ObjectManager $manager): void
    {
        $vr1 = new VatRate();
        $vr1->setRate(5.5)
            ->addCountry($this->getReference(CountryFixtures::FIRST_COUNTRY));
        $manager->persist($vr1);

        $vr2 = new VatRate();
        $vr2->setRate(10)
            ->addCountry($this->getReference(CountryFixtures::FIRST_COUNTRY));
        $manager->persist($vr2);

        $vr3 = new VatRate();
        $vr3->setRate(7)
            ->addCountry($this->getReference(CountryFixtures::SECOND_COUNTRY));
        $manager->persist($vr3);

        $vr4 = new VatRate();
        $vr4->setRate(13.3)
            ->addCountry($this->getReference(CountryFixtures::SECOND_COUNTRY));
        $manager->persist($vr4);

        $this->addReference(self::getReferenceKey(0), $vr1);
        $this->addReference(self::getReferenceKey(1), $vr2);
        $this->addReference(self::getReferenceKey(2), $vr3);
        $this->addReference(self::getReferenceKey(3), $vr4);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CountryFixtures::class
        ];
    }
}
