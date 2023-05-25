<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture 
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }



    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin->setEmail("admin@admin.fr")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->hashPassword($admin, 'azerazer'))
        ;

        $manager->persist($admin);
        $manager->flush();
    }
}
