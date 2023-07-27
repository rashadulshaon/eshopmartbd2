<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $om): void
    {
        $admin = new Admin();
        $admin->setEmail('admin@eBuyHat.com');
        $admin->setPlainPassword('asdf456');
        $admin->setRoles(["ROLE_ADMIN"]);
        $om->persist($admin);

        $om->flush();
    }
}
