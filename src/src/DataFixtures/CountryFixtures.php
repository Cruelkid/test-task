<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture implements DependentFixtureInterface
{
    public const FIRST_COUNTRY = 'gb';
    public const SECOND_COUNTRY = 'ua';

    public function load(ObjectManager $manager): void
    {
        $gb = new Country();
        $gb->setName('Great Britain')
            ->setLocale($this->getReference(LocaleFixtures::FIRST_LOCALE));
        $manager->persist($gb);

        $ua = new Country();
        $ua->setName('Ukraine')
            ->setLocale($this->getReference(LocaleFixtures::SECOND_LOCALE));
        $manager->persist($ua);

        $this->addReference(self::FIRST_COUNTRY, $gb);
        $this->addReference(self::SECOND_COUNTRY, $ua);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LocaleFixtures::class
        ];
    }
}
