<?php

namespace App\DataFixtures;

use App\Entity\Locale;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocaleFixtures extends Fixture
{
    public const FIRST_LOCALE = 'English';
    public const SECOND_LOCALE = 'Ukrainian';

    public function load(ObjectManager $manager): void
    {
        $enLocale = new Locale();
        $enLocale->setName('English')
            ->setIsoCode('en');
        $manager->persist($enLocale);

        $uaLocale = new Locale();
        $uaLocale->setName('Ukrainian')
            ->setIsoCode('ua');
        $manager->persist($uaLocale);

        $this->addReference(self::FIRST_LOCALE, $enLocale);
        $this->addReference(self::SECOND_LOCALE, $uaLocale);

        $manager->flush();
    }
}
