<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $siteName = (new Setting())
            ->setKey('Site Name')
            ->setValue('ESHOP MART BD');

        $manager->persist($siteName);

        $sitePhoneNumber = (new Setting())
            ->setKey('Site Phone Number')
            ->setValue('01000000000');

        $manager->persist($sitePhoneNumber);

        $orderPhone1 = (new Setting())
            ->setKey('Order Phone 1')
            ->setValue('01000000000');

        $manager->persist($orderPhone1);

        $orderPhone2 = (new Setting())
            ->setKey('Order Phone 2')
            ->setValue('01000000000');

        $manager->persist($orderPhone2);

        $orderPhone3 = (new Setting())
            ->setKey('Order Phone 3')
            ->setValue('X');

        $manager->persist($orderPhone3);

        $siteEmail = (new Setting())
            ->setKey('Site Email Address')
            ->setValue('contact@eshopmartbd.com');

        $manager->persist($siteEmail);

        $shopAddress = (new Setting())
            ->setKey('Shop Address')
            ->setValue('Dhaka, Bangladesh');

        $manager->persist($shopAddress);

        $facebookUrl = (new Setting())
            ->setKey('Facebook URL')
            ->setValue('http://facebook.com/#your_page_link');

        $manager->persist($facebookUrl);

        $instagramUrl = (new Setting())
            ->setKey('Instagram URL')
            ->setValue('http://instagram.com/#your_profile_link');

        $manager->persist($instagramUrl);

        $youtubeUrl = (new Setting())
            ->setKey('Youtube URL')
            ->setValue('http://youtube.com/#your_channel_link');

        $manager->persist($youtubeUrl);

        $manager->flush();
    }
}
