<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Contact;
use DateTimeZone;
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
            ->setPassword($this->encoder->hashPassword($admin, 'azerazer'));
        $manager->persist($admin);

        for ($i = 1; $i <= 5; $i++) { 
            $contact = new Contact();
            $timezone = new DateTimeZone('Europe/Paris');
            $contact->setEmail("user".$i."@user.fr")
                    ->setName("Joe".$i)
                    ->setSubject("Hello Fred")
                    ->setMessage("lorem ipsum dolor sit amet, consectetur adip.")
                    ->setCreatedAt(new \DateTime("now", $timezone))
                    ->setAdministrator($admin);
            $manager->persist($contact);
            $manager->flush();
        }
    }
}
